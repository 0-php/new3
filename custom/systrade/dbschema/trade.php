<?php
return  array(
    'columns' => array(
        'tid' => array(
            //'type' => 'bigint unsigned',
            'type' => 'bigint',
            'unsigned' => true,
            'required' => true,
            //'pkey' => true,
            'in_list' => true,
            'is_title' => true,
            'searchtype' => 'has',
            'filtertype' => 'custom',
            'filterdefault' => true,
            'default_in_list' => true,
            'label' => app::get('systrade')->_('订单号'),
            'width' => '100',
            'order' => 10,
        ),
        'shop_id' => array(
            'type' => 'table:shop@sysshop',
            'required' => true,
            'in_list' => true,
            'default_in_list' => true,
            'label' => app::get('systrade')->_('所属商家'),
            'comment' => app::get('systrade')->_('订单所属的店铺id'),
            'width' => 100,
            'order' => 11,
        ),
        'user_id' => array(
            'type' => 'table:account@sysuser',
            'required' => true,
            'in_list' => true,
            'default_in_list' => true,
            'label' => app::get('systrade')->_('会员用户名'),
            'comment' => app::get('systrade')->_('会员id'),
            'width' => 100,
            'order' => 12,
        ),
        'dlytmpl_id' => array(
            'type' => 'table:dlytmpl@syslogistics',
            'required' => true,
            'in_list' => true,
            'default_in_list' => true,
            'label' => app::get('systrade')->_('配送模板id'),
            'comment' => app::get('systrade')->_('配送模板id'),
            'width' => 100,
            'order' => 12,
        ),
        'ziti_addr' => array(
            'type' => 'string',
            'in_list' => true,
            'default_in_list' => true,
            'label' => app::get('systrade')->_('自提地址'),
            'comment' => app::get('systrade')->_('自提地址'),
        ),
        'status' => array(
            'type' => array(
                'WAIT_BUYER_PAY' => '已下单等待付款',
                'WAIT_SELLER_SEND_GOODS' => '已付款等待发货',
                'WAIT_BUYER_CONFIRM_GOODS' => '已发货等待确认收货',
                'TRADE_FINISHED' => '已完成',
                'TRADE_CLOSED' => '已关闭(退款关闭订单)',
                'TRADE_CLOSED_BY_SYSTEM' => '已关闭(卖家或买家主动关闭)'
            ),
            'required' => true,
            'in_list' => true,
            'default_in_list' => true,
            'width' => '100',
            'order' => 13,
            'label' => app::get('systrade')->_('订单状态'),
            'comment' => app::get('systrade')->_('订单状态'),
        ),
        'cancel_reason' => array(
            'type' => 'text',
            'in_list' => true,
            'default_in_list' => true,
            'width' => '100',
            'order' => 13,
            'label' => app::get('systrade')->_('取消订单原因'),
        ),
        'pay_type' => array(
            'type' => array(
                'online' => '线上付款',
                'offline' => '线下付款',
            ),
            'default'=>'online',
            'required' => true,
            'width' => '100',
            'label' => app::get('systrade')->_('支付类型'),
            'comment' => app::get('systrade')->_('支付类型'),
        ),
        'payment' => array(
            'type' => 'money',
            'default' => '0',
            'in_list' => true,
            'default_in_list' => true,
            'width' => '50',
            'order' => 14,
            'label' => app::get('systrade')->_('实付金额'),
            'comment' => app::get('systrade')->_('实付金额,订单最终总额'),
        ),
        'total_fee' => array(
            'type' => 'money',
            'default' => '0',
            'label' => '商品总额',
            'comment' => app::get('systrade')->_('各子订单中商品price * num的和，不包括任何优惠信息'),
        ),
        'post_fee' => array(
            'type' => 'money',
            'comment' => app::get('systrade')->_('邮费'),
        ),
        'payed_fee' => array (
            'type' => 'money',
            'default' => '0',
            'editable' => false,
            'comment' => app::get('systrade')->_('已支付金额'),
        ),
        'seller_rate' => array(
            'type' => 'bool',
            /*
            'type' => array(
                'true' => '已评价',
                'false' => '未评价',
            ),
            */
            'default' => 0,
            'in_list' => true,
            'default_in_list' => false,
            'comment' => app::get('systrade')->_('卖家是否评价'),
            'label' => app::get('systrade')->_('卖家是否评价'),
        ),
        'receiver_name' => array(
            //'type' => 'varchar(20)',
            'type' => 'string',
            'length' => 50,

            'in_list' => true,
            'default_in_list' => true,
            'searchtype' => 'has',
            'filtertype' => 'custom',
            'filterdefault' => true,
            'width' => '100',
            'order' => 15,
            'label' => app::get('systrade')->_('收货人姓名'),
            'comment' => app::get('systrade')->_('收货人姓名'),
        ),
        'created_time' => array(
            'type' => 'time',
            'in_list' => true,
            'default_in_list' => true,
            'width' => '100',
            'order' => 17,
            'label' => app::get('systrade')->_('订单创建时间'),
            'comment' => app::get('systrade')->_('订单创建时间'),
        ),
        'pay_time' => array(
            'type' => 'time',
            'comment' => app::get('systrade')->_('付款时间'),
        ),
        'consign_time' => array(
            'type' => 'time',
            'in_list' => true,
            'default_in_list' => false,
            'comment' => app::get('systrade')->_('卖家发货时间'),
            'label' => app::get('systrade')->_('卖家发货时间'),
        ),
        'end_time' => array(
            'type' => 'time',
            'in_list' => true,
            'default_in_list' => true,
            'label' => app::get('systrade')->_('结束时间'),
            'comment' => app::get('systrade')->_('结束时间'),
        ),
        'modified_time' => array(
            'type' => 'last_modify',
            'in_list' => true,
            'default_in_list' => true,
            'width' => '100',
            'order' => 18,
            'label' => app::get('systrade')->_('修改时间'),
            'comment' => app::get('systrade')->_('修改时间'),
        ),
        'timeout_action_time' => array(
            'type' => 'time',
            'in_list' => true,
            'default_in_list' => false,
            'label' => app::get('systrade')->_('超时到期时间'),
            'comment' => app::get('systrade')->_('超时到期时间'),
        ),
        'send_time' => array(
            'type' => 'time',
            'comment' => app::get('systrade')->_('订单将在此时间前发出，主要用于预售订单'),
        ),
        'receiver_state' => array(
            //'type' => 'varchar(20)',
            'type' => 'string',
            'length' => 20,
            'comment' => app::get('systrade')->_('收货人所在省份'),
        ),
        'receiver_city' => array(
            //'type' => 'varchar(20)',
            'type' => 'string',
            'length' => 20,
            'comment' => app::get('systrade')->_('收货人所在城市'),
        ),
        'receiver_district' => array(
            //'type' => 'varchar(20)',
            'type' => 'string',
            'length' => 20,
            'comment' => app::get('systrade')->_('收货人所在地区'),
        ),
        'receiver_address' => array(
            //'type' => 'varchar(200)',
            'type' => 'string',
            'length' => 200,
            'in_list' => true,
            'default_in_list' => true,
            'width' => '150',
            'order' => 16,
            'label' => app::get('systrade')->_('收货人详细地址'),
            'comment' => app::get('systrade')->_('收货人详细地址'),
        ),
        'receiver_zip' => array(
            //'type' => 'varchar(20)',
            'type' => 'string',
            'length' => 20,
            'comment' => app::get('systrade')->_('收货人邮编'),
        ),
        'receiver_mobile' => array(
            //'type' => 'varchar(20)',
            'type' => 'string',
            'length' => 20,
            'comment' => app::get('systrade')->_('收货人手机号'),
        ),
        'receiver_phone' => array(
            //'type' => 'varchar(20)',
            'type' => 'string',
            'length' => 20,
            'comment' => app::get('systrade')->_('收货人电话'),
        ),
        'title' => array(
            //'type' => 'varchar(50)',
            'type' => 'string',
            'length' => 50,
            'in_list' => true,
            'default_in_list' => false,
            'label' => app::get('systrade')->_('交易标题'),
            'comment' => app::get('systrade')->_('交易标题'),
        ),
        'discount_fee' => array(
            'type' => 'money',
            'default' => '0',
            'comment' => app::get('systrade')->_('促销优惠金额'),
        ),
        'hdvip_fee' => array(
            'type' => 'money',
            'default' => '0',
            'comment' => app::get('systrade')->_('折扣卡优惠金额'),
        ),
        'consume_point_fee' => array(
            'type' => 'number',
            'default' => '0',
            'comment' => app::get('systrade')->_('买家使用积分'),
        ),
        'buyer_message' => array(
            //'type' => 'varchar(255)',
            'type' => 'string',
            'comment' => app::get('systrade')->_('买家留言'),
        ),
        'need_invoice' => array(
            'type' => 'bool',
            'default' => 0,
            'comment' => app::get('systrade')->_('是否需要开票'),
        ),
        'invoice_name' => array(
            //'type' => 'varchar(20)',
            'type' => 'string',
            'length' => 100,
            'comment' => app::get('systrade')->_('发票抬头'),
        ),
        'invoice_type' => array(
            //'type' => 'varchar(20)',
            'type' => 'string',
            'length' => 20,
            'comment' => app::get('systrade')->_('发票类型'),
        ),
        'invoice_main' => array(
            //'type' => 'varchar(20)',
            'type' => 'string',
            'length' => 20,
            'comment' => app::get('systrade')->_('发票内容'),
        ),
        'adjust_fee' => array(
            'type' => 'money',
            'default' => '0',
            'comment' => app::get('systrade')->_('卖家手工调整金额,子订单调整金额之和'),
        ),
        'trade_from' => array(
            'type' => array(
                'pc' =>app::get('systrade')->_('标准平台'),
                'wap' => app::get('systrade')->_('手机触屏'),
                'weixin' => app::get('systrade')->_('微信商城'),
            ),
            'default' => 'pc',
            'comment' => app::get('systrade')->_('订单来源'),
        ),
        'itemnum' => array(
            'type' => 'number',
            'default' => '0',
            'comment' => app::get('systrade')->_('子订单商品购买数量总数'),
        ),
        'shop_flag' => array(
            //'type' => 'varchar(20)',
            'type' => 'string',
            'length' => 20,
            'comment' => app::get('systrade')->_('卖家备注旗帜'),
        ),
        'shop_memo' => array(
            'type' => 'text',
            'comment' => app::get('systrade')->_('卖家备注'),
        ),
        'buyer_area' => array(
            //'type' => 'varchar(30)',
            'type' => 'string',
            'deny_export' => true,
            'length' => 30,
            'comment' => app::get('systrade')->_('买家下单的地区'),
        ),
        'area_id' => array(
            'type' => 'number',
            'deny_export' => true,
            'comment' => app::get('systrade')->_('区域id，代表订单下单的区位码'),
        ),
        'step_trade_status' => array(
            'type' => array(
                '0'=>'定金和尾款都付',
                '1' => '定金已付尾款未付',
                '2' => '定金未付尾款未付',
            ),
            'comment' => app::get('systrade')->_('分阶段付款的订单状态'),
        ),
        'step_paid_fee' => array(
            'type' => 'money',
            'comment' => app::get('systrade')->_('分阶段付款的已付金额'),
        ),
        'shipping_type' => array(
            'type' => array(
                'free' => '卖家包邮',
                'post' => '平邮',
                'express' => '快递',
                'ems' => 'EMS',
                'virtual' => '虚拟发货',
            ),
            'comment' => app::get('systrade')->_('创建交易时的物流方式'),
        ),
        'obtain_point_fee' => array(
            'type' => 'number',
            'comment' => app::get('systrade')->_('买家获得积分,返点的积分'),
        ),
        'trade_memo' => array(
            'type' => 'text',
            'comment' => app::get('systrade')->_('交易备注'),
        ),
        'buyer_rate' => array(
            'type' => 'bool',
            'default' => 0,
            'comment' => app::get('systrade')->_('买家是否已评价'),
        ),
        'is_part_consign' => array(
            'type' => 'bool',
            'default' => 0,
            'comment' => app::get('systrade')->_('是否是多次发货的订单'),
        ),
        'real_point_fee' => array(
            'type' => 'number',
            'comment' => app::get('systrade')->_('买家实际使用积分'),
        ),
        'ip' => array(
            //'type' => 'varchar(15)',
            'type' => 'string',
            'length' => 15,
            'editable' => false,
            'comment' => app::get('systrade')->_('IP地址'),
            'label' => app::get('systrade')->_('IP地址'),
            'in_list' => true,
        ),
        'anony' => array(
            'type' => 'bool',//1 匿名， 0 默认实名
            'default' => 0,
            'comment' => app::get('systrade')->_('下单选择的是否匿名，子订单将匿名修改该字段不修改，只表示下单的选择'),
        ),
        'is_clearing' => array(
            'type' => 'bool',
            /*
            'type' => array(
                'true' => '已生成结算单',
                'false' => '未生成结算单',
            ),
            */
            'default' => 0,
            'in_list' => true,
            'default_in_list' => false,
            'comment' => app::get('systrade')->_('是否生成结算单'),
            'label' => app::get('systrade')->_('是否生成结算单'),
        ),
        'disabled' => array(
            'type' => 'bool',
            'default' => 0,
            'required' => true,
            'editable' => false,
            'comment' => app::get('systrade')->_('是否有效'),
        ),
    ),
    'primary' => 'tid',
    'comment' => app::get('systrade')->_('订单主表'),
);
