<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */

class sysuser_passport_callback{

    //检查第三方的数据
    public function checkData($ret)
    {
        if(!$ret)
        {
            throw new \LogicException(app::get('sysuser')->_('返回数据有误'));
        }
        $trustMdl = app::get('sysuser')->model('trustinfo');
        $pamUserMdl = app::get('sysuser')->model('account');
        $checkData = array(
            'openid'=>$ret['data']['openid'],
            'realname'=>$ret['data']['realname'],
        );
        $trustData = $trustMdl->getRow('trust_id,user_id',$checkData);
        //检查是否已经登录过了
        if($trustData)
        {
            $memberData = $pamUserMdl->getRow('user_id,login_account',array('user_id'=>$trustData['user_id']));
            if($memberData['user_id'])
            {
                $userInfo = array(
                    'userId' => $memberData['user_id'],
                    'loginName' => $memberData['login_account']
                );
                $params['module'] = 'sysuser_passport_trust';
                $params['data'] = $ret;

                userAuth::login($userInfo['userId'],trim($userInfo['loginName']));
                //pamAccount::setSession($userInfo['userId'], trim($userInfo['loginName']));
                if($ret['type']=='pc')
                {
                    kernel::single('topc_controller')->set_cookie('UNAME',userAuth::getLoginName());
                    $back_url = url::action("topc_ctl_trustlogin@postLogin");
                }
                if($ret['type']=='wap')
                {
                    kernel::single('topm_controller')->set_cookie('UNAME',userAuth::getLoginName());
                    $back_url = url::action("topm_ctl_trustlogin@postLogin");
                }
                echo "<script>top.window.location='".$back_url."'</script>";
            }
            else
            {
                $trustData = $trustMdl->delete($checkData);
                $params['module'] = 'sysuser_passport_trust';
                $params['redirect'] = base64_encode($back_url);
                $params['data'] = urlencode(json_encode($ret));
            }
        }
        else
        {
            $params['module'] = 'sysuser_passport_trust';
            $params['redirect'] = base64_encode($back_url);
            $params['data'] = urlencode(json_encode($ret));
        }
        return $params;
    }

    public function todecirct($data)
    {
        if($data['data']['type']=='pc')
        {
            $back_url = url::action("topc_ctl_trustlogin@postLogin");
        }
        if($data['data']['type']=='wap')
        {
            $back_url = url::action("topm_ctl_trustlogin@postLogin");
        }
        $this->login($data);
        return "<script>top.window.location='".$back_url."'</script>";
    }

    /**
    * 登录调用的方法
    * @param array $params 认证传递的参数,包含认证类型，跳转地址,第三方返回数据等
    */
    function login($params)
    {
        if($params)
        {
            $userInfo = kernel::single('sysuser_passport_trust')->login($params);
            if($userInfo)
            {
                userAuth::login($userInfo['userId'],trim($userInfo['loginName']));
                //pamAccount::setSession($userInfo['userId'], trim($userInfo['loginName']));
                if($params['data']['type']=='pc')
                {
                    kernel::single('topc_controller')->set_cookie('UNAME',userAuth::getLoginName());
                }
                if($params['data']['type']=='wap')
                {
                    kernel::single('topm_controller')->set_cookie('UNAME',userAuth::getLoginName());
                }
                return true;
            }
        }
        else
        {
            return false;
        }
    }
}

