<?php

/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */

return array(
    'columns' => array(
        'id' => array(
            'type' => 'number',
            'required' => true,
            'autoincrement' => true,
        ),
        'special_id' => array(
            'type' => 'number',
            'label' => app::get('syspromotion')->_('规则id'),
            'comment' => app::get('syspromotion')->_('规则id'),
        ),
        'item_id' => array(
            'type' => 'number',
            'label' => app::get('syspromotion')->_('货品id'),
            'comment' => app::get('syspromotion')->_('货品id'),
        ),
        'type_id' => array(
            'type' => 'number',
            'label' => app::get('syspromotion')->_('促销类型id'),
            'comment' => app::get('syspromotion')->_('促销类型id'),
        ),
        'promotion_price' => array(
            'type' => 'money',
            'label' => app::get('syspromotion')->_('促销价格'),
            'comment' => app::get('syspromotion')->_('促销价格'),
        ),
        'release_time' => array(
            'type' => 'time',
            'default' => 0,
            'editable' => true,
            'filterdefault' => true,
            'label' => app::get('syspromotion')->_('发布时间'),
            'comment' => app::get('syspromotion')->_('发布时间'),
        ),
        'begin_time' => array(
            'type' => 'time',
            'default' => 0,
            'editable' => true,
            'filterdefault' => true,
            'label' => app::get('syspromotion')->_('开始时间'),
            'comment' => app::get('syspromotion')->_('开始时间'),
        ),
        'end_time' => array(
            'type' => 'time',
            'default' => 0,
            'editable' => true,
            'filterdefault' => true,
            'label' => app::get('syspromotion')->_('结束时间'),
            'comment' => app::get('syspromotion')->_('结束时间'),
        ),
        'limit' => array(
            'type' => 'number',
            'label' => app::get('syspromotion')->_('限购数量'),
            'comment' => app::get('syspromotion')->_('限购数量'),
        ),
        'remind_way' => array(
            'type' => 'serialize',
            'label' => app::get('syspromotion')->_('提醒方式'),
            'comment' => app::get('syspromotion')->_('提醒方式'),
        ),
        'remind_time' => array(
            'type' => 'number',
            'label' => app::get('syspromotion')->_('提前提醒时间'),
            'comment' => app::get('syspromotion')->_('提前提醒时间'),
        ),
        'timeout' => array(
            'type' => 'number',
            'label' => app::get('syspromotion')->_('超时时间'),
            'comment' => app::get('syspromotion')->_('超时时间'),
        ),
        'cdown' => array(
            'type' => 'bool',
            'label' => app::get('syspromotion')->_('是否显示倒计时'),
            'comment' => app::get('syspromotion')->_('是显示否倒计时'),
        ),
        'initial_num' => array(
            'type' => 'number',
            'comment' => app::get('syspromotion')->_('初始销售量'),
        ),
        'status' => array(
            'type' => 'bool',
            'default' => 0,
            'label' => app::get('syspromotion')->_('状态'),
            'comment' => app::get('syspromotion')->_('状态'),
        ),
        'description' => array(
            'type' => 'text',
            'required' => false,
            'default' => '',
            'editable' => false,
            'label' => app::get('syspromotion')->_('规则描述'),
            'comment' => app::get('syspromotion')->_('规则描述'),
        ),
    ),
    'primary' => 'id',
);
