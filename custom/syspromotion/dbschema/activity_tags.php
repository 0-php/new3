<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */

return array(
    'columns' => array(
        'type_id' => array(
            'type' => 'number',
            'required' => true,
            'editable' => false,
            'autoincrement' => true,
            'in_list' => false,
            'label' => app::get('syspromotion')->_('类型id'),
            'comment' => app::get('syspromotion')->_('类型id'),
        ),
        'name' => array(
            'type' => 'string',
            'length' => 50,
            'required' => true,
            'default' => '',
            'in_list' => true,
            'default_in_list' => true,
            'filterdefault' => true,
            'is_title' => true,
            'label' => app::get('syspromotion')->_('类型名称'),
            'comment' => app::get('syspromotion')->_('类型名称'),
        ),
        'bydefault' => array(
            'type' => 'bool',
            'default' => 0,
            'default_in_list' => true,
            'in_list' => true,
            'label' => app::get('syspromotion')->_('是否系统默认'),
            'comment' => app::get('syspromotion')->_('是否系统默认'),
        ),
    ),
    'primary' => 'type_id',
);
