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
        'shopnum' => array(
            'type' => 'number',
            'label' => app::get('syshdtj')->_('产品总单'),
            'comment' => app::get('syshdtj')->_('产品总单'),
            'in_list' => true,
            'default_in_list' => true,
            'unsigned' => true,
            'required' => true,
            'width' => 50,
        ),
        'topdnum' => array(
            'type' => 'number',
            'label' => app::get('syshdtj')->_('店铺总单'),
            'comment' => app::get('syshdtj')->_('店铺总单'),
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
            'label' => app::get('syshdtj')->_('产品总收入'),
            'comment' => app::get('syshdtj')->_('产品总收入'),
            'in_list' => true,
            'default_in_list' => true,
            'width' => 50,
        ),
        'topdprice' => array(
            'type' => 'money',
            'required' => true,
            'default' => '0',
            'label' => app::get('syshdtj')->_('开店总收入'),
            'comment' => app::get('syshdtj')->_('开店总收入'),
            'in_list' => true,
            'default_in_list' => true,
            'width' => 50,
        ),
        'concertprice' => array(
            'type' => 'money',
            'required' => true,
            'default' => '0',
            'label' => app::get('syshdtj')->_('演唱会总收入'),
            'comment' => app::get('syshdtj')->_('演唱会总收入'),
            'in_list' => true,
            'default_in_list' => true,
            'width' => 50,
        ),
        'priceshop' => array(
            'type' => 'money',
            'required' => true,
            'default' => '0',
            'label' => app::get('syshdtj')->_('支出给供应商'),
            'comment' => app::get('syshdtj')->_('支出给供应商'),
            'in_list' => true,
            'default_in_list' => true,
            'width' => 50,
        ),
        'price' => array(
            'type' => 'money',
            'required' => true,
            'default' => '0',
            'label' => app::get('syshdtj')->_('支出给代理商'),
            'comment' => app::get('syshdtj')->_('支出给代理商'),
            'in_list' => true,
            'default_in_list' => true,
            'width' => 50,
        ),
        'pricetopd' => array(
            'type' => 'money',
            'required' => true,
            'default' => '0',
            'label' => app::get('syshdtj')->_('支出给公众店铺'),
            'comment' => app::get('syshdtj')->_('支出给公众店铺'),
            'in_list' => true,
            'default_in_list' => true,
            'width' => 50,
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
    'comment' => app::get('syshdtj')->_('恒大微购结算'),
);

