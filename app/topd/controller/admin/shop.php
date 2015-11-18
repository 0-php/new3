<?php

/**
 * @brief 店铺
 */
class topd_ctl_admin_shop extends desktop_controller{

    /*
     * 公众店铺开店配置
     * Ric
     * */
    public function license()
    {
        if( $_POST['license'] )
        {
            $this->begin();
            app::get('topd')->setConf('topd.register.setting_topd_license',$_POST['license']);
            app::get('topd')->setConf('topd.register.setting_topd_rmb',$_POST['rmb']);
            $this->end(true, app::get('topd')->_('当前配置修改成功！'));
        }
        $pagedata['license'] = app::get('topd')->getConf('topd.register.setting_topd_license');
        $pagedata['rmb'] = app::get('topd')->getConf('topd.register.setting_topd_rmb');
        return $this->page('topd/license.html', $pagedata);
    }

    /*
     * 开店申请列表
     * Ric
     * */
    public function index()
    {
        return $this->finder('topd_mdl_dp',array(
            'title' => app::get('topd')->_('分销申请列表'),
            'use_buildin_delete'=>false,
            /*'actions'=>array(
                array(
                    'label'=>'发送邮件短信',
                    'submit'=>'?app=sysshop&ctl=admin_seller&act=messenger',
                ),
                array(
                    'label'=>app::get('sysshop')->_('同意分销'),
                    'href'=>'?app=sysshop&ctl=admin_seller&act=addSelfUser',
                    'target'=>'dialog::{title:\''.app::get('sysshop')->_('同意分销').'\',  width:500,height:320}',
                ),
            ),以后待用*/
        ));
    }

    /**
     * @brief 店铺列表tab
     * Ric
     */
    public function _views()
    {
        $subMenu = array(
            0=>array(
                'label'=>app::get('topd')->_('待审核'),
                'optional'=>false,
                'filter'=>array(
                    'fenxiao_zt'=>'successful',
                ),
            ),
            1=>array(
                'label'=>app::get('topd')->_('全部'),
                'optional'=>false,
            ),
            2=>array(
                'label'=>app::get('topd')->_('未支付'),
                'optional'=>false,
                'filter'=>array(
                    'fenxiao_zt'=>'active',
                ),
            ),
            3=>array(
                'label'=>app::get('topd')->_('审核驳回'),
                'optional'=>false,
                'filter'=>array(
                    'fenxiao_zt'=>'failing',
                ),
            ),
            4=>array(
                'label'=>app::get('topd')->_('审核通过'),
                'optional'=>false,
                'filter'=>array(
                    'fenxiao_zt'=>'finish',
                ),
            ),
            5=>array(
                'label'=>app::get('topd')->_('暂停使用'),
                'optional'=>false,
                'filter'=>array(
                    'fenxiao_zt'=>'stop',
                ),
            ),
        );
        return $subMenu;
    }

    /*
     * 通过分销填写优惠券
     * Ric
     * */
    public function openfenxiao($userid){
        $pagedata = kernel::single('sysuser_passport')->memInfo($userid);
        return $this->page('topd/admin/seller/openfenxiao.html', $pagedata);
    }

    /*
     * 通过审核
     * Ric
     * */
    public function update_shop_zt(){
        $this->_dpzt(input::get('user_id'),"finish","已同意分销！",'4',input::get('yhj'));
    }

    /*
     * 审核驳回
     * Ric
     * */
    public function bhfenxiao($userid){
        $this->_dpzt($userid,"failing","已经驳回申请！",'3');
    }

    /*
     * 店铺暂停
     * Ric
     * */
    public function ztfenxiao($userid){
        $this->_dpzt($userid,"stop","店铺已经成功暂停！",'5');
    }

    /*
     * 店铺恢复
     * Ric
     * */
    public function hffenxiao($userid){
        $this->_dpzt($userid,"finish","店铺已经成功恢复！",'4');
    }

    /*
     * 店铺状态改变函数
     * Ric
     * */
    private function _dpzt($userid,$dazt,$msg,$view,$yhj=0){
        $this->begin('?app=topd&ctl=admin_shop&act=index&view='.$view);
        try{
            $data = array(
                'fenxiao_zt' => $dazt,
            );

            $filter = array(
                'user_id' => $userid,
            );
            app::get('topd')->model('dp')->update($data,$filter);
            $this->hongbao($yhj,$userid);
        }
        catch(\LogicException $e)
        {
            $msg = $e->getMessage();
            $this->end(false,$msg);
        }
        $this->end(true,$msg);
    }

    /*
     * 通过分销填写优惠券
     * Ric
     * */
    public function mffenxiao($userid){
        $pagedata = kernel::single('sysuser_passport')->memInfo($userid);
        return $this->page('topd/admin/seller/mffenxiao.html', $pagedata);
    }

    /**
     * 未支付的也可以同意开通分销
     * Ric
     */
    public function openfenxiaoNoPay(){
        $this->begin('?app=topd&ctl=admin_shop&act=index&view=4');
        try {
            $data = array(
                'fenxiao_zt' => 'finish',
                'payment_id' => input::get('payment_id'),
                'fenxiao_payprice' => '0',
                'fenxiao_paytime' => time(),
                'payment_pay_id' => serialize(array('msg'=>'未付款直接开通的店铺！')),
            );

            $filter = array(
                'user_id' => $_POST['user_id'],
            );
            app::get('topd')->model('dp')->update($data,$filter);
            $this->hongbao(input::get('yhj'),$_POST['user_id']);
        }
        catch (\LogicException $e)
        {
            $msg = $e->getMessage();
            $this->end(false,$msg);
        }
        $this->end(true,"已同意分销");
    }

    /*
     * 开店发放红包
     * Ric
     * */
    public function hongbao($yhj,$user){
        if(!empty($yhj)){
            $data = array(
                'coupon_id' => 2,
                'coupon_code' => 'HDVGVIP2',
                'user_id' => $user,
                'canuse_start_time' => time(),
                'canuse_end_time' => time()+864000000,
                'money' => $yhj,
                'deduct_money' => $yhj,
                'card_status' => 1,
            );
            $n = app::get('syspromotion')->model('hdvip_ncard')->getList('money,deduct_money',array('user_id' => $user,'coupon_id' => 2));
            if(!empty($n)){
                $data['money'] +=$n['money'];
                $data['deduct_money'] +=$n['deduct_money'];
                $tdata = app::get('syspromotion')->model('hdvip_ncard')->update($data);
            }else{
                $tdata = app::get('syspromotion')->model('hdvip_ncard')->insert($data);
            }
            $log = array(
                'card_id' => $tdata,
                'time' => time(),
                'money' => $yhj,
                'msg' => "公众店铺开店赠送红包",
                'author' => pamAccount::getAccountId(),
            );
            app::get('syspromotion')->model('hdvip_log')->insert($log);
        }
    }
}


