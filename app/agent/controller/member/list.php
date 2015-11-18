<?php
class agent_ctl_member_list extends agent_controller
{
	
			//会员管理主页
	public function index()
	{
	 	//设置页面限定长度
		$limit = 10;

		$filter = input::get();
		//获取搜索昵称

		/*获取代理商区域，级别*/
				$agent_id = pamAccount::getAccountId();//获取代理商ID
			 	$agent_area = app::get('agent')->model('account')->getRow('agent_area,agent_grade', array('agent_id'=>$agent_id));//根据登录代理商的ID，查询其代理区域
			 	$agent_grade=$agent_area["agent_grade"];
			 	$data =unserialize($agent_area['agent_area']);


			 	/*----------处理分页查询相关数据Begin----*/		
			 	$filter['limit'] = $limit;
			 	$filter['start'] = empty($filter['pages']) ?  0 : $limit * (intval($filter['pages'])-1);
			 	$current = empty($filter['pages']) ? 1: $filter['pages'];
			 	unset($filter['pages']);
			 	/*---------//处理分页查询相关数据End------*/


			 	/*----------------昵称搜素Begin------------------------------------*/

			 	/*----------------------昵称搜素End--------------------------------*/






			 	/*----------------------返回第一级select框Begin------------------*/
	 	    $data1 = array();//此此变量用于存放下一级select值
	 	    if($agent_grade == "省")
	 	    {
	 	    	foreach ($data['code'] as $key => $value) {
	 	    		$val =substr($value,0,4);
	 					//如果是直辖区域，则特殊处理
	 	    		if($val=="1101" || $val == "1201" || $val=="3101" || $val=="5001")
	 	    		{
	 	    			$code = $val."00";
	 	    		}else
	 	    		{
	 	    			$code = substr($value,0,2)."0000";
	 	    		}

	 	    		if(!array_key_exists($code,$data1))
	 	    		{
	          			  	$strName = kernel::single('agent_data_area')->getAreaNameById($code);//返回对应代码的名称
	          			  	$data1[$code] = $strName;
	          			  }
	          			}
	          		}
	          		if($agent_grade == "市")
	          		{
	          			foreach ($data['code'] as $key => $value) {
	          				$code = substr($value, 0,4)."00";
	          				if(!array_key_exists($code,$data1))
	          				{
	          			  	$strName = kernel::single('agent_data_area')->getAreaNameById($code);//返回对应代码的名称
	          			  	$data1[$code] = $strName;
	          			  }
	          			}
	          		}
	          		if($agent_grade == "县")
	          		{
	          			foreach ($data['code'] as $key => $value) {
	          				$data1[$value]=$data['area'][$key];
	          			}
	          		}
	          		$pagedata['areaName'] =  $data1;
	          		/*----------------------返回第一级select框End------------------*/


	          		/*分页*/
	 			  $totalPage = ceil($total/$filter['limit']);//返回分页页数
	 			  $filter['pages'] = time();
	 			  $pagers = array(
	 			  	'link'=>url::action('agent_ctl_member_list@index',$filter),
            		'current'=>$current,//当前起始条数
            		'use_app' => 'agent',
            		'total'=>$totalPage,
            		'token'=>time(),
            		);
	 			  $pagedata['pagers'] = $pagers; 


	 			  return $this->page('agent/member/list.html',$pagedata);
	 			}


			//根据区域筛选会员信息
			//第一级select框   第二级select框
	 			public function searchArea()
	 			{
	 				$postData = input::get();
	 				$selArea = $postData['selArea'];
	 				$agent_id = pamAccount::getAccountId();
	 	 			//根据登录代理商的ID，查询其代理区域
	 				$agent_area = app::get('agent')->model('account')->getRow('agent_area', array('agent_id'=>$agent_id));
	 				$data =unserialize($agent_area['agent_area']);


	 				if($selArea==""|| substr($selArea, 4,2)!="00")
	 				{
		 				$pagedata['next']="";//当next为空，则表示没有下一级
		 			}else
		 			{
		 				$data2=array();

						$pagedata['next']="1";//当next为1，则表示有下一级
						/*--------------4个直辖区域，特殊处理----Begin------------------*/
						$val = substr($selArea,0,4);
						if($val=="1101" ||  $val=="1201"  || $val=="3101" || $val=="5001")
						{
							foreach ($data['code'] as $key => $value) {
								if(substr($value,4,2)!="00" && substr($value, 0,4)==$val)
								{
									if(!array_key_exists($value,$data2))
									{
										$strNameTwo = kernel::single('agent_data_area')->getAreaNameById($value);
										$data2[$value] = $strNameTwo;
									}
								}	
							}
						}
						/*--------------4个直辖区域，特殊处理----Begin------------------*/

						/*--------------------------非直辖区域Begin------------------------*/
						if(substr($selArea, 2,4) == "0000")
						{
							foreach ($data['code'] as $key => $value)
							{
	 					if(substr($value,4,2)=="00" &&  substr($value,2,4)!="0000" && substr($value, 0,2)==substr($selArea,0,2))//如果绑定到市
	 					{

	 						if(!array_key_exists($value,$data2))
	 						{

	 							$strNameTwo = kernel::single('agent_data_area')->getAreaNameById($value);//getAreaNameById()函数，用于，根据编码，获取区域名称
	 							$data2[$value] = $strNameTwo;
	 						}

	 					}
	 					if(substr($value,4,2)!="00" && substr($value, 0,2)==substr($selArea,0,2))//如果绑定到县
	 					{
	 						$parent1=kernel::single('agent_data_area')->getHighArea($value);
	 						if(!array_key_exists($parent1,$data2))
	 						{

	 							$strNameTwo = kernel::single('agent_data_area')->getAreaNameById($parent1);//getAreaNameById()函数，用于，根据编码，获取区域名称
	 							$data2[$parent1] = $strNameTwo;
	 						}
	 					}
	 				}
	 			}

	 			//如果是市
	 			if(substr($selArea, 4,2) == "00" && substr($selArea, 2,4) != "0000")
	 			{
	 				foreach ($data['code'] as $key => $value) {
	 					if(substr($value,4,2)!="00" && substr($value, 0,4)==substr($selArea, 0,4))
	 					{				
	 						if(!array_key_exists($value,$data2))
	 						{
	          			 		//getAreaNameById()函数，用于，根据编码，获取区域名称
	 							$strNameTwo = kernel::single('agent_data_area')->getAreaNameById($value);
	 							$data2[$value] = $strNameTwo;
	 						}
	 					}
	 				}
	 				/*--------------------------非直辖区域End------------------------*/
	 			}
	 		}
	 		$pagedata['areaNameSecond'] =  $data2;

	 		return response::json($pagedata);
	 	}
	 }
	 ?>