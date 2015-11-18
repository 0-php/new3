<?php
class sysitem_api_item_topdcount{
    public $apiDescription = "统计商品数量";
    public function getParams()
    {
        $return['params'] = array(
            'user_id' => ['type'=>'int','valid'=>'required','description'=>'公众店铺id','example'=>'2','default'=>''],
            'goods_zt' => ['type'=>'string','valid'=>'','description'=>'商品状态','example'=>'2','default'=>''],
        );

        return $return;
    }
    public function itemCount($params)
    {
        $filter['user_id'] = $params['user_id'];
        if($params['goods_zt'])
        {
            $filter['goods_zt'] = $params['goods_zt'];
        }
        $objMdlItemStatus = app::get('topd')->model('goods');
        $count = $objMdlItemStatus->count($filter);
        return $count;
    }
}
