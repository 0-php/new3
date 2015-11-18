<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2015 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  www.ec-os.net ShopEx License
 *
 * 恒大vip卡验证
 *
 */
final class syspromotion_api_hdvip_verify {

    public $apiDescription = '使用优惠券促销';

    public function getParams()
    {
        $return['params'] = array(
            'card_account' => ['type'=>'string', 'valid'=>'', 'default'=>'', 'example'=>'', 'description'=>'折扣卡号'],
            'card_password' => ['type'=>'string', 'valid'=>'', 'default'=>'', 'example'=>'', 'description'=>'折扣卡密码'],
            'used_platform' => ['type'=>'string', 'valid'=>'', 'default'=>'', 'example'=>'', 'description'=>'平台'],
            'use_bound' => ['type'=>'string', 'valid'=>'', 'default'=>'', 'example'=>'', 'description'=>'范围'],
        );
        return $return;
    }

    /**
     *  恒大vip卡验证
     * @param  array $params 筛选条件数组
     * @return array         返回应用信息
     */
    public function couponUse($params)
    {
        $filter['user_id'] = $params['oauth']['account_id'];
        $filter['vip_card'] = $params['vip_card'];
        $filter['vip_pas'] = $params['vip_pas'];
        $filter['used_platform'] = in_array($params['used_platform'], array('0', '1', '2','4')) ? $params['used_platform'] : '1';
        $filter['use_bound'] = in_array($params['use_bound'], array('0', '1', '2', '3')) ? $params['use_bound'] : '1';
        return kernel::single('syspromotion_solutions_hdvip')->useCoupon($filter);
    }
}

