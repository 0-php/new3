<?php
	
	class agent_controller extends base_routing_controller 
	{
		/**
     	* 页面不需要menu
     	*/
   		public $nomenu = false;

		public function __construct($app)
		{
			pamAccount::setAuthType('agent');
			$this->app = $app;
			$this->agentId = pamAccount::getAccountId();
		/*	pd($this->agentId);*/
			$this->agentName = pamAccount::getLoginName();
		/*	pd($this->agentId);*/
			//$this->Id = app::get('agent')->rpcCall('shop.get.loginId',array('agent_id'=>$this->agentId),'agent');
			 $action = route::current()->getActionName();
      		  $actionArr = explode('@',$action);
      		 // pd($actionArr['0']);
       		 if( $actionArr['0'] != 'agent_ctl_passport' )
       		 {
         		   if( !$this->agentId )
           			{
                		redirect::action('agent_ctl_passport@signin')->send();exit;
            		}
        	}
		}

		public function page($view, $pagedata = array())
		{
			$sellerData = agentAuth::getSellerData();
			$topshopPageParams['seller'] = $sellerData;
			$topshopPageParams['path'] = $this->runtimePath;//设置面包屑
			if( $this->contentHeaderTitle )
        	{
            	$topshopPageParams['contentTitle'] = $this->contentHeaderTitle;
        	}
        	//当前页面调用的action
        	$topshopPageParams['currentActionName']= route::current()->getActionName();


        	$menuArr = $this->__getMenu();
        	
        	if( $menuArr && !$this->nomenu )
       		{
          	  	$topshopPageParams['navbar'] = $menuArr['navbar'];
            	$topshopPageParams['sidebar'] = $menuArr['sidebar'];
        	}

        	$topshopPageParams['view'] = $view;

        	$pagedata['topshop'] = $topshopPageParams;
        	//pd($pagedata);

        	$pagedata['icon'] =  kernel::base_url(1).'/public/statics/favicon.ico';

        	  if( !$this->tmplName )
        	  {
         	   	 	$this->tmplName = 'agent/tmpl/page.html';
        	  }
        	//  pd($this->tmplName);

        	return view::make($this->tmplName, $pagedata);



		}

		private function __getMenu()
		{
			 $defaultActionName = route::current()->getActionName();
       		 $shopMenu = config::get('agent');
       		 $commonUserMenu = $this->__commonUserMenu();

       		 $sidebar['commonUser']['label'] = '常用菜单';
        	 $sidebar['commonUser']['active'] = true; //是否展开
        	 $sidebar['commonUser']['icon'] = 'glyphicon glyphicon-heart';
        	 $sidebar['commonUser']['menu'] = $commonUserMenu;

        foreach( (array)$shopMenu as $menu => $row )
        {
            if( $row['display'] === false ) continue;

            $navbar[$menu]['label'] = $row['label'];
            $navbar[$menu]['icon'] = $row['icon'];
            $navbar[$menu]['action'] = $row['action'];

            foreach( (array)$row['menu'] as $k=>$params )
            {
                if( $params['action'] == $defaultActionName )
                {
                    $navbar[$menu]['default'] = true;
                }
                if( $shopIndex )
                {
                    $shopIndex = true;
                    $sidebar[$menu] = $navbar[$menu];
                    $sidebar[$menu]['active'] = false; //是否展开
                    $sidebar[$menu]['menu'] = $row['menu'];
                    continue;
                }
                if( $row['shopIndex'] && $params['action'] == $defaultActionName )
                {
                    $shopIndex = $row['shopIndex'];
                    continue;
                }

                if( $params['action'] == $defaultActionName )
                {
                    $sidebar[$menu]['label'] = $row['label'];
                    $row['menu'][$k]['default'] = true;
                    $sidebar[$menu]['active'] = true; //是否展开
                    $sidebar[$menu]['icon'] = $row['icon'];
                    $sidebar[$menu]['menu'] = $row['menu'];
                }
            }
        }

        $res['navbar'] = $navbar;
        $res['sidebar'] = $sidebar;

        return $res;
		}

		 private function __commonUserMenu()
   		{
        	return [
            	array('label'=>'会员管理','display'=>true,'action'=>'agent_ctl_member_list@index'),
        	];
    	}


		public function set_tmpl($tmpl)
		{
			 $tmplName = 'agent/tmpl/'.$tmpl.'.html';
        	$this->tmplName = $tmplName;
		}

        public function splash($status='success',$url=null,$msg=null,$ajax=true){
        $status = ($status == 'failed') ? 'error' : $status;
        //如果需要返回则ajax
        if($ajax==true||request::ajax()){
            return response::json(array(
                $status => true,
                'message'=>$msg,
                'redirect' => $url,
            ));
        }

        if($url && !$msg){//如果有url地址但是没有信息输出则直接跳转
            return redirect::to($url);
        }
    }


	}

?>