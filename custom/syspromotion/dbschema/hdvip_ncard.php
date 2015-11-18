<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2015 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */

// 恒大折扣卡
return  array(
    'columns' => array(
        'card_id' => array(
            'type' => 'number',
            'required' => true,
            'autoincrement' => true,
            'width' => 110,
            'label' => app::get('syspromotion')->_('id'),
            'comment' => app::get('syspromotion')->_('折扣卡id'),
        ),
        'coupon_id' => array(
            'type' => 'table:hdvip@syspromotion', // 字段类型
            'width' => 110,
            'label' => app::get('syspromotion')->_('id'),
            'comment' => app::get('syspromotion')->_('折扣方案id'),
        ),
        'coupon_code' => array(
            'type' => 'string',
            'length' => 32,
            'label' => app::get('syspromotion')->_('折扣卡识别码'),
            'comment' => app::get('syspromotion')->_('折扣卡识别码'),
        ),
        'user_id' =>array (
            'type' => 'table:account@sysuser', // 字段类型
            'label' => app::get('syspromotion')->_('会员id'),
            'comment' => app::get('syspromotion')->_('会员'),
            'in_list' => true,
            'default_in_list' => true,
            'order' => 0,
        ),
        'canuse_start_time' => array(
            'type' => 'time',
            'in_list' => true,
            'default_in_list' => true,
            'width' => '100',
            'label' => app::get('syspromotion')->_('折扣卡生效时间'),
            'comment' => app::get('syspromotion')->_('折扣卡生效时间'),
        ),
        'canuse_end_time' => array(
            'type' => 'time',
            'in_list' => true,
            'default_in_list' => true,
            'width' => '100',
            'label' => app::get('syspromotion')->_('折扣卡失效时间'),
            'comment' => app::get('syspromotion')->_('折扣卡失效时间'),
        ),
        'money' => array(
            'type' => 'money',
            'default' => '0',
            'in_list' => true,
            'default_in_list' => true,
            'width' => '50',
            'order' => 14,
            'label' => app::get('syspromotion')->_('总金额'),
            'comment' => app::get('syspromotion')->_('总金额'),
        ),
        'deduct_money' => array(
            'type' => 'money',
            'default' => '0',
            'in_list' => true,
            'default_in_list' => true,
            'width' => '50',
            'order' => 14,
            'label' => app::get('syspromotion')->_('剩余金额'),
            'comment' => app::get('syspromotion')->_('剩余金额'),
        ),
        'card_status' => array(
            'type' => array(
                '0' => app::get('syspromotion')->_('已失效'),
                '1' => app::get('syspromotion')->_('有效期内多次使用'),
                '2' => app::get('syspromotion')->_('限额使用'),
                '3' => app::get('syspromotion')->_('一次性使用'),
                '4' => app::get('syspromotion')->_('暂停使用'),
            ),
            'default' => '0',
            'in_list' => true,
            'default_in_list' => true,
            'width' => '50',
            'order' => 14,
            'label' => app::get('syspromotion')->_('折扣卡状态'),
            'comment' => app::get('syspromotion')->_('折扣卡状态'),
        ),
    ),
    'primary' => 'card_id',
    'comment' => app::get('syspromotion')->_('恒大微购虚拟折扣卡'),
);
