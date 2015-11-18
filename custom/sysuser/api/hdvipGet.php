<?php
class sysuser_api_hdvipGet {

    /**
     * 接口作用说明
     */
    public $apiDescription = '获取用户恒大微购折扣卡信息';

    /**
     * 定义应用级参数，参数的数据类型，参数是否必填，参数的描述
     * 用于在调用接口前，根据定义的参数，过滤必填参数是否已经参入
     */
    public function getParams()
    {
        //接口传入的参数
        $return['params'] = array(
            'user_id' => ['type'=>'int','valid'=>'required', 'default'=>'', 'example'=>'', 'description'=>'用户ID必填','default'=>'','example'=>''],
            'coupon_code' => ['type'=>'string','valid'=>'required', 'default'=>'', 'example'=>'', 'description'=>'VIP卡标识','default'=>'','example'=>''],
        );

        return $return;
    }

    public function couponGet($apiData)
    {
        $filter['user_id'] = intval($apiData['user_id']);
        $filter['coupon_code'] = $apiData['coupon_code'];
        return app::get('syspromotion')->model('hdvip_ncard')->getRow('*', $filter);
    }
}
