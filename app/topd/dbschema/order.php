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
        'oid' =>array (
            'type' => 'table:order@systrade', // 字段类型
            'required' => true,                // 不能为空 默认为false
            'label' => app::get('topd')->_('子订单号'),
            'comment' => app::get('topd')->_('子订单号'),
            'in_list' => true,
            'default_in_list' => true,
            'order' => 0,
        ),
        'cost_price' =>array (
            'type' => 'money',
            'default' => '0',
            'comment' => app::get('topd')->_('成本价'),
        ),


    ),
    'primary' => ['user_id', 'oid'],
    'comment' => app::get('topd')->_('公众店铺订单表'),
);

