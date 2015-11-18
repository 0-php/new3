<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */
return  array(
    'columns'=>
    array(
        'notice_id'=> array(
            'type' => 'number',
            'required' => true,
            'autoincrement' => true,
            'width' => 50,
            'order'=>1,
            'comment' => app::get('topd')->_('公众店铺通知ID'),
            'label' => app::get('topd')->_('公众店铺通知ID'),
        ),
        'notice_title'=> array(
            'type' => 'string',
            'searchtype' => 'has',
            'filtertype' => 'normal',
            'filterdefault' => 'true',
            'required' => true,
            'in_list' => true,
            'default_in_list' => true,
            'order'=>2,
            'comment' => app::get('topd')->_('公众店铺通知标题'),
            'label' => app::get('topd')->_('公众店铺通知标题'),
        ),
        'notice_content'=> array(
            'type' => 'text',
            'required' => true,
            'comment' => app::get('topd')->_('公众店铺通知内容'),
            'order'=>3,
        ),
        'notice_type'=> array(
            'type' => 'string',
            'searchtype' => 'has',
            'filtertype' => 'normal',
            'filterdefault' => 'true',
            'required' => true,
            'in_list' => true,
            'default_in_list' => true,
            'order'=>4,
            'comment' => app::get('topd')->_('公众店铺通知类型'),
            'label' => app::get('topd')->_('公众店铺通知类型'),
        ),
        'user_id' =>array (
            'type' => 'number',
            'default' => 0,
            'label' => app::get('topd')->_('公众店铺id'),
        ),
        'admin_id'=> array(
            'type' => 'number',
            'required' => false,
            'label' => app::get('topd')->_('操作者的id'),
            'order'=>6,
        ),
        'createtime'=> array(
            'type' => 'time',
            'comment' => app::get('topd')->_('公众店铺通知创建时间'),
            'label' => app::get('topd')->_('公众店铺创建时间'),
            'editable' => false,
            'width' => 130,
            'in_list' => true,
            'default_in_list' => true,
            'order'=>7,
        ),
        'modified_time' => array (
            'type' => 'time',
            'comment' => app::get('topd')->_('公众店铺最后修改时间'),
            'label' => app::get('topd')->_('公众店铺最后修改时间'),
            'editable' => false,
            'width' => 130,
            'in_list' => true,
            'default_in_list' => true,
            'order'=>8,
        ),
    ),
    'primary' => 'notice_id',
    'comment' => app::get('topd')->_('公众店铺通知表'),
);
