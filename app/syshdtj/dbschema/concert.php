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
        'agent_id' =>array (
            'type' => 'table:account@agent',
            'required' => true,
            'label' => app::get('syshdtj')->_('所属代理商'),
            'comment' => app::get('syshdtj')->_('所属代理商'),
            'width' => 110,
            'searchtype' => 'has',
            'filtertype' => false,
            'filterdefault' => 'true',
            'in_list' => true,
            'default_in_list'=>true,
        ),
        'shopnum' => array(
            'type' => 'number',
            'label' => app::get('syshdtj')->_('产品单'),
            'comment' => app::get('syshdtj')->_('产品单'),
            'in_list' => true,
            'default_in_list' => true,
            'unsigned' => true,
            'required' => true,
            'width' => 50,
        ),
        'topdnum' => array(
            'type' => 'number',
            'label' => app::get('syshdtj')->_('店铺单'),
            'comment' => app::get('syshdtj')->_('店铺单'),
            'in_list' => true,
            'default_in_list' => true,
            'unsigned' => true,
            'required' => true,
            'width' => 50,
        ),
        'concertnum' => array(
            'type' => 'number',
            'label' => app::get('syshdtj')->_('演唱会'),
            'comment' => app::get('syshdtj')->_('演唱会'),
            'in_list' => true,
            'default_in_list' => true,
            'unsigned' => true,
            'required' => true,
            'width' => 50,
        ),
        'shopprice' => array(
            'type' => 'money',
            'required' => true,
            'default' => '0',
            'label' => app::get('syshdtj')->_('产品收入'),
            'comment' => app::get('syshdtj')->_('产品收入'),
            'in_list' => true,
            'default_in_list' => true,
            'width' => 50,
        ),
        'topdprice' => array(
            'type' => 'money',
            'required' => true,
            'default' => '0',
            'label' => app::get('syshdtj')->_('开店收入'),
            'comment' => app::get('syshdtj')->_('开店收入'),
            'in_list' => true,
            'default_in_list' => true,
            'width' => 50,
        ),
        'concertprice' => array(
            'type' => 'money',
            'required' => true,
            'default' => '0',
            'label' => app::get('syshdtj')->_('演唱会收入'),
            'comment' => app::get('syshdtj')->_('演唱会收入'),
            'in_list' => true,
            'default_in_list' => true,
            'width' => 50,
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
    'comment' => app::get('syshdtj')->_('代理商结算'),
);

