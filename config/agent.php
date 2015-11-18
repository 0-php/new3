<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2012 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 *
 * 代理商菜单定义
 */

return array(
    /*
    |--------------------------------------------------------------------------
    | 代理商之首页
    |--------------------------------------------------------------------------
     */
    'index' => array(
        'label' => '首页',
        'display' => true,
        'shopIndex' => true,
        'action' => 'agent_ctl_index@index',
        'icon' => 'glyphicon glyphicon-home',
        'menu' => array(
            array('label'=>'首页','display'=>false,'action'=>'agent_ctl_index@index','url'=>'/','method'=>'get'),
             )
    ),

  

    'member' => array(
        'label' => '代理商会员管理',
        'display' => true,
        'action' => 'agent_ctl_member_list@index',
        'icon' => 'glyphicon glyphicon-list-alt',
        'menu' => array(
            array('label'=>'会员管理','display'=>true,'action'=>'agent_ctl_member_list@index','url'=>'sysstat/sysstat.html','method'=>'get'),
      		
            )
    ),

        'distributorMember' => array(
        'label' => '分销商会员管理',
        'display' => true,
        'action' => 'agent_ctl_member_distributorList@index',
        'icon' => 'glyphicon glyphicon-list-alt',
        'menu' => array(
            array('label'=>'分销商会员统计','display'=>true,'action'=>'agent_ctl_member_distributorList@index','url'=>'sysstat/sysstat.html','method'=>'get'),
            
            )
    ),

    'agentMessage' => array(
        'label' => '代理商信息',
        'display' => true,
        'action' => 'agent_ctl_mes@index',
        'icon' => 'glyphicon glyphicon-list-alt',
        'menu' => array(
            array('label'=>'代理商信息','display'=>true,'action'=>'agent_ctl_mes@index','url'=>'agent/agentMes.html','method'=>'get'),
            )
    ),




 /*   'agentMessage' => array(
        'label' => '代理商信息',
        'display' => true,
        'action' => 'agent_ctl_mes@index',
        'icon' => 'glyphicon glyphicon-list-alt',
        'menu' => array(
            array('label'=>'代理商信息','display'=>true,'action'=>'agent_ctl_mes@index','url'=>'agent/agentMes.html','method'=>'get'),
            )
    ),*/

     'reportForm' => array(
        'label' => '报表',
        'display' => true,
        'action' => 'agent_ctl_orderIncome@index',
        'icon' => 'glyphicon glyphicon-list-alt',
        'menu' => array(
            array('label'=>'订单收入','display'=>true,'action'=>'agent_ctl_orderIncome@index','url'=>'agent/orderIncome.html','method'=>'get'),
            array('label'=>'公众店铺收入','display'=>true,'action'=>'agent_ctl_publicIncome@index','url'=>'agent/publicIncome.html','method'=>'get'),
            array('label'=>'演出会门票收入','display'=>true,'action'=>'agent_ctl_ticketIncome@index','url'=>'agent/ticketIncome.html','method'=>'get'),
               )
    ),


 


  
);
