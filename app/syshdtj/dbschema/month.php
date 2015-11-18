<?php
return  array(
    'columns'=>array(
        'month_id' => array(
            'type' => 'number',
            'autoincrement' => true,
            'required' => true,
            'comment' => app::get('syshdtj')->_('月报表编号'),
        ),
        'month_time' => array (
            'type' => 'time',
            'in_list' => true,
            'default_in_list' => true,
            'editable' => false,
            'filtertype' => 'time',
            'filterdefault' => true,
            'label' => app::get('syshdtj')->_('月结时间'),
            'comment' => app::get('syshdtj')->_('月结时间'),
            'width' => 50,
        ),
        'type' => array (
            'type' => array (
                '供应商' => app::get('syshdtj')->_('供应商'),
                '公众店铺' => app::get('syshdtj')->_('公众店铺'),
                '供应商所在县' => app::get('syshdtj')->_('供应商所在县'),
                '行政省' => app::get('syshdtj')->_('行政省'),
                '行政市' => app::get('syshdtj')->_('行政市'),
                '行政县' => app::get('syshdtj')->_('行政县'),
                '恒大微购' => app::get('syshdtj')->_('恒大微购'),
            ),
            'required' => true,
            'label' => app::get('syshdtj')->_('角色类型'),
            'comment' => app::get('syshdtj')->_('角色类型'),
            'width' => 50,
            'editable' => false,
            'in_list' => true,
            'default_in_list' => true,
        ),
        'type_id'=>array(
            'type'=>'number',
            'required' => true,
            'default' => '0',
            'comment' => app::get('sysshop')->_('角色ID'),
        ),
        'shopnum' => array(
            'type' => 'number',
            'label' => app::get('syshdtj')->_('产品单'),
            'comment' => app::get('syshdtj')->_('产品单'),
            'in_list' => true,
            'default' => '0',
            'default_in_list' => true,
            'unsigned' => true,
            'width' => 50,
        ),
        'topdnum' => array(
            'type' => 'number',
            'label' => app::get('syshdtj')->_('店铺单'),
            'comment' => app::get('syshdtj')->_('店铺单'),
            'in_list' => true,
            'default' => '0',
            'default_in_list' => true,
            'unsigned' => true,
            'width' => 50,
        ),
        'concertnum' => array(
            'type' => 'number',
            'label' => app::get('syshdtj')->_('演唱会'),
            'comment' => app::get('syshdtj')->_('演唱会'),
            'in_list' => true,
            'default' => '0',
            'default_in_list' => true,
            'unsigned' => true,
            'width' => 50,
        ),
        'shopprice' => array(
            'type' => 'money',
            'default' => '0',
            'label' => app::get('syshdtj')->_('产品收入'),
            'comment' => app::get('syshdtj')->_('产品收入'),
            'in_list' => true,
            'default_in_list' => true,
            'width' => 50,
        ),
        'topdprice' => array(
            'type' => 'money',
            'default' => '0',
            'label' => app::get('syshdtj')->_('开店收入'),
            'comment' => app::get('syshdtj')->_('开店收入'),
            'in_list' => true,
            'default_in_list' => true,
            'width' => 50,
        ),
        'concertprice' => array(
            'type' => 'money',
            'default' => '0',
            'label' => app::get('syshdtj')->_('演唱会收入'),
            'comment' => app::get('syshdtj')->_('演唱会收入'),
            'in_list' => true,
            'default_in_list' => true,
            'width' => 50,
        ),
    ),
    'primary' => 'month_id',
    'comment' => app::get('syshdtj')->_('月结报表'),
);
