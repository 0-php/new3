<?php
     /*
     *代理商注册信息表
     */
    return  array(
        'columns' => array(
            /*代理商ID*/
            'agent_id' => array(
                'type' => 'number',
                'required' => true,
                'autoincrement' => true,
                 'comment' => app::get('agent')->_('代理商ID'),
                //'pkey' => true,
            ),
            /*代理商用户名*/
            'agent_username' => array(
                'type' => 'string',
                'length' => 20,
                'in_list'=>true,
                'is_title'=>true,
                'default_in_list'=>true,
                'label'=>'用户名',
                'comment' => app::get('agent')->_('用户名'),
                'filtertype'=>true,
                'searchtype'=>true,
                'searchtype' => 'has',
                'order' => 2,
            ),
            /*代理商名称*/
            'agent_name' => array(
                'type' => 'string',
                'length' => 50,
                'in_list'=>true,
                'default_in_list' => true,
                'label' => '代理商名称',
                'comment' => app::get('agent')->_('代理商名称'),
                'order' => 3,
            ),
            /*代理商密码*/
            'agent_pwd' => array(
                'type' => 'string',
                'length' => 60,
                'comment' => app::get('agent')->_('代理商密码'),
            ),
              /*代理级别*/
            'agent_grade' => array(
                'type' => 'string',
                'length' => 6,
                'in_list'=>true,
                'default_in_list' => true,
                'label' => '代理商级别',
                'comment' => app::get('agent')->_('代理商级别,省,市，县'),
                'order' => 5,
            ),
            /*代理区域*/
            'agent_area' => array(
                'in_list'=>true,
                'default_in_list' => true,
                'label' => '代理区域',
                'comment' => app::get('agent')->_('代理区域'),
                'type' => 'region',
                'order' => 6,
            ),
        ),
        'primary' => 'agent_id',
    );


?>