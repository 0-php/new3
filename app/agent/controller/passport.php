<?php
	class agent_ctl_passport extends agent_controller
	{
		/*显示登录页面*/
		public function signin()
		{
			$this->contentHeaderTitle = app::get('agent')->_('代理商账号登录');
			$this->set_tmpl('passport'); 
			return $this->page('agent/passport/signin.html');
			//return view::make('agent/passport/signin.html');
		}

		/*代理商注销帐号*/
		public function logout()
		{
			pamAccount::logout();
			return redirect::action('agent_ctl_passport@signin');	
		}


		/*代理商密码修改*/
		public function update()
		{
			return view::make('agent/passport/update.html');
		}
		public function updatepwd()
		{
			try
        	{
            	agentAuth::modifyPwd(input::get());
        	}
        	catch(Exception $e)
        	{
           		$msg = $e->getMessage();
            	return $this->splash('error',null,$msg,true);
        	}

        	$url = url::action('agent_ctl_passport@signin');
        	$msg = app::get('agent')->_('修改成功,请重新登陆');
        	pamAccount::logout();

        	return $this->splash('success',$url,$msg,true);
    
		}

		/*代理商登录*/
		public function login()
		{

			try
			{
				agentAuth::login(input::get('agent_username'), input::get('agent_pwd'));
			}
			catch(Exception $e)
			{
				$msg = $e->getMessage();
			}

			if( pamAccount::check() )
       		{
            	if( input::get('remember_me') )
            	{
                	setcookie('LOGINNAME',trim(input::get('agent_username')),time()+31536000,kernel::base_url().'/');
            	}

            	$url = url::action('agent_ctl_index@index');
            	$msg = app::get('agent')->_('登录成功');
            	return $this->splash('success',$url,$msg,true);
       		}
        	else
        	{
        	
            	return $this->splash('error',$url,$msg,true);
        	}
		}
	}
?>