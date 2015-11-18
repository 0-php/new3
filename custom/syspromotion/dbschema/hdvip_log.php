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
        'log_id' => array(
            'type' => 'number',
            'required' => true,
            'autoincrement' => true,
            'width' => 110,
            'label' => app::get('syspromotion')->_('id'),
            'comment' => app::get('syspromotion')->_('获取记录id'),
        ),
        'card_id' =>array (
            'type' => 'table:hdvip_ncard@syspromotion', // 字段类型
            'required' => true,                // 不能为空 默认为false
            'label' => app::get('topd')->_('虚拟卡'),
            'comment' => app::get('topd')->_('虚拟卡'),
            'in_list' => true,
            'default_in_list' => true,
            'order' => 0,
        ),
        'time' => array(
            'type' => 'time',
            'in_list' => true,
            'default_in_list' => true,
            'width' => '100',
            'label' => app::get('syspromotion')->_('获取时间'),
            'comment' => app::get('syspromotion')->_('获取时间'),
        ),
        'money' => array(
            'type' => 'money',
            'default' => '0',
            'in_list' => true,
            'default_in_list' => true,
            'width' => '50',
            'order' => 14,
            'label' => app::get('syspromotion')->_('金额'),
            'comment' => app::get('syspromotion')->_('金额'),
        ),
        'msg'=>array(
            'type' => 'string',
            'length' => 100,
            'is_title'=>true,
            'comment' => app::get('syspromotion')->_('备注'),
        ),
        'author' => array(
            'type' => 'string',
            'length' => 60,
            'label' => app::get('syspromotion')->_('操作人'),
            'width' => '40',
            'in_list' => true,
            'default_in_list' => true,
            'comment' => app::get('syspromotion')->_('操作人'),
            'filtertype' => 'yes',
            'filterdefault' => true,
            'order' =>'3',
        ),
    ),
    'primary' => 'log_id',
    'comment' => app::get('syspromotion')->_('虚拟折扣卡获取记录'),
);
