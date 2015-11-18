<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */


/**
 * 外部支付接口统一调用的api类
 * @auther shopex ecstore dev dev@shopex.cn
 * @version 0.1
 * @package ectools.lib.payment
 */
class ectools_payment_api
{
    /**
     * @var object 应用对象的实例。
     */
    private $app;

    /**
     * 构造方法
     * @param object 当前应用的app
     * @return null
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * 支付返回后的同意支付处理
     * @params array - 页面参数
     * @return null
     */
    public function parse($params='')
    {
        // 取到内部系统参数
        $arr_pathInfo = explode('?', $_SERVER['REQUEST_URI']);
        $pathInfo = substr($arr_pathInfo[0], strpos($arr_pathInfo[0], "parse/") + 6);
        $objShopApp = $this->getAppName($pathInfo);
        $innerArgs = explode('/', $pathInfo);
        $class_name = array_shift($innerArgs);
        $method = array_shift($innerArgs);

        $arrStr = array();
        $arrSplits = array();
        $arrQueryStrs = array();
        // QUERY_STRING
        if (isset($arr_pathInfo[1]) && $arr_pathInfo[1])
        {
            $querystring = $arr_pathInfo[1];
        }
        if ($querystring)
        {
            $arrStr = explode("&", $querystring);

            foreach ($arrStr as $str)
            {
                $arrSplits = explode("=", $str);
                $arrQueryStrs[urldecode($arrSplits[0])] = urldecode($arrSplits[1]);
            }
        }
        else
        {
            if ($_POST)
            {
                $arrQueryStrs = $_POST;
            }
        }

        logger::info("支付返回信息记录：".var_export($arrQueryStrs,1));
        $payments = new $class_name($objShopApp);
        $ret = $payments->$method($arrQueryStrs);

        //判断是否是公众店铺支付 开始 Ric
        if(substr($ret['payment_id'],0,2) =='14'){
            $this->userInfo = userAuth::getUserInfo(); //会员信息
            if($this->userInfo['fenxiaodp']['fenxiao_zt'] != 'active' || $ret['payment_id'] != $this->userInfo['fenxiaodp']['payment_id']){
                redirect::action('topd_ctl_index@index')->send();exit;
            }
            if($ret['status'] == 'succ' || $ret['status'] == 'progress') { //判断是否支付成功
                app::get('topd')->model('dp')->update(array('fenxiao_payprice' => $ret['money'], 'fenxiao_paytime' => time(), 'fenxiao_zt' => 'successful', 'payment_app_id' => serialize(input::get()), 'payment_pay_id' => serialize($ret),), array('user_id' => $this->userInfo['userId'], 'payment_id' => $ret['payment_id']));//更新支付状态
                $this->_hdvgtopd($ret,$this->userInfo['fenxiaodp']); //提交记录到角色提成表
                header('Location: ' . url::action('topd_ctl_paycenter@finish', $ret));
            }
        }
        //结束 Ric


        logger::info("支付返回信息转换之后记录：".var_export($ret,1));
        // 支付结束，回调服务.
        if (!isset($ret['status']) || $ret['status'] == '') $ret['status'] = 'failed';

        $objPayments = app::get('ectools')->model('payments');
        $sdf = $objPayments->getRow('*',array('payment_id'=>$ret['payment_id']));
        if ($sdf)
        {
            $sdf['account'] = $ret['account'];
            $sdf['bank'] = $ret['bank'];
            $sdf['pay_account'] = $ret['pay_account'];
            $sdf['currency'] = $ret['currency'];
            $sdf['trade_no'] = $ret['trade_no'];
            $sdf['payed_time'] = $ret['t_payed'];
            $sdf['pay_app_id'] = $ret['pay_app_id'];
            $sdf['pay_type'] = $ret['pay_type'];
            $sdf['memo'] = $ret['memo'];
            $sdf['money'] = $ret['money'];
            $sdf['cur_money'] = $ret['cur_money'];
        }

        switch ($ret['status'])
        {
            case 'succ': //支付成功
                $this->_hdvgshop($sdf); //提交记录到角色提成表
            case 'progress': //已付款至担保方
                $this->_hdvgshop($sdf); //提交记录到角色提成表
                if ($sdf && $sdf['status'] != 'succ')
                {
                    $isUpdatedPay = payment::update($ret, $msg);
                    if($isUpdatedPay)
                    {
                        $params['payment_id'] = $sdf['payment_id'];
                        $params['fields'] = 'status,payment_id';
                        try
                        {
                            $paymentBill = app::get('ectools')->rpcCall('payment.bill.get',$params);
                        }
                        catch(Exception $e)
                        {
                            throw $e;
                        }

                        $db = app::get('ectools')->database();
                        $db->beginTransaction();
                        try
                        {
                            if($paymentBill['status'] == "succ" || $paymentBill['status'] == "progress")
                            {
                                foreach($paymentBill['trade'] as $value)
                                {
                                    app::get('ectools')->rpcCall('trade.pay.finish',array('tid'=>$value['tid'],'payment'=>$value['payment']));
                                }
                                $db->commit();
                            }
                        }
                        catch(\Exception $e)
                        {
                            $db->rollback();
                            throw $e;
                        }
                    }

                    //支付成功给支付网关显示支付信息
                    if(method_exists($payments, 'ret_result')){
                        $payments->ret_result($ret['payment_id']);
                    }
                }
                break;
            case 'REFUND_SUCCESS':
                // 退款成功操作
                if ($sdf)
                {
                    unset($sdf['payment_id']);
                    $obj_refund = app::get('ectools')->model('refund');
                    $sdf['refund_id'] = $obj_refund->gen_id();
                    $ret['status'] = 'succ';
                    if ($obj_refund->insert($sdf))
                    {
                        //处理单据的支付状态
                        $obj_refund_finish = kernel::service("order.refund_finish");
                        $obj_refund_finish->order_refund_finish($sdf, $ret['status'], 'font',$msg);
                    }
                }
                break;
            case 'PAY_PDT_SUCC':
                $ret['status'] = 'succ';
                // 无需更新状态.
                break;
            case 'failed': //支付失败
            case 'error':  //处理异常
            case 'cancel': //未支付
            case 'invalid': //invalid
            case 'timeout': //超时
                $is_updated = false;
                $isUpdatedPay = payment::update($ret, $msg);
                break;
        }

        // Redirect page.
        if ($sdf['return_url'])
        {
            //header('Location: '.kernel::removeIndex(request::root()).$sdf['return_url']);
            $sdf['return_url'] = unserialize($sdf['return_url']);
            header('Location: '.url::action($sdf['return_url'][0],$sdf['return_url'][1]));
        }
    }

    /**
     * 得到实例应用名
     * @params string - 请求的url
     * @return object - 应用实例
     */
    private function getAppName($strUrl='')
    {
        //todo.
        if (strpos($strUrl, '/') !== false)
        {
            $arrUrl = explode('/', $strUrl);
        }
        return app::get($arrUrl[0]);
    }

    /**
     * 各角色提成记录 公众店铺开店提成分配
     * Ric
     */
    public function _hdvgtopd($params,$info){
        $inhd = array(
            'topd_id' => $info['fenxiao_tuijuan'],
            'area' => $info['fenxiao_shengid'],
            'shop_area' => '无',
            'shop_id' => 0,
        );
        $tc = array(
            'topd' => app::get('site')->getConf('base.topd_topd'),
            'zxs' => app::get('site')->getConf('base.topd_zxs'),
            'zxx' => app::get('site')->getConf('base.topd_zxx'),
            'xzsheng' => app::get('site')->getConf('base.topd_xzsheng'),
            'xzshi' => app::get('site')->getConf('base.topd_xzshi'),
            'xzxian' => app::get('site')->getConf('base.topd_xzxian'),
        );
        $hdvg = array(
            'oid' => $info['user_id'],
            'price' => $params['money'],
            'payment' => $params['money'],
            'user_id' => $info['user_id'],
            'payed_time' =>$params['t_payed'],
            'order_from' => '店铺开店',
            'payment_id' => $params['payment_id'],
            'bank' => $params['bank'],
            'title' => '公众店铺开店',
            'spec_nature_info' => $info['fenxiao_name'].$info['fenxiao_cid'],
            'num' => 1,
        );
        $this->_hdvg($params['money'],$inhd,$tc,$hdvg);
        app::get('syshdtj')->model('order')->save($hdvg);
    }

    /**
     * 各角色提成记录 产品订单提成分配
     * Ric
     */
    private function _hdvgshop($info){
        $tc = array(
            'topd' => app::get('site')->getConf('base.shop_topd'),
            'zxs' => app::get('site')->getConf('base.shop_zxs'),
            'zxx' => app::get('site')->getConf('base.shop_zxx'),
            'gysszx' => app::get('site')->getConf('base.shop_gysszx'),
            'xzsheng' => app::get('site')->getConf('base.shop_xzsheng'),
            'xzshi' => app::get('site')->getConf('base.shop_xzshi'),
            'xzxian' => app::get('site')->getConf('base.shop_xzxian'),
        );
        $thdvg = array( //子订单公用信息
            'payed_time' =>$info['payed_time'],
            'bank' => $info['bank'],
            'payment_id' => $info['payment_id'],
            'user_id' => $info['user_id'],
            'order_from' => '产品订单',
        );
        $tid = app::get('ectools')->model('trade_paybill')->getRow('tid',array('payment_id'=>$info['payment_id']));
        $fields = "oid,price,total_fee,title,spec_nature_info,num,shop_id";
        $oid = app::get('systrade')->model('order')->getList($fields,array('tid'=>$tid['tid']));
        foreach($oid as $key => $val){
            $hdvg = $thdvg;
            $hdvg['oid'] = $val['oid'];
            $hdvg['price'] = $val['price'];
            $hdvg['payment'] = $val['total_fee'];
            $hdvg['title'] = $val['title'];
            $hdvg['spec_nature_info'] = $val['spec_nature_info'];
            $hdvg['num'] = $val['num'];
            //分销商和供应商信息
            $tuser_id = app::get('topd')->model('order')->getRow('user_id, cost_price',array('oid'=>$val['oid']));
            $topd = app::get('sysuser')->model('user')->getRow('area',array('user_id'=>$info['user_id']));
            if(empty($topd['area'])){
                $tidarea = app::get('systrade')->model('trade')->getList("receiver_state,receiver_city,receiver_district,buyer_area",array('tid'=>$tid['tid']));
                $topd['area'] = $tidarea['receiver_state']."/".$tidarea['receiver_city']."/".$tidarea['receiver_district'].":".$tidarea['buyer_area'];
                app::get('sysuser')->model('user')->save(array('user_id'=>$info['user_id'],'area'=>$topd['area'],));
            }
            $shop_area = app::get('sysshop')->model('shop')->getRow('area',array('shop_id'=>$val['shop_id']));
            $inhd = array(
                'topd_id' => $tuser_id['user_id'],
                'area' => $topd['area'],
                'shop_area' => $shop_area['area'], //还没有查询
                'shop_id' => $val['shop_id'],
                'cost_price' => $tuser_id['cost_price'],
            );

            $this->_hdvg($val['price'],$inhd,$tc,$hdvg);
            app::get('syshdtj')->model('order')->save($hdvg);
        }
    }

    /**
     * 各角色提成分配公用
     * Ric
     */
    private function _hdvg($profit,$inhd,$tc,&$hdvg){
        //处理供应商提成
        if(empty($inhd['shop_id'])){
            $hdvg['shop_id'] = 0;
            $hdvg['cost_price'] = 0.00;
        }else{
            $hdvg['shop_id'] = $inhd['shop_id'];
            $hdvg['cost_price'] = round($inhd['cost_price'],3);
        }

        //处理公众店铺提成
        $profit -= $hdvg['cost_price']; //除去供应商后的业绩
        if(!empty($inhd['topd_id'])) {
            $hdvg['topd_id'] = $inhd['topd_id'];
            if (strstr($tc['topd'], "元")) {
                $hdvg['topd_price'] = trim(str_replace("元", "", $tc['topd']));
            } else {
                $hdvg['topd_price'] = round($profit * trim($tc['topd']),3);
            }
        }else{
            $hdvg['topd_id'] = 0;
            $hdvg['topd_price'] = 0.00;
        }

        $profit -= $hdvg['topd_price']; //除去供应商、分销商后的业绩

        //处理供应商所在县的提成
        if(empty($inhd['shop_area']) || $inhd['shop_area'] == '无'){
            $hdvg['shop_area'] = '无';
            $hdvg['gszx_id'] = 0;
            $hdvg['gszx_price'] = 0.00;
        }else{
            $regionshop = explode(':',$inhd['shop_area']);//获取供应商区域
            $regionshop_id = explode('/',$regionshop[1]); //分解出id数组
            $hdvg['shop_area'] = $inhd['shop_area'];
            $hdvg['gszx_id'] = !empty($regionshop_id[2])?$regionshop_id[2]:(!empty($regionshop_id[1])?$regionshop_id[1]:0);
            if (strstr($tc['gysszx'], "元")) {
                $hdvg['gszx_price'] = trim(str_replace("元", "", $tc['gysszx']));
            } else {
                $hdvg['gszx_price'] = round($profit * trim($tc['gysszx']),3);
            }
        }

        //分解消费者各省、市、县id
        $hdvg['area'] = $inhd['area'];
        $region = explode(':',$inhd['area']);//获取消费者区域
        $region_id = explode('/',$region[1]); //分解出id数组
        $hdvg['szs_id'] = !empty($region_id[0])?$region_id[0]:0; //行政省id
        $hdvg['szshi_id'] = !empty($region_id[2])?$region_id[1]:0; //行政市id
        $hdvg['szx_id'] = !empty($region_id[2])?$region_id[2]:(!empty($region_id[1])?$region_id[1]:0); //行政县id

        if(empty($region_id[2])){ //判断是否为直辖市
            if (strstr($tc['zxs'], "元")) {
                $hdvg['szs_price'] = trim(str_replace("元", "", $tc['zxs']));
            } else {
                $hdvg['szs_price'] = round($profit * trim($tc['zxs']),3);
            }
            $hdvg['szshi_price'] = 0.00;
            if (strstr($tc['zxx'], "元")) {
                $hdvg['szx_price'] = trim(str_replace("元", "", $tc['zxx']));
            } else {
                $hdvg['szx_price'] = round($profit * trim($tc['zxx']),3);
            }
        }else{
            if (strstr($tc['xzsheng'], "元")) {
                $hdvg['szs_price'] = trim(str_replace("元", "", $tc['xzsheng']));
            } else {
                $hdvg['szs_price'] = round($profit * trim($tc['xzsheng']),3);
            }
            if (strstr($tc['xzshi'], "元")) {
                $hdvg['szshi_price'] = trim(str_replace("元", "", $tc['xzshi']));
            } else {
                $hdvg['szshi_price'] = round($profit * trim($tc['xzshi']),3);
            }
            if (strstr($tc['xzxian'], "元")) {
                $hdvg['szx_price'] = trim(str_replace("元", "", $tc['xzxian']));
            } else {
                $hdvg['szx_price'] = round($profit * trim($tc['xzxian']),3);
            }
        }
        //恒大微购获取
        $hdvg['hdvg_price'] = round(($profit - $hdvg['gszx_price'] - $hdvg['szs_price'] - $hdvg['szshi_price'] - $hdvg['szx_price']),3);
    }
}
