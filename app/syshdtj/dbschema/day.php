<?php
return  array(
    'columns'=>array(
        'day_time' => array (
            'type' => 'time',
            'in_list' => true,
            'default_in_list' => true,
            'filtertype' => 'time',
            'filterdefault' => true,
            'label' => app::get('syshdtj')->_('日结时间'),
            'comment' => app::get('syshdtj')->_('日结时间'),
            'width' => 50,
        ),
        'countnum' => array(
            'type' => 'number',
            'label' => app::get('syshdtj')->_('总订单'),
            'comment' => app::get('syshdtj')->_('总订单'),
            'in_list' => true,
            'default_in_list' => true,
            'unsigned' => true,
            'required' => true,
            'width' => 50,
        ),
        'shopnum' => array(
            'type' => 'number',
            'label' => app::get('syshdtj')->_('产品单'),
            'comment' => app::get('syshdtj')->_('产品单'),
            'in_list' => true,
            'default_in_list' => true,
            'unsigned' => true,
            'required' => true,
            'width' => 50,
        ),
        'topdnum' => array(
            'type' => 'number',
            'label' => app::get('syshdtj')->_('店铺单'),
            'comment' => app::get('syshdtj')->_('店铺单'),
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
        'countprice' => array(
            'type' => 'money',
            'required' => true,
            'default' => '0',
            'label' => app::get('syshdtj')->_('总收入'),
            'comment' => app::get('syshdtj')->_('总收入'),
            'in_list' => true,
            'default_in_list' => true,
            'width' => 50,
        ),
        'shopprice' => array(
            'type' => 'money',
            'required' => true,
            'default' => '0',
            'label' => app::get('syshdtj')->_('产品收入'),
            'comment' => app::get('syshdtj')->_('产品收入'),
            'in_list' => true,
            'default_in_list' => true,
            'width' => 50,
        ),
        'topdprice' => array(
            'type' => 'money',
            'required' => true,
            'default' => '0',
            'label' => app::get('syshdtj')->_('开店收入'),
            'comment' => app::get('syshdtj')->_('开店收入'),
            'in_list' => true,
            'default_in_list' => true,
            'width' => 50,
        ),
        'concertprice' => array(
            'type' => 'money',
            'required' => true,
            'default' => '0',
            'label' => app::get('syshdtj')->_('演唱会收入'),
            'comment' => app::get('syshdtj')->_('演唱会收入'),
            'in_list' => true,
            'default_in_list' => true,
            'width' => 50,
        ),
        'gprice' => array(
            'type' => 'money',
            'required' => true,
            'default' => '0',
            'label' => app::get('syshdtj')->_('供应商'),
            'comment' => app::get('syshdtj')->_('供应商'),
            'in_list' => true,
            'default_in_list' => true,
            'width' => 50,
        ),
        'tprice' => array(
            'type' => 'money',
            'required' => true,
            'default' => '0',
            'label' => app::get('syshdtj')->_('分销商'),
            'comment' => app::get('syshdtj')->_('分销商'),
            'in_list' => true,
            'default_in_list' => true,
            'width' => 50,
        ),
        'gxprice' => array(
            'type' => 'money',
            'required' => true,
            'default' => '0',
            'label' => app::get('syshdtj')->_('供所在县'),
            'comment' => app::get('syshdtj')->_('供所在县'),
            'in_list' => true,
            'default_in_list' => true,
            'width' => 50,
        ),
        'xzsprice' => array(
            'type' => 'money',
            'required' => true,
            'default' => '0',
            'label' => app::get('syshdtj')->_('行政省'),
            'comment' => app::get('syshdtj')->_('行政省'),
            'in_list' => true,
            'default_in_list' => true,
            'width' => 50,
        ),
        'xzshiprice' => array(
            'type' => 'money',
            'required' => true,
            'default' => '0',
            'label' => app::get('syshdtj')->_('行政市'),
            'comment' => app::get('syshdtj')->_('行政市'),
            'in_list' => true,
            'default_in_list' => true,
            'width' => 50,
        ),
        'xzxprice' => array(
            'type' => 'money',
            'required' => true,
            'default' => '0',
            'label' => app::get('syshdtj')->_('行政县'),
            'comment' => app::get('syshdtj')->_('行政县'),
            'in_list' => true,
            'default_in_list' => true,
            'width' => 50,
        ),
        'hdvgprice' => array(
            'type' => 'money',
            'required' => true,
            'default' => '0',
            'label' => app::get('syshdtj')->_('恒大微购'),
            'comment' => app::get('syshdtj')->_('恒大微购'),
            'in_list' => true,
            'default_in_list' => true,
            'width' => 50,
        ),

    ),
    'primary' => 'day_time',
    'comment' => app::get('syshdtj')->_('提成日结'),
);
