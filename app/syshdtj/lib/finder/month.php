<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */

class syshdtj_finder_month{
    //订单详情，后期优化
    /*public $detail_shop = '产品订单';
    public $detail_topd = '推荐的公众店铺';
    public $detail_concert = '演唱会门票';

    public function detail_shop($tradeId)
    {
        //订单基本信息查询
        $objTrade = kernel::single('systrade_data_trade');
        $trade = $objTrade->getTradeInfo('*',array('tid'=>$tradeId));
        $trade['status_des'] = $this->tradeStatus[$trade['status']];
        $pagedata['trade'] = $trade;

        //订单支付信息查询
        $params['tids'] = $tradeId;
        $params['status'] =implode(',',array('succ','progress'));
        $params['fields'] = "*";
        $payment = app::get('systrade')->rpcCall('payment.bill.get',$params);
        $pagedata['payment'] = $payment;
        //订单发货信息查询
        $pagedata['logi'] = app::get('systrade')->rpcCall('delivery.logistics.tracking.get',array('tid'=>$tradeId));

        return view::make('systrade/admin/trade/detail.html', $pagedata)->render();
    }

    public function detail_topd($tradeId)
    {
        $params = array(
            'tid' => $tradeId,
        );
        $objMdlOrder = app::get('systrade')->model('order');
        $orders = $objMdlOrder->getList("*",$params);
        $pagedata['goodsItems'] = $orders;
        return view::make('systrade/admin/trade/detail_item.html', $pagedata)->render();
    }

    public function detail_concert($tradeId)
    {
        $objTrade = kernel::single('systrade_data_trade');
        $trade = $objTrade->getTradeInfo('user_id',array('tid'=>$tradeId));
        $users = kernel::single('sysuser_passport')->memInfo($trade['user_id']);
        $pagedata['user'] = $users;

        return view::make('systrade/admin/trade/detail_users.html', $pagedata)->render();
    }*/

    public $column_name = '角色名称';
    public function column_name(&$colList, $list)
    {
        foreach($list as $k=>$row)
        {
            if($row['type'] == '供应商'){
                $shop = kernel::single('sysshop_data_shop')->getShopById($row['type_id'],'shop_name');
                $colList[$k] = $shop['shop_name'];
            }elseif($row['type'] == '公众店铺'){
                $user = app::get('syshdtj')->rpcCall('user.get.account.name',array('user_id'=>$row['type_id']),'buyer');
                $colList[$k] = array_values($user)[0];
            }elseif(in_array($row['type'], array('供应商所在县','行政省','行政市','行政县'))){
                $colList[$k] =  app::get('topc')->rpcCall('logistics.area',array('area'=>$row['type_id']));
            }elseif($row['type'] == '恒大微购'){
                $colList[$k] = '恒大微购';
            }
        }
    }
}
