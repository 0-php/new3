<?php
class sysuser_api_hdvipList {

    /**
     * 接口作用说明
     */
    public $apiDescription = '获取恒大微购VIP虚拟折扣卡列表';

    /**
     * 定义应用级参数，参数的数据类型，参数是否必填，参数的描述
     * 用于在调用接口前，根据定义的参数，过滤必填参数是否已经参入
     * user.coupon.list
     */
    public function getParams()
    {
        //接口传入的参数
        $return['params'] = array(
            'page_no'   => ['type'=>'int',        'valid'=>'', 'default'=>'', 'example'=>'', 'description'=>'分页当前页数,默认为1','default'=>'','example'=>''],
            'page_size' => ['type'=>'int',        'valid'=>'', 'default'=>'', 'example'=>'', 'description'=>'每页数据条数,默认20条','default'=>'','example'=>''],
            'fields'    => ['type'=>'field_list', 'valid'=>'', 'default'=>'', 'example'=>'', 'description'=>'需要的字段','default'=>'','example'=>''],
            'orderBy'   => ['type'=>'string',     'valid'=>'', 'default'=>'', 'example'=>'', 'description'=>'排序','default'=>'','example'=>''],
            'user_id'   => ['type'=>'int',        'valid'=>'required', 'default'=>'', 'example'=>'', 'description'=>'用户ID必填','default'=>'','example'=>''],
            'shop_id'   => ['type'=>'int',        'valid'=>'', 'default'=>'', 'example'=>'', 'description'=>'店铺ID','default'=>'','example'=>''],
            'type'      => ['type'=>'int',        'valid'=>'', 'default'=>'', 'example'=>'', 'description'=>'卡类型','default'=>'','example'=>''],
            'coupon_status'  => ['type'=>'string','valid'=>'', 'default'=>'', 'example'=>'', 'description'=>'折扣卡状态','default'=>'','example'=>''],
        );

        return $return;
    }

    public function couponList($params)
    {
        $objMdlUserCoupon = app::get('syspromotion')->database()->createQueryBuilder();
        $couponData = $objMdlUserCoupon
            ->select("*")
            ->from('syspromotion_hdvip', 'h')
            ->leftJoin('h', 'syspromotion_hdvip_ncard', 'n', 'h.coupon_id = n.coupon_id')
            ->where("n.user_id = '".$params['user_id']."'")
            ->andWhere("h.type = '".$params['type']."'")
            ->andWhere("h.coupon_status = '".$params['coupon_status']."'")
            ->setFirstResult(($params['page_no']))
            ->setMaxResults($params['page_size'])
            ->execute()->fetchAll();
        $itemData = array(
                'coupons' => $couponData,
                'count' => count($couponData),
        );
        return $itemData;
    }
}
