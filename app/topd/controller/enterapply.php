<?php

/**
 * @brief 商家入驻申请及查看页面
 */
class topd_ctl_enterapply extends topd_controller{

    public $nomenu = true;


    /**
     * @brief 构造函数
     * 分销商
     * @return
     */
    public function __construct()
    {
        parent::__construct();
        //检测是否开店
        if($this->userInfo['fenxiaodp']['fenxiao_zt'] == 'finish'){
            redirect::action('topd_ctl_index@index')->send();exit;
        }
    }

    /**
     * @brief 入驻申请 function
     * 已经修改为分销商
     * @return html
     */
    public function apply()
    {
        if($this->userInfo['fenxiaodp']['fenxiao_zt'] == "successful") {
            redirect::action('topd_ctl_enterapply@checkPlan')->send();exit;
        }elseif($this->userInfo['fenxiaodp']['fenxiao_zt'] == "active") {
            redirect::action('topd_ctl_enterapply@checkPay')->send();exit;
        }else {
            $pagedata = $this->_pubdata();
            $this->set_tmpl('commonpage');
            if($_POST['license'] != "true") {
                //获取配置好的入驻协议
                $pagedata['content'] = app::get('topd')->getConf('topd.register.setting_topd_license');
                $this->contentHeaderTitle = '您还未提交开店申请';
                return $this->page('topd/enterapply/premise.html', $pagedata);
            } else {
                $this->contentHeaderTitle = '填写开店申请资料';
                return $this->page('topd/enterapply/apply.html', $pagedata);
            }
        }
    }

    /**
     * @brief 开店支付
     * 分销商
     * @return thml
     */
    public function checkPay()
    {
        $pagedata = $this->_pubdata();
        if($this->userInfo['fenxiaodp']){
            $pagedata['fenxiaodp'] = $this->userInfo['fenxiaodp'];
            list($pagedata['fenxiaodp']['regions'],$pagedata['fenxiaodp']['region_id']) = explode(':', $pagedata['fenxiaodp']['fenxiao_shengid']);
            $pagedata['fenxiaodp']['region_id'] = str_replace('/', ',', $pagedata['fenxiaodp']['region_id']);
            $pagedata['fenxiaodp']['rmb'] = app::get('topd')->getConf('topd.register.setting_topd_rmb');
            if($pagedata['fenxiaodp']['fenxiao_type'] == 'student'){
                $pagedata['fenxiaodp']['fenxiao_type'] = "在校学生";
            }else{
                $pagedata['fenxiaodp']['fenxiao_type'] = "社会人士";
            }
        }
        $this->set_tmpl('commonpage');
        $this->contentHeaderTitle = '费用支付';
        return $this->page('topd/enterapply/checkpay.html', $pagedata);
    }

    /**
     * @brief 入驻申请查看
     * 分销商
     * @return thml
     */
    public function checkPlan()
    {
        $pagedata = $this->userInfo['fenxiaodp'];
        $this->set_tmpl('commonpage');
        $this->contentHeaderTitle = '查看开店申请进度';
        return $this->page('topd/enterapply/checkPlan.html', $pagedata);
    }

    /**
     * @brief 保存入驻申请信息
     * 分销商
     * @return
     */
    public function saveApply()
    {
        $objShopType = kernel::single('topd_data_enterapply');
        $post = input::get();

        //处理区域
        $syslogLibArea = kernel::single('syslogistics_data_area');
        $post['fenxiao_shengid'] = $syslogLibArea->getSelectArea($post['area'][0]);
        if($post['fenxiao_shengid'])
        {
            $areaId =  str_replace(",","/", $post['area'][0]);
            $children = $syslogLibArea->getChildrenById(substr($areaId,-6));
            if(!empty($children)){
                return $this->splash('error',null,"请选择完整区域！",true);
            }
            $post['fenxiao_shengid'] = $post['fenxiao_shengid'] . ':' . $areaId;
        }
        else
        {
            return $this->splash('error',null,'请选择完整区域!',true);
        }

        try{
            $url = url::route('topd.home');
            $this->__checkpost($post);
            $objShopType->savedata($post);
            $msg = app::get('topd')->_('申请入驻成功');
            return $this->splash('success',$url,$msg,true);
        } catch (\LogicException $e) {
            return $this->splash('error',null,$e->getMessage(),true);
        }
    }


    /**
     * @brief  检测输入项
     *
     * @param $postdata
     * 修改为分销商，但是区域是否完整无法验证
     * @return array
     */
    private function __checkpost(&$postdata)
    {
        $this->userInfo = userAuth::getUserInfo(); //会员信息
        if($this->userInfo['fenxiaodp']['fenxiao_zt'] != 'active' && !empty($this->userInfo['fenxiaodp']['fenxiao_zt'])){
            $msg = app::get('topd')->_("无需重复提交申请！");
            throw new \LogicException($msg);
        }
        if(!$postdata['fenxiao_type'])
        {
            $msg = app::get('topd')->_("身份类型不允许为空！");
            throw new \LogicException($msg);
        }
        if(!$postdata['fenxiao_name'])
        {
            $msg = app::get('topd')->_("真实姓名不允许为空！");
            throw new \LogicException($msg);
        }
        if(!$postdata['area'])
        {
            $msg = app::get('topd')->_("区域不允许为空！");
            throw new \LogicException($msg);
        }
        if(!$postdata['fenxiao_call'])
        {
            $msg = app::get('topd')->_("手机号不允许为空！");
            throw new \LogicException($msg);
        }
        if(!$postdata['fenxiao_cid'])
        {
            $msg = app::get('topd')->_("身份证号不允许为空！");
            throw new \LogicException($msg);
        }
        $postdata['user_id'] = $this->userInfo['userId'];  //绑定会员
        $postdata['fenxiao_dpname'] = '公众店铺';     //店铺名称
        $postdata['fenxiao_cidimg'] = 0;
        $postdata['fenxiao_time'] = time();
        $postdata['fenxiao_tuijuan'] = $_COOKIE['fenxiao_tuijuan'];
        unset($postdata['area']);
    }

    /**
     * @brief 申请编辑时的公有数据
     * 已修改为分销商
     * @return array
     */
    private function _pubdata(){
        $pagedata['fenxiao_type'] = array(
            'student'=>'在校学生',
            'Sociology'=>'社会人士',
        );
        return $pagedata;
    }
}

