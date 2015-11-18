<?php
/**
 * HDVG
 */

class topd_ctl_index extends topd_controller {

    public function feedback()
    {
        $status = 'success';
        $msg = '提交成功';

        try
        {
            app::get('topd')->rpcCall('feedback.add', input::get(), 'user');
        }
        catch (LogicException $e)
        {
            $msg = $e->getMessage();
            $status = 'error';
        }

        return $this->splash($status,$url,$msg,true);
    }

	public function index()
	{
        //检测是否开店
        if($this->userInfo['fenxiaodp']['fenxiao_zt'] != 'finish'){
            redirect::action('topd_ctl_enterapply@apply')->send();exit;
        }

        //业务信息
        $topd_id = $this->userInfo['userId'];
        $pagedata['pd'] = app::get('syshdtj')->database()->createQueryBuilder()
            ->select("COUNT( CASE WHEN `order_from` = '产品订单' THEN 1 ELSE NULL END ) AS `shop`")
            ->addSelect("COUNT( CASE WHEN `order_from` = '店铺开店' THEN 1 ELSE NULL END ) AS `topd`")
            ->addSelect("COUNT( CASE WHEN `order_from` = '演唱会门票' THEN 1 ELSE NULL END ) AS `yhk`")
            ->addSelect("SUM( CASE WHEN `order_from` = '产品订单' THEN price ELSE 0 END ) AS `shopprice`")
            ->addSelect("SUM( CASE WHEN `order_from` = '店铺开店' THEN price ELSE 0 END ) AS `topdprice`")
            ->addSelect("SUM( CASE WHEN `order_from` = '演唱会门票' THEN price ELSE 0 END ) AS `yhkprice`")
            ->from('syshdtj_order')
            ->where("topd_id= $topd_id")
            ->execute()->fetch();

        //获取店铺上架商品数量
        $countShopOnsaleItem = app::get('topd')->rpcCall('item.topdcount',array('user_id'=>$this->userInfo['userId'],'goods_zt'=>'onsale'));
        //获取店铺下架商品数量
        $countShopInstockItem = app::get('topd')->rpcCall('item.topdcount',array('user_id'=>$this->userInfo['userId'],'goods_zt'=>'instock'));

        $pagedata['countShopOnsaleItem'] = $countShopOnsaleItem;
        $pagedata['countShopInstockItem'] = $countShopInstockItem;
        $pagedata['shop'] = $this->userInfo['fenxiaodp'];
        list($pagedata['shop']['fenxiao_clk0'],$pagedata['shop']['fenxiao_clk1'],$pagedata['shop']['fenxiao_clk2']) = explode('|', $pagedata['shop']['fenxiao_clk']);
		$this->contentHeaderTitle = app::get('topd')->_('我的工作台');
		return $this->page('topd/index.html', $pagedata);
	}
}
