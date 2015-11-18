<?php
	/**
	*代理商信息表
	*/
	return  array(
    'columns' => array(
        'agent_id' =>
        array (
            'type' => 'table:account@agent',
            //'pkey' => true,
            'comment' => app::get('agent')->_('代理商id'),
            'width' => 110,
        
        ),
         'agentmsg_address'=>array(
            'type'=>'string',
            'length'=>50,
            // 'required' => true,
            'label' => '代理商所在地址',
            'comment' => app::get('agent')->_('代理商地址'),
        ),
        'agentmsg_name' => array(
            //'type'=>'varchar(50)',
            'type' => 'string',
            'length' => 50,
            'comment' => app::get('agent')->_('代理商姓名'),
            'required' => true,
            'order' => 40,
            'in_list' => true,
            'default_in_list'=>true,
        ),
        'agentmsg_mobile' => array(
            //'type'=>'varchar(20)',
            'type' => 'string',
            'length' => 20,
            'comment' => app::get('agent')->_('代理商手机号'),
            'required' => true,
            'order' => 50,
            'in_list' => true,
            'default_in_list'=>true,
        ),
        'agentmsg_email' => array(
            //'type'=>'varchar(100)',
            'type' => 'string',
            'length' => 100,
            'comment' => app::get('agent')->_('代理商邮箱'),
            'required' => true,
            'is_title' => true,
            'order' => 60,
            'in_list' => true,
            'default_in_list'=>true,
        ),
        'agentmsg_last_modify' => array (
            'type' => 'last_modify',
            'comment' => app::get('syscategory')->_('最后修改时间'),
        ),
        'agentmsg_register_time' => array (
            'type' => 'time',
            'comment' => app::get('agent')->_('注册时间'),
        ),
        'agentmsg_login_time' => array (
            'type' => 'time',
            'comment' => app::get('agent')->_('最后登录时间'),
        ),
    ),
    
    'primary' => 'agent_id',
    'comment' => app::get('agent')->_('商家账号信息'),
);


?>