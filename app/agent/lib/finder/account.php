<?php
	class agent_finder_account
	{	
    	public  $column_edit = '信息详情';
    	public function column_edit(&$colList, $list)
    	{
        	foreach($list as $k=>$row)
        	{
             
            	//$colList[$k] = '<a href="?app=agent&ctl=admin_agent&act=edit&agent_id='.$row['agent_id'].'">查看</a>|<a href="?app=agent&ctl=admin_agent&act=editPwd&agent_id='.$row['agent_id'].'">修改密码</a>';
               $url = '?app=agent&ctl=admin_agent&act=edit&agent='.$row['agent_id'];
               $urlPwd = '?app=agent&ctl=admin_agent&act=editPwd&agent='.$row['agent_id'];
               $targetPwd='dialog::  {title:\''.app::get('agent')->_('修改密码').'\', width:500, height:400}';
                $target = 'dialog::  {title:\''.app::get('agent')->_('查看').'\', width:500, height:400}';
                $title = app::get('agent')->_('编辑');
                $titlePwd = app::get('agent')->_('修改密码');
                $button = '<a href="' . $url . '" target="' . $target . '">' . $title . '</a>'.'|<a href="' .$urlPwd. '" targent="' .$targetPwd . '">' .$titlePwd . '</a>';

                $colList[$k] = $button;
            }
        
    	}

     


/*
    	public $detail_editt = '详细列表';
   		public function detail_editt($agent_id)
   		{
        	$render = app::get('agent')->render();
        	$oItem = kernel::single("agent_mdl_account");
        	$items = $oItem->getList('*',array('agent_id' => $agent_id), 0, 1);
        	$render->pagedata['item'] = $items[0];
       		return $render->display('agent/admin/itemdetail.html');
    	}*/
	}

?>