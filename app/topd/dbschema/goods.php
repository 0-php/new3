<?php
/**
 * 恒大微购
 * Ric
 * 20150512
 */
return array(
    'columns'=>array(
        'user_id' =>array (
            'type' => 'table:account@sysuser', // 字段类型
            'required' => true,                // 不能为空 默认为false
            'label' => app::get('topd')->_('会员id'),
            'comment' => app::get('topd')->_('会员'),
            'in_list' => true,
            'default_in_list' => true,
            'order' => 0,
        ),
        'item_id' => array(
            'type' => 'table:item@sysitem',
            'required' => true,
            'comment' => app::get('topd')->_('商品id'),
        ),
        'goods_zt' => array(
            'type' => array(
                'onsale' => app::get('topd')->_('上架'),
                'instock' => app::get('topd')->_('下架'),
            ),
            'required' => true,
            'default' => 'onsale',
            'label' => app::get('topd')->_('店内商品状态'),
            'comment' => app::get('topd')->_('店内商品状态'),
            'in_list' => true,
            'default_in_list' => false,
            'filtertype' => 'yes',
            'filterdefault' => true,
            'order'=>1,
        ),
    ),
    'primary' => ['user_id', 'item_id'],
    'comment' => app::get('topd')->_('公众店铺商品表'),
);

