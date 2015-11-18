<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 *
 * 商家会员,登录注册流程
 */
class agent_passport {

    public $sellerId = null;

    public $sellerName = null;

    public function __construct()
    {
        $this->app = app::get('sysshop');
        kernel::single('base_session')->start();

        $this->sellerId = pamAccount::getAccountId();
    }

    /**
     * 根据企业账号ID，获取对应的企业登录名称
     *
     * @param int $sellerId
     *
     * @return array
     */
    public function getSellerName($sellerId)
    {
        $accountShopModel = app::get('sysshop')->model('account');
        $sellerData = $accountShopModel->getRow('login_account',array('seller_id'=>$sellerId));
        return $sellerData['login_account'];
    }

    /**
     * 获取登录会员的信息
     *
     * @return array
     */
    public function getSellerData($sellerId="")
    {
        if( !$this->sellerData )
        {
            $accountShopModel = app::get('agent')->model('account');
            if(!$sellerId) $sellerId = pamAccount::getAccountId();
            $this->sellerData = $accountShopModel->getRow('*',array('agent_id'=>$sellerId));
        }
        return $this->sellerData;
    }

    public function getShopId($sellerId)
    {
        if( !$this->shopId )
        {
            $objMdlSeller = app::get('sysshop')->model('seller');
            $result = $objMdlSeller->getRow('shop_id',array('seller_id'=>$sellerId));
            $this->shopId = $result['shop_id'];
        }
        return $this->shopId;
    }

    /**
     *  商家登录
     *
     * @param string  $loginAccount 用户名
     * @param string  $loginPassword 密码
     *
     * @return boole
     */
    public function login($loginAccount, $loginPassword)
    {
        //检查数据安全
        $loginAccount = utils::_filter_input($loginAccount);
        $loginPassword = utils::_filter_input($loginPassword);

        $agentId = $this->__verifyLogin($loginAccount, $loginPassword);

        if( $agentId )
        {
            $num = app::get('agent')->model('account')->count(array('agent_id'=>$agentId));
            if( !$num )
            {
                throw new \LogicException(app::get('agent')->_('数据异常，请联系客服'));
            }
        }

        pamAccount::setSession($agentId, trim($loginAccount));

        return true;
    }

    /**
     * 验证登录的用户名和密码是否一致
     *
     * @param string $loginName 登录名
     * @param string $password  密码
     *
     * @return int $userId
     */
    private function __verifyLogin($loginName, $password )
    {
        if( empty($loginName) )
        {
            throw new \LogicException(app::get('agent')->_('请输入账号'));
        }

        if( empty($password) )
        {
            throw new \LogicException(app::get('agent')->_('请输入密码'));
        }

        $filter = array('agent_username'=>trim($loginName),'disabled'=>'0');
        $account = app::get('agent')->model('account')->getRow('agent_id,agent_pwd',$filter);
        if( !$account )
        {
            throw new \LogicException(app::get('agent')->_('该账号未注册'));
        }
        
        if ( !pam_encrypt::check($password, $account['agent_pwd']))
        {
            throw new \LogicException(app::get('agent')->_('用户名或密码错误'));
        }

        return $account['agent_id'];
    }

    /**
     * 新增一个商家用户，传入为验证过后的数据
     *
     * @param array $data 新增商家用户信息
     *
     * @return int userId
     */
    public function signupSeller($data)
    {
        //检查数据安全
        $data = utils::_filter_input($data);

        $accountShopModel = app::get('sysshop')->model('account');
        $shopUserModel = app::get('sysshop')->model('seller');

        //检查注册账号合法性
        $this->checkSignupAccount(trim($data['login_account']) );

        //检查密码合法，是否一致
        $this->checkPassport($data['login_password'],$data['psw_confirm']);

        //检查基本的数据
        $this->checkSignup($data);

        $pamShopData = $this->__preAccountSeller($data);

        $db = app::get('sysshop')->database();
        $db->beginTransaction();

        try
        {
            if( !$sellerId = $accountShopModel->insert($pamShopData) )
            {
                throw new \LogicException(app::get('sysshop')->_('注册失败'));
            }

            $sellerData = $this->__preSeller($sellerId, $data);
            if( !$shopUserModel->insert($sellerData) )
            {
                throw new \LogicException(app::get('sysshop')->_('注册失败'));
            }
            $db->commit();

        }
        catch(\Excessive $e)
        {
            $db->rollback();
            throw $e;
        }

        pamAccount::setSession($sellerId, trim($data['login_account']));
        return true;
    }

    private function __preAccountSeller($data)
    {
        $pamShopData['login_account'] = trim($data['agent_username']);
        $pamShopData['agent_name'] = trim($data['agent_name']);
        $loginPassword = pam_encrypt::make(trim($data['agent_pwd']));//静态方法  哈希加密
        $pamShopData['login_password'] = $loginPassword;
        
        return $pamShopData;
    }

    private function __preSeller($sellerId, $data)
    {
        $sellerData['seller_id'] = intval($sellerId);
        $sellerData['seller_type'] = !empty($data['seller_type']) ? $data['seller_type'] : '0';
        $sellerData['name'] = $data['name'];
        $sellerData['mobile'] = $data['mobile'];
        $sellerData['email'] = $data['email'];
        $sellerData['modified_time'] = time();
        return $sellerData;
    }

    /**
     * @brief 检查注册数据的合法性
     *
     * @param array  $data 注册表单提交的数据
     *
     * @return bool
     */
    public function checkSignup($data)
    {
        //检查数据安全
        $data = utils::_filter_input($data);


        if( empty($data['agentmsg_mobile']) || !$this->checkStrType($data['agentmsg_mobile'], 'mobile') )
        {
            if( $this->isExists($data['agentmsg_mobile'], 'mobile') )
            {
                $msg = $this->app->_('该手机号已被注册，请重新换一个');
                throw new \LogicException($msg);
            }
            $msg = $this->app->_('请输入正确的手机号码');
            throw new \LogicException($msg);
        }

        if( empty($data['agentmsg_email']) || !$this->checkStrType($data['agentmsg_email'], 'email') )
        {
            if( strlen( trim($data['agentmsg_email']) ) > 50 )
            {
                $msg = $this->app->_('邮箱长度不能超过50个字符');
                throw new \LogicException($msg);
            }

            if( $this->isExists($data['agentmsg_email'], 'email') )
            {
                $msg = $this->app->_('该邮箱已被注册，请重新换一个');
                throw new \LogicException($msg);
            }
            $msg = $this->app->_('请输入正确的邮箱');
            throw new \LogicException($msg);
        }

        return true;
    }

    /**
     * @brief 检查传入字符的是否为预想类型
     *
     * @param string $string 传入的字符
     * @param string $type   字符预想的类型
     *
     * @return string
     */
    public function checkStrType($string, $type)
    {
        if( $type == 'email' && strpos($string,'@') )
        {
            if( !preg_match("/^[a-z\d][a-z\d_.]*@[\w-]+(?:\.[a-z]{2,})+$/",$string) )
            {
                throw new \LogicException(app::get('sysshop')->_('请输入正确的邮箱地址'));
            }
            return true;
        }

        if( $type == 'mobile' && preg_match("/^1[34578]{1}[0-9]{9}$/",$string) ) return true;

        return false;
    }

    /**
     * 检查密码是否合法，密码是否一致(注册，找回密码，修改密码)调用
     * @params string $password  密码
     * @params string $psw_confirm 确认密码
     *
     * @return bool
     */
    public function checkPassport($password, $psw_confirm){
        $passwdlen = strlen( trim($password) );

        if($passwdlen<6)
        {
            $msg = $this->app->_('密码长度不能小于6位');
            throw new \LogicException($msg);
        }

        if($passwdlen>20)
        {
            $msg = $this->app->_('密码长度不能大于20位');
            throw new \LogicException($msg);
        }

        if(preg_match("/^[a-z]*$/i", trim($password)) )
        {
            $msg = $this->app->_('密码不能为纯字母');
            throw new \LogicException($msg);
        }

        if(preg_match("/^[0-9]*$/i", trim($password)) )
        {
            $msg = $this->app->_('密码不能为纯数字');
            throw new \LogicException($msg);
        }

        $password = trim($password);
        $psw_confirm = trim($psw_confirm);
        if($password != $psw_confirm)
        {
            $msg = $this->app->_('输入的密码不一致');
            throw new \LogicException($msg);
        }

        return true;
    }//end function

    /**
     * @brief  验证传入注册账号的合法性
     *
     * @param $loginName
     *
     * @return bool
     */
    public function checkSignupAccount($loginName)
    {
        if( empty($loginName) )
        {
            throw new \LogicException(app::get('agent')->_('请输入用户名'));
        }

        if( mb_strlen(trim($loginName)) < 4 )
        {
            throw new \LogicException(app::get('agent')->_('登录账号最少4个字'));
        }
        else if( mb_strlen(trim($loginName)) > 20 )
        {
            throw new \LogicException(app::get('agent')->_('登录账号过长，请换一个重试'));
        }

        if( is_numeric($loginName) )
        {
            throw new \LogicException(app::get('agent')->_('登录账号不能全为数字'));
        }

        if(!preg_match('/^[^\x00-\x2d^\x2f^\x3a-\x3f]+$/i', trim($loginName)) )
        {
            throw new \LogicException(app::get('agent')->_('该登录账号包含非法字符'));
        }

        //判断账号是否存在
        if( $this->isExists($loginName,'account') )
        {
            throw new \LogicException(app::get('agent')->_('该账号已经被占用，请换一个重试'));
        }

        return true;
    }//end function

    /**
     * @brief 判断注册信息账号，手机号，邮箱是否已近注册
     *
     * @param string $str 验证字符串
     * @param string $type 验证类型 账号，手机号，邮箱
     *
     * @return bool true已存在 | false不存在
     */
    public function isExists($str, $type='account')
    {
        //检查数据安全
        $str = utils::_filter_input($str);

        if(empty($str)) return false;

        switch($type)
        {
            case 'account':
                $accountShopModel = app::get('agent')->model('account');
                $data = $accountShopModel->getRow('agent_id',array('agent_username'=>trim($str)));
                break;
            case 'mobile':
                $sysshopModel = app::get('agent')->model('agentmsg');
                $data = $sysshopModel->getRow('agent_id',array('agentmsg_mobile'=>trim($str)));
                break;
            case 'email':
                $sysshopModel = app::get('agent')->model('agentmsg');
                $data = $sysshopModel->getRow('agent_id',array('agentmsg_email'=>trim($str)));
                break;
        }
        return $data['agent_id'] ? true : false;
    }

    public function logout()
    {
        $this->sellerId = null;
        $this->sellerName = null;
        $this->shopId = null;
        $this->sellerData = null;
        kernel::single('base_session')->set_cookie_expires(0);

        parent::logout();
    }

    /**
     * @brief  商家密码修改
     *
     * @param array $data 商家密码
     *
     * @return int userId
     */

    public function modifyPwd($data)
    {
        //检查数据安全
        $data = utils::_filter_input($data);
     
        $accountShopModel = app::get('agent')->model('account');
        $filter = array('agent_id'=>pamAccount::getAccountId());
        $account = $accountShopModel->getRow('agent_id,agent_pwd',$filter);
        if( !$account ) return false;

        //检查密码合法，是否一致
        $this->checkPassport($data['login_password'],$data['psw_confirm']);

        if(!pam_encrypt::check($data['login_password_old'], $account['agent_pwd']))
        {
            throw new \LogicException(app::get('agent')->_('原密码填写错误，请重新填写!'));
        }

        $pamShopData['agent_pwd'] = pam_encrypt::make($data['login_password']);
        $pamShopData['agent_id'] = $filter['agent_id'];
       // $pamShopData['modified_time'] = time();
        if( !$sellerId = $accountShopModel->save($pamShopData) )
        {
            throw new \LogicException(app::get('agent')->_('修改失败'));
        }
        return true;
    }

    /**
     * @brief 后台商家重置密码
     *
     * @param int $sellerId 重置密码的seller_id
     * @param string $password 重置的新密码
     *
     * @return bool
     */
    public function resetPwd($sellerId, $data)
    {
        $data = utils::_filter_input($data);
        $accountShopModel = app::get('sysshop')->model('account');
        $filter = array('seller_id'=>$sellerId);
        $account = $accountShopModel->getRow('seller_id,login_password',$filter);
        if( !$account ) return false;


        //检查密码合法，是否一致
        $this->checkPassport($data['login_password'],$data['psw_confirm']);

        $pamShopData['login_password'] = pam_encrypt::make(trim($data['login_password']));
        $pamShopData['seller_id'] = $sellerId;
        $pamShopData['modified_time'] = time();
        if( !$sellerId = $accountShopModel->save($pamShopData) )
        {
            throw new \LogicException(app::get('sysshop')->_('修改失败'));
        }
        return true;
    }
}

