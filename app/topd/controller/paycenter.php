<?php
class topd_ctl_paycenter extends topc_controller{
    public function __construct($app)
    {
        parent::__construct();
        $this->setLayoutFlag('paycenter');
        // 检测是否登录、
        $this->userInfo = userAuth::getUserInfo(); //会员信息
        if($this->userInfo['fenxiaodp']['fenxiao_zt'] != 'active'){
            redirect::action('topd_ctl_index@index')->send();exit;
        }
    }

    public function index()
    {
        //这里提交的以后需要提取出来做并发和安全验证
        $vip['isvip'] = input::get("isvip");  //判断是在线支付还是vip卡支付
        if($vip['isvip'] == 1) {
            $vip['vip_card'] = input::get("vip_card");
            $vip['vip_pas'] = input::get("vip_pas");
            if ($vip['vip_card'] && $vip['vip_pas']) {
                $vip['platform'] = '0';
                $vip['use_bound'] = '3';
                $pagedata['msg'] = app::get('topc')->rpcCall('promotion.hdvip.verify',$vip,'buyer');
                if($pagedata['msg'] == "true"){
                    header('Location: ' . url::action('topd_ctl_paycenter@finish'));
                }
            }
        }
        //获取可用的支付方式列表
        $payType['platform'] = 'ispc';
        $payments = app::get('topc')->rpcCall('payment.get.list',$payType,'buyer');
        $filter['fields'] = "*";
        $pagedata['tids'] = $this->userInfo['fenxiaodp']['fenxiao_time'].$this->userInfo['fenxiaodp']['user_id'];
        $pagedata['payments'] = $payments;
        $pagedata['mainfile'] = "topd/payment/payment.html";
        $pagedata['trades']['cur_money'] = app::get('topd')->getConf('topd.register.setting_topd_rmb');
        $pagedata['trades']['pay_type'] = 'topdonline';
        $pagedata['trades']['payment_id'] = app::get('ectools')->model('payments')->genId(time());
        app::get('topd')->model('dp')->update(array('payment_id' => $pagedata['trades']['payment_id'],),array('user_id' => $this->userInfo['userId'],));//更新支付ID
        return $this->page('topd/payment/index.html', $pagedata);
    }

    public function dopayment()
    {
        $postdata = input::get();
        $payment = $postdata['payment'];
        $payment['user_id'] = userAuth::id();
        $payment['platform'] = "pc";
        $payment['money'] = app::get('topd')->getConf('topd.register.setting_topd_rmb');
        try
        {
            app::get('topc')->rpcCall('payment.trade.topdpay',$payment);
        }
        catch(Exception $e)
        {
            $msg = $e->getMessage();
            echo '<meta charset="utf-8"><script>alert("'.$msg.'"); window.close();</script>';
            exit;
        }
        $url = url::action('topd_ctl_paycenter@finish',$payment);
        return $this->splash('success',$url,$msg,true);
    }

    public function finish()
    {
        $post = input::get();
        $pagedata['mainfile'] = "topd/payment/finish.html";
        $pagedata['money'] = $post['money'];
        return $this->page('topd/payment/index.html', $pagedata);
    }
}


