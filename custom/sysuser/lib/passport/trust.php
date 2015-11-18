<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */

class sysuser_passport_trust{

    function login($usrdata)
    {
        if( pamAccount::getAccountId() && $usrdata['data']['type'] == 'pc')
        {
            return redirect::action('topc_ctl_default@index');
        }
        if( pamAccount::getAccountId() && $usrdata['data']['type'] == 'wap')
        {
            return redirect::action('topm_ctl_default@index');
        }

        if($usrdata['user_id'] && $usrdata['istrue']=='yes')
        {
            $saveData['pam_account'] = $this->pamUserData($usrdata['data']['data']);

            $usrdata['data']['data']['user_id']=$usrdata['user_id'];
            if( !app::get('sysuser')->model('trustinfo')->save($usrdata['data']['data']) )
            {
                throw new \LogicException('信任登陆数据保存失败');
            }

            $userInfo = array(
                'userId' => $usrdata['user_id'],
                'loginName' => $usrdata['account']
            );
        }
        else
        {
            if($usrdata['data']['rsp'] == 'succ')
            {
                $userInfo = $this->saveLoginData($usrdata);
            }
            else
            {
                $msg = app::get('sysuser')->_('保存失败，请重试');
                return $msg;
            }
        }
        return $userInfo;
    }

    //设置绑定信息的数据处理方法
    function sysuserBdData($result)
    {
        $data['email'] = $result['data']['data']['email'];
        $data['name'] = $result['login_account']?$result['login_account']:' ';
        $data['addr'] = $result['data']['data']['address'];
        $data['sex'] = $this->gender($result['data']['data']['gender']);
        $data['username'] = $result['login_account']?$result['login_account']:' ';
        $grade = app::get('sysuser')->model('user_grade')->getRow('grade_id',array('default_grade'=>1));
        $data['grade_id'] = $grade['grade_id'];
        return $data;
    }
    //设置绑定信息的数据处理方法
    function pamUserBdData($result)
    {
        $loginName = $result['login_account'];
        $loginPassword = $result['login_password'];
        $return = array(
            'login_account' => $loginName,
            'login_password' => pam_encrypt::make(trim($loginPassword)),
            //'disabled' =>  'false',
            'email'=>$result['data']['data']['email'],
            'createtime' => time()
        );
        return $return;
    }

    //保存数据到相应的数据表 pam_user   trustinfo sysuser_user表中
    function saveLoginData($result,&$msg)
    {
        if($result['isaleady']!='noaleady')
        {
            $saveData['sysuserUser'] = $this->sysuserData($result['data']['data']);
            $saveData['pamAccount'] = $this->pamUserData($result['data']['data']);
        }
        else
        {
            $saveData['sysuserUser'] = $this->sysuserBdData($result);
            $saveData['pamAccount'] = $this->pamUserBdData($result);
        }

        $sysUserMdl = app::get('sysuser')->model('user');
        $pamUserMdl = app::get('sysuser')->model('account');

        $db = app::get('sysuser')->database();
        $db->beginTransaction();
        try
        {
            //保存到sysuser user
            if(!$pamUserMdl->insert($saveData['pamAccount']))
            {
                throw new \LogicException('pamAccount数据保存失败');
            }
            $userId = $saveData['pamAccount']['user_id'];
            $saveData['sysuserUser']['user_id'] = $userId;

            if(!(kernel::single('sysuser_data_trustlogin')->saveAttr($userId,$saveData['sysuserUser'],$msg)))
            {
                throw new \LogicException('信任登陆属性保存失败');
            }

            $result['data']['data']['user_id']=$userId;
            if( !app::get('sysuser')->model('trustinfo')->save($result['data']['data']) )
            {
                throw new \LogicException('信任登陆信息保存失败');
            }
            $db->commit();
        }
        catch (Exception $e)
        {
            $db->rollback();
            throw $e;
        }
        $userInfo = array(
            'userId' => $userId,
            'loginName' => $saveData['pamAccount']['login_account']
        );
        return $userInfo;
    }

    //绑定现有信息的数据处理方法
    public function sysuserData($result)
    {
        $data['name'] = empty($result['nickname']) ? $result['realname'] : $result['nickname'];
        $data['addr'] = $result['address'];
        $data['sex'] = $this->gender($result['gender']);
        $data['username'] = empty($result['nickname'])?$result['realname']:$result['nickname'];
        return $data;
    }
    //绑定现有信息的数据处理方法
    public function pamUserData($data)
    {
        if($data['nickname']=='')
        {
          $login_name = $data['realname'].'_'.$data['openid'];
        }
        else
        {
          $login_name = $data['nickname'].'_'.$data['openid'];
        }
        $return = array(
            'login_account' => $login_name,
            'login_password' => md5(time()),
            'email' => $data['email'],
            //'disabled' => 'false',
            'login_type'=> 'trustlogin',
            'createtime' => time()
        );
        return $return;
    }

    //男女判别方法
    public function gender($data)
    {
        if($data=='男')
        {
            return '1';
        }
        elseif($data=='女')
        {
            return '0';
        }
        else
        {
            return '1';
        }
    }

}

