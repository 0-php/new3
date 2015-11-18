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
        'coupon_id' => array(
            'type' => 'number',
            'required' => true,
            'autoincrement' => true,
            'width' => 110,
            'label' => app::get('syspromotion')->_('id'),
            'comment' => app::get('syspromotion')->_('折扣方案id'),
        ),
        'shop_id' => array(
            'type' => 'number',
            'required' => true,
            'in_list' => true,
            'default_in_list' => true,
            'width' => 100,
            'label' => app::get('syspromotion')->_('所属供应商id'),
            'comment' => app::get('syspromotion')->_('所属供应商id'),
        ),
        'type' => array (
            'type' =>
                array (
                    0 => app::get('syspromotion')->_('实体卡'),
                    1 => app::get('syspromotion')->_('虚拟卡'),
                ),
            'default' => 0,
            'required' => true,
            'label' => app::get('syspromotion')->_('卡类型'),
            'width' => 40,
            'in_list' => true,
            'default_in_list' => true,
        ),
        'coupon_name' => array(
            'type' => 'string',
            'required' => true,
            'in_list' => true,
            'default_in_list' => true,
            'width' => 110,
            'label' => app::get('syspromotion')->_('折扣卡名称'),
            'comment' => app::get('syspromotion')->_('折扣卡名称'),
        ),
        'coupon_desc' => array(
            'type' => 'string',
            'required' => true,
            'in_list' => true,
            'width' => 110,
            'label' => app::get('syspromotion')->_('折扣卡描述'),
            'comment' => app::get('syspromotion')->_('折扣卡描述'),
        ),
        'used_platform' => array(
            'type' => array(
                '0' => app::get('syspromotion')->_('所有平台可用'),
                '1' => app::get('syspromotion')->_('只能用于pc'),
                '2' => app::get('syspromotion')->_('只能用于wap'),
                '3' => app::get('syspromotion')->_('只能用于app'),
            ),
            'default' => 0,
            'required' => true,
            'in_list' => true,
            'default_in_list' => true,
            'label' => app::get('syspromotion')->_('使用平台'),
            'comment' => app::get('syspromotion')->_('使用平台'),
        ),
        'valid_grade' => array(
            'type' => 'string',
            'default' => '',
            'required' => true,
            'label' => app::get('syspromotion')->_('会员级别集合'),
        ),
        'limit_money' => array(  //0            1000
            'type' => 'string',
            'default' => '0',
            'in_list' => true,
            'default_in_list' => true,
            'width' => '50',
            'order' => 14,
            'label' => app::get('syspromotion')->_('满足条件金额'),
            'comment' => app::get('syspromotion')->_('满足条件金额'),
        ),
        'deduct_money' => array(  //免单     0.50     100元
            'type' => 'string',
            'default' => '免单',
            'in_list' => true,
            'default_in_list' => true,
            'width' => '50',
            'order' => 14,
            'label' => app::get('syspromotion')->_('使用折扣'),
            'comment' => app::get('syspromotion')->_('使用折扣'),
        ),
        'use_bound' => array(
            'type' => array(
                '0' => app::get('syspromotion')->_('商家全场可用'),
                '1' => app::get('syspromotion')->_('指定商品可用'),
                '2' => app::get('syspromotion')->_('开店可用'),
                '3' => app::get('syspromotion')->_('演唱会可用'),
            ),
            'default' => '0',
            'in_list' => true,
            'default_in_list' => true,
            'width' => '50',
            'order' => 14,
            'label' => app::get('syspromotion')->_('使用范围'),
            'comment' => app::get('syspromotion')->_('使用范围'),
        ),
        'created_time' => array(
            'type' => 'time',
            'in_list' => true,
            'default_in_list' => true,
            'width' => '100',
            'label' => app::get('syspromotion')->_('创建时间'),
            'comment' => app::get('syspromotion')->_('创建时间'),
        ),
        'promotion_tag' => array(
            'type' => 'string',
            'length' => 15,
            'required' => true,
            'label' => app::get('syspromotion')->_('促销标签'),
            'comment' => app::get('syspromotion')->_('促销标签'),
        ),
        'coupon_status' => array(
            'type' => array(
                'pending' => app::get('syspromotion')->_('待审核'),
                'agree' => app::get('syspromotion')->_('审核通过'),
                'refuse' => app::get('syspromotion')->_('审核拒绝'),
                'cancel' => app::get('syspromotion')->_('已取消'),
            ),
            'default' => 'agree',
            'required' => true,
            'in_list' => true,
            'default_in_list' => true,
            'width' => 110,
            'label' => app::get('syspromotion')->_('折扣卡状态'),
            'comment' => app::get('syspromotion')->_('折扣卡状态'),
        ),
    ),
    
    'primary' => 'coupon_id',
    'comment' => app::get('syspromotion')->_('恒大微购折扣卡'),
);
