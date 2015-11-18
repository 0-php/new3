<?php

/**
 * ShopEx LuckyMall
 *
 * @author     ajx
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */

return  array(
    'columns' => array(
        'settlement_no' => array(
            'type' => 'bigint',
            'unsigned' => true,
            'required' => true,
            'in_list' => true,
            'is_title' => true,
            'filterdefault' => true,
            'default_in_list' => true,
            'label' => app::get('syshdtj')->_('账单编号'),
            'width' => '15',
        ),
        'shop_id' => array(
            'type' => 'table:shop@sysshop',
            'label' => app::get('syshdtj')->_('所属商家'),
            'width' => 110,
            'searchtype' => 'has',
            'filtertype' => false,
            'filterdefault' => 'true',
            'in_list' => true,
            'default_in_list'=>true,
        ),
        'tradecount' => array(
            //'type'=>'int(10)',
            'type' => 'number',
            'default' => '0',
            'label' => app::get('syshdtj')->_('订单数量'),
            'required' => true,
            'in_list' => true,
            'default_in_list'=>true,
        ),
        'item_fee_amount' => array(
            'type'=>'money',
            'default'=>0,
            'label' => app::get('syshdtj')->_('商品款'),
            'required' => true,
            'in_list' => true,
            'default_in_list'=>true,
        ),
        'settlement_status' => array(
            'type' => array(
                '1'=>'未结算',
                '2'=>'已结算',
            ),
            'default' => '1',
            'label' => app::get('syshdtj')->_('结算状态'),
            'in_list' => true,
            'default_in_list'=>true,
        ),
        'account_start_time' => array(
            'type' => 'time',
            'label' => app::get('syshdtj')->_('账单开始时间'),
            'in_list' => true,
            'default_in_list'=>true,
        ),
        'account_end_time' => array(
            'type' => 'time',
            'label' => app::get('syshdtj')->_('账单结束时间'),
            'in_list' => true,
            'default_in_list'=>true,
        ),
        'settlement_time' => array(
            'type' => 'time',
            'label' => app::get('syshdtj')->_('结算时间'),
            'in_list' => true,
            'default_in_list'=>true,
        ),
    ),
    'primary' => 'settlement_no',
    'comment' => app::get('syshdtj')->_('供应商结算'),
);

