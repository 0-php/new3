<?php
/**
 * 恒大微购
 * Ric
 * 20150512
 */
return array(
    'columns'=> array(
        'user_id' =>array (
            'type' => 'table:account@sysuser', // 字段类型
            'required' => true,                // 不能为空 默认为false
            'label' => app::get('topd')->_('会员id'),
            'comment' => app::get('topd')->_('会员'),
            'in_list' => true,
            'default_in_list' => true,
            'order' => 0,
        ),
        'fenxiao_time' =>array (
            'type'=>'time',
            'in_list'=>true,
            'default_in_list'=>true,
            'filtertype' => false,
            'filterdefault' => 'true',
            'label' => app::get('topd')->_('申请时间'),
            'comment' => app::get('topd')->_('申请时间'),
            'order' => 1,
        ),
        'fenxiao_name'=>array(
            'type' => 'string',
            'length' => 100,
            'default' => '',
            'in_list' => true,
            'default_in_list' => true,
            'is_title' => true,
            'searchtype' => 'has',  //是否可搜索
            'required' => true,
            'label' => app::get('topd')->_('真实姓名'),
            'comment' => app::get('topd')->_('真实姓名'),
            'order' => 2,
        ),
        'fenxiao_dpname'=>array(
                'type' => 'string',
                'length' => 50,
                'in_list'=>true,
                'default_in_list'=>true,
                'default' => 'VIP店铺',
                'is_title' => true,
                'searchtype' => 'has',  //是否可搜索
                'filtertype' => false,
                'filterdefault' => 'true',
                'label' => app::get('topd')->_('店铺名称'),
                'comment' => app::get('topd')->_('店铺名称'),
                'order' => 3,
        ),
        'fenxiao_type'=>array(
            'type'=>array(
                'student'=>'在校学生',
                'Sociology'=>'社会人士',
            ),
            'in_list'=>true,
            'default_in_list'=>true,
            'filtertype' => false,
            'filterdefault' => 'true',
            'required' => true,
            'searchtype' => 'has',  //是否可搜索
            'label' => app::get('topd')->_('店铺类型'),
            'comment' => app::get('topd')->_('店铺类型'),
            'order' => 4,
        ),
        'fenxiao_shengid'=>array(
            'type' => 'string',
            'length' => 50,
            'label' => app::get('topd')->_('地址'),
            'default' => 0,
            'in_list' => true,
            'required' => true,
            'default_in_list' => true,
            'order' => 5,
        ),
        'fenxiao_cid'=>array(
            'type' => 'string',
            'length' => 18,
            'fixed' => true,
            'in_list' => true,
            'default_in_list' => true,
            'filtertype' => false,
            'filterdefault' => 'true',
            'searchtype' => 'has',  //是否可搜索
            'label' => app::get('topd')->_('身份证号码'),
            'comment' => app::get('topd')->_('身份证号码'),
            'order' => 6,
        ),
        'fenxiao_descript'=>array(
            'type'=>'text',
            'in_list'=>true,
            'default_in_list'=>true,
            'is_title' => true,
            'filtertype' => false,
            'filterdefault' => 'true',
            'label' => app::get('topd')->_('店铺描述'),
            'comment' => app::get('topd')->_('店铺描述'),
            'order' => 7,
        ),
        'fenxiao_simg'=>array(
            'type' => 'string',
            'length' => 100,
            'label' => app::get('topd')->_('店铺logo'),
            'comment' => app::get('topd')->_('提交logo'),
            'in_list'=>true,
            'default_in_list'=>false,
            'order' => 10,
        ),
        'fenxiao_cidimg' =>array (
            'type' => 'string',
            'length' => 10,
            'label' => app::get('topd')->_('店铺模板'),
            'default' => '',
        ),
        'fenxiao_call' =>array (
            'type' => 'string',
            'length' => 50,
            'in_list'=>true,
            'default_in_list'=>true,
            'filtertype' => false,
            'filterdefault' => 'true',
            'searchtype' => 'has',  //是否可搜索
            'label' => app::get('topd')->_('手机号'),
            'comment' => app::get('topd')->_('手机号'),
            'order' => 13,
        ),
        'bank_user_name'=>array(
            'type' => 'string',
            'length' => 50,
            'filtertype' => false,
            'filterdefault' => 'true',
            'label' => app::get('topd')->_('开户银行'),
            'comment' => app::get('topd')->_('开户银行'),
        ),
        'bank_name'=>array(
            'type' => 'string',
            'length' => 50,
            'filtertype' => false,
            'filterdefault' => 'true',
            'label' => app::get('topd')->_('支行名称'),
            'comment' => app::get('topd')->_('支行名称'),
        ),
        'fenxiao_yhk'=>array(
            'type' => 'string',
            'length' => 50,
            'filtertype' => false,
            'filterdefault' => 'true',
            'label' => app::get('topd')->_('银行账号'),
            'comment' => app::get('topd')->_('银行账号'),
        ),
        'fenxiao_zt' =>array (
            'default' => 'active',
            'type'=>array(
                'active'=>'未支付',
                'successful'=>'待审核',
                'failing'=>'审核驳回',
                'finish'=>'审核通过',
                'stop'=>'暂停使用',
            ),
            'in_list'=>true,
            'default_in_list'=>true,
            'label' => app::get('topd')->_('申请状态'),
            'comment' => app::get('topd')->_('申请状态'),
            'order' => 5,
        ),
        'fenxiao_clk' =>array (
            'type' => 'string',
            'length' => 50,
            'label' => app::get('topd')->_('客户点击数'),
            'default' => '1432046906|0|0',
        ),
        'fenxiao_tuijuan' =>array (
            'type' => 'table:account@sysuser',
            'label' => app::get('topd')->_('推荐人'),
            'comment' => app::get('topd')->_('推荐人'),
        ),
        'fenxiao_tuijuanzf' =>array (
            'type' => 'money',
            'label' => app::get('topd')->_('推荐提成支付'),
            'comment' => app::get('topd')->_('推荐提成支付'),
        ),
        'fenxiao_paytime' =>array (
            'type' => 'time',
            'label' => app::get('topd')->_('支付时间'),
            'default' => 0,
            'width' => 75,
            'in_list' => true,
            'default_in_list' => true,
        ),
        'fenxiao_payprice' =>array (
            'type' => 'money',
            'default' => '0',
            'in_list' => true,
            'default_in_list' => true,
            'searchtype' => 'has',  //是否可搜索
            'label' => app::get('topd')->_('支付金额'),
            'comment' => app::get('topd')->_('需要支付的金额'),
        ),
        'payment_id' => array (
            'type' => 'string',
            'length' => 20,
            'default' => '',
            'searchtype' => 'has',  //是否可搜索
            'label' => app::get('topd')->_('支付单号'),
        ),
        'payment_app_id' => array (
            'type' => 'text',
            'default' => '',
            'label' => app::get('topd')->_('第三方返回支付记录'),
        ),
        'payment_pay_id' => array (
            'type' => 'text',
            'default' => '',
            'label' => app::get('topd')->_('商城支付记录'),
        ),
    ),
    'primary' => 'user_id',
    'comment' => app::get('topd')->_('个人店铺店铺表'),
);

