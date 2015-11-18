<?php

class agent_ctl_admin_agent extends desktop_controller
{
	function index()
	{
		return $this->finder(
			'agent_mdl_account',
			array(
				'title' => app::get('agent')->_('代理商列表'),
                'use_buildin_delete'=>false, //隐藏桌面的删除按钮
                'use_buildin_tagedit' => true,
                'use_buildin_filter'=> true,
                'use_buildin_refresh' => true,
                'actions' => array(
                	array(
                		'label' => app::get('agent')->_('添加代理商'),
                		'href' => '?app=agent&ctl=admin_agent&act=add',
                		'target' => 'dialog::{title:\'' . app::get('agent')->_('添加代理商') . '\',width:680,height:450}'
                		),
                    //重构删除按钮
                	array(
                		'label' => app::get('agent')->_('删除'),
                		//'href' => '?app=agent&ctl=admin_agent&act=delete',
                        'submit' => '?app=agent&ctl=admin_agent&act=delete',
                        'confirm'=>app::get('system')->_('确定要删除代理商信息?'),
                        ),
                	),
                )
			);
	}

	public function agent_grade()
	{
		$postData =input::get();
		$agent_grade = $postData['agent_grade'];
		return response::json($agent_grade);
	}

    //获取所选代理区域， 用于判断该区域是否已被代理
	public function area_submit()
	{
		$postdata = input::get(); 
		$arr =explode( "=",$postdata['code']);

         $code = $arr[0];//获取所选的区域编码
         $grade = $arr[1];//获取所选的级别
         $filter = "agent_area like '%{$code}%' limit 0,1";
            $pagedata= app::get('agent')->database()->createQueryBuilder()->from('agent_account')->select('agent_id')->where( $filter)->execute()->fetch();
           if($pagedata['agent_id'] == "")
           {
            //1代表可以代理
              $promise = "1";
          }else if($pagedata['agent_id'] != "")
          {
            //0表示该地区已经被人代理，不能再代理
              $promise = "0";
          }
       /*  if($grade == "省")
         {
         	//如果省代理只绑定省，则需要判断，该省下面是否有市或有县，已被别的省代理所绑定，如果是，则不能代理该省，而是需要代理具体的市或绑定特定的县
         	if(substr($code, 2,4)=="0000")
         	{

         	}else if(substr($code, 4,2)=="00")//如果省代理绑定了市，则需要判断，该市所在的省是否已被代理，如果是，则不能代理，还需要判断，该市下，是否有特定县，已被代理，如果是，则需要具体绑定到县，除了这两种情况，还需要判断，该市是否已经被代理，如果是，则不能代理
         	{

         	}else if(substr($code, 4,2)!= "00")//如果省代理绑定了县，则需要判断该县所在的市，是否已被代理，如果是，则不能再代理该县的兄弟县，还需要判断，该县是否已被代理，如果是，则不能代理该县，而是需要，绑定其它县
         	{
                 $parentId = substr($code, 0,4);
                 $filter = "agent_area like '%{$parentId}%' limit 0,1";
                 $pagedata= app::get('agent')->database()->createQueryBuilder()->from('agent_account')->select('agent_id')->where( $filter)->execute()->fetch();
                 if($pagedata['agent_id'] == "")
                 {
                    $filter = "agent_area like '%{$code}%' limit 0,1";
                   /**
                   下面语句  还需要绑定一个条件   ,array('agent_grade'=>$grade)
                   
                   $pagedata= app::get('agent')->database()->createQueryBuilder()->from('agent_account')->select('agent_id')->where( $filter)->execute()->fetch();
                   if($pagedata['agent_id'] == "")
                   {
                      //1代表可以代理
                    $promise = "1";
                }else if($pagedata['agent_id'] != "")
                {
                     //5表示，是所选绑定的县，已被代理，请选择别的县进行绑定
                    $promise = "5";
                }
            }else if($pagedata['agent_id'] != "")
            {
                  //0表示该地区已经被人代理，不能再代理
                    $promise = "4";//4代表，您所绑定地区的所有县，已被代理，请选择，别的市，或别的市下的县代理
                }
            }
        }else if($grade=="市")
        {

          if(substr($code, 4,2)=="00")
          {*/
              /*
                //如果是县
                  $filter = "agent_area like '%{$code}%' limit 0,1";
                    $pagedata= app::get('agent')->database()->createQueryBuilder()->from('agent_account')->select('agent_id')->where( $filter)->execute()->fetch();
                    if($pagedata['agent_id'] == "")
                     {
                     //1代表可以代理
                     $promise = "1";
                     }else if($pagedata['agent_id'] != "")
                    {
                    //0表示该地区已经被人代理，不能再代理
                     $promise = "0";
                    }*/
               //如果是市区域,而没有绑定特级县，则需要查询，该市下，是否有县已被代理，如果是，则不能代理整个市，而是需要代理该市下的具体的县
               /*  $code =substr($code, 0,4);
                $filter = "agent_area like '%{$code}%' limit 0,1";
         	  	   
					   //,array('agent_grade'=>$grade)  下面语句还需要绑定一个条件
         		     
                   $pagedata= app::get('agent')->database()->createQueryBuilder()->from('agent_account')->select('agent_id')->where( $filter)->execute()->fetch();
                   if($pagedata['agent_id'] != "")
                   {
                	$promise = "3";//3，表示该市下面有的县已被别的市代理商所绑定，所以市代理不能代理整个市。而是要具体选择该市下面的特定县
                }else if($pagedata['agent_id'] == "") {

               		 $promise = "1";//1表示可以代理该区域
                      }
                  }else if(substr($code, 4,2) != "00")
                  {
                    //如果市代理绑定的是县，则，需要查询该县是否已被代理，如果是，则该县不能被代理
                      $filter = "agent_area like '%{$code}%' limit 0,1";
                      $pagedata= app::get('agent')->database()->createQueryBuilder()->from('agent_account')->select('agent_id')->where( $filter)->execute()->fetch();
                      if($pagedata['agent_id'] == "")
                      {
                    //1代表可以代理
                        $promise = "1";
                    }else if($pagedata['agent_id'] != "")
                    {
                    //0表示该地区已经被人代理，不能再代理
                        $promise = "0";
                    }*/
              
              /* $parentId = substr($code, 0,4);
               $filter = "agent_area like '%{$parentId}%' limit 0,1";
               $pagedata= app::get('agent')->database()->createQueryBuilder()->from('agent_account')->select('agent_id')->where( $filter)->execute()->fetch();
               if($pagedata['agent_id'] == "")
        		{
                	$filter = "agent_area like '%{$code}%' limit 0,1";
			       
			       //下面句  还需要绑定一个条件   ,array('agent_grade'=>$grade)
			       
			       $pagedata= app::get('agent')->database()->createQueryBuilder()->from('agent_account')->select('agent_id')->where( $filter)->execute()->fetch();
			       if($pagedata['agent_id'] == "")
			       {
                      //1代表可以代理
			       	$promise = "1";
			       }else if($pagedata['agent_id'] != "")
			       {
                     //5表示，是所选绑定的县，已被代理，请选择别的县进行绑定
			       	$promise = "5";
			       }
        		}else if($pagedata['agent_id'] != "")
        		{
                  //0表示该地区已经被人代理，不能再代理
        			$promise = "4";//4代表，您所绑定地区的所有县，已被代理，请选择，别的市，或别的市下的县代理
        		}*/
       /* 		
            }
        }else if($grade == "县")
        {
             //如果是县
           $filter = "agent_area like '%{$code}%' limit 0,1";
           $pagedata= app::get('agent')->database()->createQueryBuilder()->from('agent_account')->select('agent_id')->where( $filter)->execute()->fetch();
           if($pagedata['agent_id'] == "")
           {
            //1代表可以代理
              $promise = "1";
          }else if($pagedata['agent_id'] != "")
          {
            //0表示该地区已经被人代理，不能再代理
              $promise = "0";
          }

      }*/

        /* if(substr($code, 2,4)=="0000")
         {

         }else if(substr($code, 4,2)=="00")
         {
            //如果是市区域
            $code =substr($code, 0,4);
            $filter = "agent_area like '%{$code}%' limit 0,1";
            $pagedata= app::get('agent')->database()->createQueryBuilder()->from('agent_account')->select('agent_id')->where( $filter)->execute()->fetch();
            if($pagedata['agent_id'] != "")
            {
                $promise = "3";//3，表示该市下面有县已被别的市代理商所绑定，所以市代理不能代理整个市。而是要具体选择该市下面的特定县
            }else if($pagedata['agent_id'] == "")
            {
                $promise = "0";//0表示可以代理该区域
            }
         }else
         {
            //如果是县
            $filter = "agent_area like '%{$code}%' limit 0,1";
            $pagedata= app::get('agent')->database()->createQueryBuilder()->from('agent_account')->select('agent_id')->where( $filter)->execute()->fetch();
            if($pagedata['agent_id'] == "")
            {
                //1代表可以代理
                $promise = "1";
            }else if($pagedata['agent_id'] != "")
            {
                //0表示该地区已经被人代理，不能再代理
                $promise = "0";
            }
        }*/
        

        
        return response::json($promise);
    }

    public function edit()
    {

    	header("cache-control:no-store,no-cache,must-revalidate");
        $postdata= input::get();
        $agent_id = $postdata['agent'];
        $oaccount =  $this->app->model('account');
        $oagentmsg = $this->app->model('agentmsg');
        $pagedata['oaccount'] = $oaccount->getRow('*',array('agent_id'=>$agent_id));
        $pagedata['oagentmsg'] = $oagentmsg->getRow('*', array('agent_id'=>$agent_id));
        // $row = $oItem->getList('*',array('agent_id'=>$agent_id),0,1);
        //$this->pagedata['item'] = $row[0];
        $arr = unserialize($pagedata['oaccount']['agent_area']);
        //$pagedata['oaccount']['agent_area'] = implode(',', $arr['area']);
        foreach ($arr['area'] as $key => $value) {

            $pagedata['area'][$arr['code'][$key]]=$value;

        }
        $pagedata['corpcode'] = $this->_corpCode();
        $pagedata['oagentmsg']['agentmsg_register_time']= date("Y-m-d",$pagedata['oagentmsg']['agentmsg_register_time']);
        return $this->page('agent/admin/agentedit.html',$pagedata);

    } 


    /*修改密码*/
    public function editPwd()
    {


    	$postData = input::get();


    	$data['agentid']=$postData['agent'];

    	return $this->page('agent/admin/editPwd.html', $data);
    }

    public   function  savePwd()
    {
    	$postdata = utils::_filter_input(input::get('seller'));


    	$objSeller = kernel::single('agent_data_seller');
    	try {
    		$objSeller->savePwd($postdata);

    	} catch (Exception $e) {
    		$msg = $e->getMessage();
    		return $this->splash('error', null, $msg);
    	}
    	return $this->splash('success', null, "密码已成功修改成功");        
    }




    public function checkUsername()
    {
    	$postData =input::get();
    	$userName = $postData['userName'];

    	$oaccount =  $this->app->model('account');
    	$pagedata['oaccount'] = $oaccount->getRow('*',array('agent_username'=>$userName));
    	if( $pagedata['oaccount'])
    	{
    		$result = "用户名已被注册";

    	}else
    	{
    		$result = "允许注册"; 

    	}
    	return response::json($result);
    }

    public function saveagent()
    {
    	$postdata = utils::_filter_input(input::get('seller'));
    	foreach ($postdata['agent_area'] as $key => $value) {

    		$arr['code'][] = substr($value,0,6);
    		$arr['area'][] = substr($value,6);
    	}
    	$new =serialize($arr);
    	$postdata['agentmsg_register_time'] =  strtotime($postdata['agentmsg_register_time']);
    	$postdata['agent_area']= $new;
    	$objSeller = kernel::single('agent_data_seller');
    	try {
            //$this->adminlog("添加自营用户[{$postdata['agent_username']}]", 1);
    		$objSeller->saveSelf($postdata);
    	} catch (Exception $e) {
            //$this->adminlog("添加自营用户[{$postdata['agent_username']}]", 0);
    		$msg = $e->getMessage();
    		return $this->splash('error', null, $msg);
    	}
    	return $this->splash('success', null, "删除成功");

    }
    
    public function modifyAgent()
    {
        $postData=input::get();


        foreach ($postData["seller"]['agent_area'] as $key => $value) {

            $area['code'][] = substr($value,0,6);
            $area['area'][] = substr($value,6);
        }
        $new =serialize($area);

       //接收数据
        $account['agent_name'] = trim($postData["seller"]["agent_name"]);

        $account['agent_grade'] = $postData["seller"]["agent_grade"];
        $account['agent_area'] =$new;
        $account['agent_id'] = $postData["seller"]["agent_id"];

        $agent_msg['agent_id'] = $postData["seller"]["agent_id"];
        $agent_msg['agentmsg_name'] = $postData["seller"]["agentmsg_name"];
        $agent_msg['agentmsg_address'] = $postData["seller"]["agentmsg_address"];
        $agent_msg['agentmsg_mobile'] = $postData["seller"]["agentmsg_mobile"];
        $agent_msg['agentmsg_email'] = $postData["seller"]["agentmsg_email"];
        $agent_msg['agentmsg_register_time'] = strtotime($postData["seller"]["agentmsg_register_time"]);



        $accountl = app::get('agent')->model('account');
        $agentmsg1 = app::get('agent')->model('agentmsg');


        try {

           $sellerId = $accountl->save($account);
           $sellerId2 =$agentmsg1->save($agent_msg);
       } catch (Exception $e) {
            //$this->adminlog("添加自营用户[{$postdata['agent_username']}]", 0);
        $msg = $e->getMessage();
        return $this->splash('error', null, $msg);
    }
    return $this->splash('success', null, "修改成功");


}
    //根据市，返回下一级县
public  function city()
{
   $postData =input::get();

   $cityName = $postData['cityName'];
   $reCountry = kernel::single('agent_data_area')->getCountryById($cityName);
   if($reCountry == "")
   {
      $reCountry['cityId'] = $cityName;
      $reCountry['cityName'] = kernel::single('agent_data_area')->getAreaNameById($cityName);
      return response::json($reCountry);
  }
  else
  {
      $reCountry['cityId'] = "";
      $reCountry['cityName'] = kernel::single('agent_data_area')->getAreaNameById($cityName);
      return response::json($reCountry);
  }


}

    //根据县   返回select的值
public function country()
{
   $postData =input::get();
   $countryName = $postData['countryName'];
   $reCountry['countryId'] = $postData['countryName'];
   $reCountry['countryName'] = kernel::single('agent_data_area')->getAreaNameById($countryName);
   return response::json();
}


    //根据省  返回下一级市/区
public function province()
{

         /*$expirse = $_POST;
        $key = key($_POST);
        $value = current($_POST);
        app::get('site')->setConf($key.'.cache_expires', (int)$value);*/
        //$pagedata['corpcode'] = $this->_corpCode();
        $postData =input::get();

        $provinceName = $postData['provinceName'];

        //$postData = $_POST;

        //$postData = $pagedata['corpcode'][''] ;

        $reCity = kernel::single('agent_data_area')->getCityById($provinceName);

        $reCity['provinceName'] = kernel::single('agent_data_area')->getAreaNameById($provinceName);

        return response::json($reCity);



      //第二种ajax返回的方法
       /* $default = array(
                $status=>$msg?$msg:app::get('desktop')->_('操作成功'),
                $method=>'',
        );
        //$params = array('121','21212');
        $params = $_POST;
        $arr = array_merge($default, $params ,array('splash'=>true));
        return response::json($arr)->send();
*/
    }

    // function Country_change(obj)
    //         {
    //             new Request({
    //                 url: "?app=agent&ctl=admin_agent&act=country",
    //                 update:this.value,
    //                 onComplete:function(rs){
    //                     rs = JSON.decode(rs);
    //                     var Country_txt;

    //                   $('hid_agent_country').value =  Country_txt;
    //      }
    //  }).send('cityName=' + obj.value);
    //         }






    //将地区信息输入到select选项中
    public function add()
    {
    	$pagedata['corpcode'] = $this->_corpCode();
       // $area = app::get('agent')->rpcCall('logistics.area',array('area'=>'110100'));

       /* $pagedata['corpcode'] = kernel::single('agent_data_area')->getAreaNameByIdqq('110100');
       pr($pagedata['corpcode']);*/
       return $this->page('agent/admin/edit.html', $pagedata);
   }

   public function delete()
   {

    $postdata = input::get();
    $arr = $postdata['agent_id'];
    $inCondition="'".implode("','", $arr)."'";

    try {
        $re1= app::get('agent')->database()->executeQuery("DELETE  FROM    `agent_account` where   `agent_id`  in(".$inCondition.") ");
        $re2= app::get('agent')->database()->executeQuery("DELETE  FROM    `agent_agentmsg` where   `agent_id`  in(".$inCondition.") ");
    } catch (Exception $e) {
        $msg = $e->getMessage();
        return $this->splash('error', null, $msg);
    }
    return $this->splash('success', null, "添加成功");


}
private function _corpCode()
{
    $objDlycorp = kernel::single('agent_data_area');
    $corpcode = $objDlycorp->__getAreaFileContents();
    return $corpcode;
}
}