<?php
class agent_ctl_member_distributorList  extends agent_controller
{
	public function index()
	{

		$filter = input::get();
		/*----------处理分页查询相关数据Begin----*/
	 			$limit = 5;//设置页面限定长度
	 			$filter['limit'] = $limit;
	 			if($filter['pages'] =="1")
	 			{
	 				$filter['start'] = 0;
	 				$filter['limit']  = $limit;

	 			}
	 			else if($filter['pages'])
	 			{
	 				$filter['start'] = $limit * (intval($filter['pages'])-1);
	 				$filter['limit']  = $limit;

	 			}else if(!$filter['pages'])
	 			{

	 				$filter['start'] = 0;
	 				$filter['limit']  = $limit;
	 			}
	 			$current = $filter['pages'];
	 			unset($filter['pages']);
	 			/*---------//处理分页查询相关数据End------*/

	 			$agent_id = pamAccount::getAccountId();
	 		 	//根据登录代理商的ID，查询其代理区域
	 			$agent_area = app::get('agent')->model('account')->getRow('agent_area,agent_grade', array('agent_id'=>$agent_id));
	 			$agent_grade=$agent_area["agent_grade"];
	 			$data =unserialize($agent_area['agent_area']);

	 			/*--------这是县代理Begin-----*/
	 			if($agent_grade == "县")
	 			{
	 				foreach ($data['code'] as $key => $value) {
	 					$data1[$value] = $data['area'][$key];
	 				}
	 				$pagedata['areaName'] =  $data1;

	 				/*---------------------------获取In条件  Begin----------------------------------*/
	              $inCondition ="";//该变量用于in条件
		 		 //根据代理区域，查询其所在代理区域的会员
	              foreach ($data['code'] as $key => $value) {
	              	$inCondition .= "'";
	 		 	       //getAreaNameById()函数，用于，根据编码，获取区域名称
	              	$strNameOne = kernel::single('agent_data_area')->getAreaNameById($value);

	              	$strCodeOne = $value;	
	 		 	       //函数getHighArea() 用于  根据编码，获取上一级区域编码
	              	$parent1=kernel::single('agent_data_area')->getHighArea($value);

	              	if($parent1!="")
	              	{
	              		
	              		$strNameTwo =kernel::single('agent_data_area')->getAreaNameById($parent1);
	              		$strCodeTwo=$parent1;

	              		$parent2=kernel::single('agent_data_area')->getHighArea($parent1);

	              		
	              		if($parent2=="110000" || $parent2=="120000" ||$parent2=="310000" ||$parent2=="500000")
	              		{

	              			$inCondition .= $strNameTwo."/".$strNameOne.":".$strCodeTwo."/".$strCodeOne."',";
	              		}else
	              		{
	              			$strNameThree =kernel::single('agent_data_area')->getAreaNameById($parent2);
	              			$strCodeThree =$parent2;
	              			$inCondition .= $strNameThree."/".$strNameTwo."/".$strNameOne.":".$strCodeThree."/".$strCodeTwo."/".$strCodeOne."',";
	              			
	              		}
	              	}else
	              	{
	              		$inCondition .='\''.$strNameOne.":".$strCodeOne."',";
	              	}
	              	/*---------------------------获取In条件  End---------------------------------*/

	 			  // $filter = "addr like '%{$val}%'";
	 		    // $pagedata['user'][]= app::get('sysuser')->database()->createQueryBuilder()->from('sysuser_user')->select('*')->where( $filter)->execute()->fetchall();
	 		 	//$pagedata['user'][] = app::get('sysuser')->database()->executeQuery("SELECT * FROM `sysuser_user` where  `addr` like '%".$val."%' limit  ? , ?",[0,2])->fetchAll();
	              }
	              	//$pagedata['user'][] = app::get('sysuser')->database()->executeQuery("SELECT * FROM `sysuser_user` where  `addr` in(".$inCondition.") limit  ? , ?",[0,2])->fetchAll();
	              	//pr("-------------------------------------");
	              	//pd($pagedata);

	              $inCondition=substr($inCondition,0,strlen($inCondition)-1).'';

	              $qb = app::get('sysuser')->database()->createQueryBuilder();
 			       // $pagedata['user'][] = $qb->select('*')->from('sysuser_user')->where($qb->expr()->in('area', $inCondition))->execute()->fetchAll();
 					//$pagedata['user'][] = $qb->select('*')->from('sysuser_user')->setFirstResult(0)->setMaxResults(1)->where($qb->expr()->in('area', $inCondition));

	              /*--获取所匹配的数据总数Begin---*/
	              $reTotal = app::get('sysuser')->database()->executeQuery("SELECT count(*) as tol FROM `sysuser_user` where  `area`  in(".$inCondition.")")->fetchAll();
	              $total=$reTotal[0]['tol'];
	              /*--获取所匹配的数据总数End---*/

	              /*返回所匹配的数据Begin*/
	              $pagedata['user'][] = app::get('sysuser')->database()->executeQuery("SELECT name,grade_id,username,sex,point,refer_url,regtime,area,experience,lang,vocation FROM `sysuser_user` where  `area`  in(".$inCondition.") limit  ? , ?",[$filter['start'],$filter['limit']])->fetchAll();
	              /*返回所匹配的数据End*/


	              /*---------------------------------------县代理End------------------------*/
	          }else if($agent_grade == "市")
	          {

	          	foreach ($data['code'] as $key => $value) {

	          		if(substr($value,4,2)!= "00")
	          		{

	          			$code=substr($value,0,4)."00";
	          			if(!array_key_exists($code,$data1))
	          			{
	          				
	          			   //getAreaNameById()函数，用于，根据编码，获取区域名称
	          				$strName = kernel::single('agent_data_area')->getAreaNameById($code);
	          				$data1[$code] = $strName;

	          			}

	          		}else if(substr($value,4,2) == "00")
	          		{
	          			if(!array_key_exists($value,$data1))
	          			{
	          				$data1[$value] = $data['area'][$key];

	          			}
	          		}
	          	}
	          	$pagedata['areaName'] =  $data1;


	          	$reTotalAll="";
	          	/*-----------------------------------市代理Begin------------------*/
	          	foreach ($data['code'] as $key => $value) {
	          			//市代理可以查询整个市的会员
	          		$code=substr($value, 0,4)."00";

	          		$reTotal = app::get('sysuser')->database()->executeQuery("SELECT count(*) as tol FROM `sysuser_user` where  `area` like '%".$code."%' ")->fetchAll();
	          		$reTotalAll += $reTotal[0]['tol'];


	          		$pagedata['user'][] = app::get('sysuser')->database()->executeQuery("SELECT * FROM `sysuser_user` where  `area` like '%".$code."%' limit  ? , ?",[$filter['start'],$filter['limit']])->fetchAll();

	          	}

	          	$total = $reTotalAll;



	          	/*-------------------------------------市代理End---------------------*/
	          }else if($agent_grade == "省")
	          { 

	          	$data1 = array(); 
	          	foreach ($data['code'] as $key => $value) {
	          		if(substr($value, 2,4)=="0000")
	          		{
	          			if(!array_key_exists($value,$data1))
	          			{
	          				$data1[$value] = $data['area'][$key];
	          			}
	          		}else if(substr($value,4,2) == "00")
	          		{
	          			$code=substr($value,0,2)."0000";
	          			if(!array_key_exists($code,$data1))
	          			{
	          				 //getAreaNameById()函数，用于，根据编码，获取区域名称
	          				$strName = kernel::single('agent_data_area')->getAreaNameById($code);
	          				$data1[$code] = $strName;
	          			}
	          		}else if(substr($value,4,2)!= "00")
	          		{

	          			$code=substr($value,0,2)."0000";
	          			if(!array_key_exists($code,$data1))
	          			{
	          				
	          			   //getAreaNameById()函数，用于，根据编码，获取区域名称
	          				$strName = kernel::single('agent_data_area')->getAreaNameById($code);
	          				$data1[$code] = $strName;
	          			}

	          		}
	          	}
	          	$pagedata['areaName'] =  $data1;

	          	$reTotalAll="";
	          	/*-----------------------------------省代理Begin------------------*/
	          	foreach ($data['code'] as $key => $value) {
	          		$code = substr($value, 0,2). "0000";	          		
	          		$reTotal = app::get('sysuser')->database()->executeQuery("SELECT count(*) as tol FROM `sysuser_user` where  `area` like '%".$code."%' ")->fetchAll();
	          		$reTotalAll += $reTotal[0]['tol'];
	          		$pagedata['user'][] = app::get('sysuser')->database()->executeQuery("SELECT * FROM `sysuser_user` where  `area` like '%".$code."%' limit  ? , ?",[$filter['start'],$filter['limit']])->fetchAll();
	          	}
	          	/*-------------------------------------省代理End---------------------*/
	          }

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
	 			  return $this->page("agent/member/distributorList.html",$pagedata);
	 			}

	 			//根据会员注册时间筛选信息
	 			public function searchRegtime()
	 			{
	 				$postData = input::get();
	 				$regtime= explode("-", $postData['regtime']);
	 				$timeStar = strtotime($regtime[0]);
	 				$timeEnd = strtotime($regtime[1]);
	 				$agent_id = pamAccount::getAccountId();
	 		 	//根据登录代理商的ID，查询其代理区域
	 				$agent_area = app::get('agent')->model('account')->getRow('agent_area,agent_grade', array('agent_id'=>$agent_id));
	 				$data =unserialize($agent_area['agent_area']);
	 				$grade = $agent_area['agent_grade'];
	 				foreach ($data['code'] as $key => $value)
	 				{
	 					if($grade == "县")
	 					{
	 						$pagedata['user'][] = app::get('sysuser')->database()->executeQuery("SELECT name,grade_id,username,sex,point,refer_url,regtime,area,experience,lang,vocation  FROM `sysuser_user` where   `area` like '%".$value."%' and `regtime` between ? AND  ?",[$timeStar,$timeEnd])->fetchAll();

	 					}else if($grade == "市")
	 					{
	 						$value=substr($value, 0,4)."00";
	 						$pagedata['user'][] = app::get('sysuser')->database()->executeQuery("SELECT name,grade_id,username,sex,point,refer_url,regtime,area,experience,lang,vocation  FROM `sysuser_user` where   `area` like '%".$value."%' and `regtime` between ? AND  ?",[$timeStar,$timeEnd])->fetchAll();

	 					}else if($grade =="省")
	 					{
	 						$value = substr($value,0,2)."0000";
	 						$pagedata['user'][] = app::get('sysuser')->database()->executeQuery("SELECT name,grade_id,username,sex,point,refer_url,regtime,area,experience,lang,vocation  FROM `sysuser_user` where   `area` like '%".$value."%' and `regtime` between ? AND  ?",[$timeStar,$timeEnd])->fetchAll();
	 					}
	 				}
	 				return response::json($pagedata);
	 			}



        //第三级select框
        public function searchAreaThree()
        {
        	$postData = input::get();
        	$selArea = $postData["selArea"];
        	//getAreaNameById()函数，用于，根据编码，获取区域名称
        	$pagedata['areaName1'] = kernel::single('agent_data_area')->getAreaNameById($selArea);
        	$pagedata['user'][] = app::get('sysuser')->database()->executeQuery("SELECT name,grade_id,username,sex,point,refer_url,regtime,area,experience,lang,vocation FROM `sysuser_user` where  `area` like '%".$selArea."%' ")->fetchAll();
        	
			//需要返回其会员等级的个数,会员级别对应为1，2----8；
        	$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('1')")->fetchAll();
        	$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('2')")->fetchAll();
        	$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('3')")->fetchAll();
        	$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('4')")->fetchAll();
        	$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('5')")->fetchAll();
        	$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('6')")->fetchAll();
        	$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('7')")->fetchAll();
        	$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('8')")->fetchAll();
        	
        	
        	return response::json($pagedata);		
        }

        //根据昵称筛选信息
        public function searchName()
        {

        	$postData = input::get();
        	$name = $postData['name'];
        	$agent_id = pamAccount::getAccountId();
        	$agent_area = app::get('agent')->model('account')->getRow('agent_area,agent_grade', array('agent_id'=>$agent_id));
        	$data =unserialize($agent_area['agent_area']);

        	$grade = $agent_area['agent_grade'];

				 //根据代理区域，查询其所在代理区域的会员
        	foreach ($data['code'] as $key => $value)
        	{
        		if($grade == "县")
        		{
        			$filter = "area like '%{$value}%' and name like '%{$name}%' ";
        		}else if($grade =="市")
        		{
        			$value=substr($value, 0,4)."00";
        			$filter = "area like '%{$value}%' and name like '%{$name}%' ";

        		}else if($grade =="省")
        		{
        			$value=substr($value, 0,2)."0000";
        			$filter = "area like '%{$value}%' and name like '%{$name}%' ";

        		}
	 		    //$filter = '\'addr like "%' .substr($value, 0 ,6).'%"\'';
        		$pagedata['user'][]= app::get('sysuser')->database()->createQueryBuilder()->from('sysuser_user')->select('name,grade_id,username,sex,point,refer_url,regtime,area,experience,lang,vocation')->where( $filter)->execute()->fetchall();
        	}
        	return response::json($pagedata);
        }
	 	//根据会员等级筛选信息
        public function searchGradeId()
        {
        	$postData = input::get();
        	$GradeId = $postData['selGradeId'];
        	$agent_id = pamAccount::getAccountId();
        	$agent_area = app::get('agent')->model('account')->getRow('agent_area,agent_grade', array('agent_id'=>$agent_id));
        	$grade = $agent_area['agent_grade'];
        	$data =unserialize($agent_area['agent_area']);

	 		 //根据代理区域，查询其所在代理区域的会员
        	foreach ($data['code'] as $key => $value)
        	{
        		if($grade =="县")
        		{

        		}else if($grade == "市")
        		{
        			$value = substr($value, 0,4)."00";
        		}else if($grade =="省")
        		{
        			$value = substr($value,0, 2)."0000";

        		}
        		$filter = "area like '%{$value}%' and grade_id in('$GradeId')";
        		$pagedata['user'][]= app::get('sysuser')->database()->createQueryBuilder()->from('sysuser_user')->select('*')->where( $filter)->execute()->fetchall();					
        	}
        	return response::json($pagedata);
        }


	 	//根据区域筛选会员信息
	 	//第一级select框   第二级select框
	 			public function searchArea()
	 			{
	 				$postData = input::get();
	 				$selArea = $postData['selArea'];

	 					//getAreaNameById()函数，用于，根据编码，获取区域名称
	 				$pagedata['areaName1'] = kernel::single('agent_data_area')->getAreaNameById($selArea);

	 				/*--------------4个直辖区域，特殊处理----Begin------------------*/
	 				if(substr($selArea,0,4)=="1101" ||  substr($selArea,0,4)=="1201"  || substr($selArea,0,4)=="3101" || substr($selArea,0,4)=="5001")
	 				{

	 			    	//判断有没有下一级
	 					if(substr($selArea,4,2)=="00")
	 					{
	 			    		//如果select有下一级，需要返回第二级select框，还需要返回其select所选定的对应的区域
	 						$pagedata['user'][] = app::get('sysuser')->database()->executeQuery("SELECT name,grade_id,username,sex,point,refer_url,regtime,area,experience,lang,vocation FROM `sysuser_user` where  `area` like '%".$selArea."%' ")->fetchAll();
	 						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('1')")->fetchAll();
	 						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('2')")->fetchAll();
	 						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('3')")->fetchAll();
	 						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('4')")->fetchAll();
	 						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('5')")->fetchAll();
	 						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('6')")->fetchAll();
	 						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('7')")->fetchAll();
	 						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('8')")->fetchAll();
	 						



	 						$agent_id = pamAccount::getAccountId();
	 		 				//根据登录代理商的ID，查询其代理区域
	 						$agent_area = app::get('agent')->model('account')->getRow('agent_area,agent_grade', array('agent_id'=>$agent_id));
	 						$agent_grade=$agent_area["agent_grade"];
	 						$data =unserialize($agent_area['agent_area']);
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
	 						$pagedata['areaNameSecond'] =  $data2;
	 						$pagedata['nextSel'] = "2";//当值为0时候，表示html中没有下一级select框

	 					}else
	 					{
	 						$pagedata['nextSel'] = "0";
	 			    		//如果没有下一级，则，只是返回select对应区域下的会员信息
	 						$pagedata['user'][] = app::get('sysuser')->database()->executeQuery("SELECT * FROM `sysuser_user` where  `area` like '%".$selArea."%' ")->fetchAll();
	 						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('1')")->fetchAll();
	 						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('2')")->fetchAll();
	 						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('3')")->fetchAll();
	 						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('4')")->fetchAll();
	 						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('5')")->fetchAll();
	 						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('6')")->fetchAll();
	 						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('7')")->fetchAll();
	 						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('8')")->fetchAll();
	 						
	 					}
	 					return response::json($pagedata);
	 					/*--------------4个直辖区域，特殊处理----Begin------------------*/
	 					/*--------------------------非直辖区域Begin------------------------*/
	 				}else  
	 				{
	 			    	//判断其有没有下一级
	 					/*--------------------------------------省代理Begin-------------------------------------------------*/
	 					if(substr($selArea, 2,4) == "0000")
	 					{
	 						/*如果是省，则返回省信息，还考虑下一级select框*/
				  		  //1：  返回该省下的，所有会员信息
	 						$pagedata['user'][] = app::get('sysuser')->database()->executeQuery("SELECT name,grade_id,username,sex,point,refer_url,regtime,area,experience,lang,vocation FROM `sysuser_user` where  `area` like '%".$selArea."%' ")->fetchAll();


	 			    		//需要返回其会员等级的个数,会员级别对应为1，2----8；
	 						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('1')")->fetchAll();
	 						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('2')")->fetchAll();
	 						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('3')")->fetchAll();
	 						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('4')")->fetchAll();
	 						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('5')")->fetchAll();
	 						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('6')")->fetchAll();
	 						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('7')")->fetchAll();
	 						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('8')")->fetchAll();
	 						

							//2：  返回下一级select框,即该省下，被代理的市
	 			    		/*$code=substr($selArea, 0,2);
	 			    		$pagedata1['FirstNextSel'] = app::get('sysuser')->database()->executeQuery("SELECT area FROM `sysuser_user` where  `area` like '%".$code."__00%' ")->fetchAll();
	 			    		$secondCode="";
	 			    		foreach ($pagedata1['FirstNextSel'] as $key => $value) {
	 			    			$arr=explode("/",($value['area']));
	 			    			$secondCode = $arr[3];
	 			    			if(!array_key_exists($secondCode,$strNameTwo)){

						  			//getAreaNameById()函数，用于，根据编码，获取区域名称
	 			    				$secondCodeName = kernel::single('agent_data_area')->getAreaNameById($arr[3]);
	 			    				$strNameTwo[$secondCode] = $secondCodeName ;
	 			    			}
	 			    		}*/

	 			    		$agent_id = pamAccount::getAccountId();
	 		 				//根据登录代理商的ID，查询其代理区域
	 			    		$agent_area = app::get('agent')->model('account')->getRow('agent_area', array('agent_id'=>$agent_id));
	 			    		
	 			    		$data =unserialize($agent_area['agent_area']);
	 			    		foreach ($data['code'] as $key => $value) {
	 							//如果绑定到省,则没有下一级select框
	 			    			if(substr($value,2,4)=="0000" && substr($value, 0,2)==substr($selArea,0,2))
	 			    			{
	 								//$pagedata['nextSel'] = "0";//值为0，表示html中没有了下一级的select框
	 							}else if(substr($value,4,2)=="00" && substr($value, 0,2)==substr($selArea,0,2))//如果绑定到市
	 							{

	 								if(!array_key_exists($value,$data2))
	 								{
	          			 				//getAreaNameById()函数，用于，根据编码，获取区域名称
	 									$strNameTwo = kernel::single('agent_data_area')->getAreaNameById($value);
	 									$data2[$value] = $strNameTwo;
	 								}
	 								
	 							}else if(substr($value,4,2)!="00" && substr($value, 0,2)==substr($selArea,0,2))//如果绑定到县
	 							{
	 								$parent1=kernel::single('agent_data_area')->getHighArea($value);
	 								if(!array_key_exists($parent1,$data2))
	 								{
	          			 					//getAreaNameById()函数，用于，根据编码，获取区域名称
	 									
	 									$strNameTwo = kernel::single('agent_data_area')->getAreaNameById($parent1);

	 									$data2[$parent1] = $strNameTwo;
	 								}
	 							}
	 							/*if(substr($value, 4,2)=="00" &&   substr($value, 0,4)==substr($selArea, 0,4))
	 							{
	 								if(!array_key_exists($value,$data2))
	 								{
	          			 				//getAreaNameById()函数，用于，根据编码，获取区域名称
	 									$strNameTwo = kernel::single('agent_data_area')->getAreaNameById($value);
	 									$data2[$value] = $strNameTwo;
	 								}
	 							}else if(substr($value,4,2)!="00" && substr($value, 0,4)==substr($selArea, 0,4))
	 							{
	 								if(!array_key_exists($value,$data2))
	 								{
	          			 					//getAreaNameById()函数，用于，根据编码，获取区域名称
	 									$strNameTwo = kernel::single('agent_data_area')->getAreaNameById($value);
	 									$data2[$value] = $strNameTwo;
	 								}
	 							}*/
	 						}
	 						$pagedata['areaNameSecond'] =  $data2;
	 						
					/*	$str="1234567/148800/jdsk:/";
						$a="14";
						$b="00";
						$preg="/\/14\d{2}00/\/";
						preg_match($preg,$str,$match);
						pd($match);*/
						return response::json($pagedata);

					}else if(substr($selArea, 4, 2) == "00")
					{

						/*----------------------------如果是市，则返回市信息，还考虑下一级select框-------------------------------*/
        			 //1：  返回该市下的，所有会员信息
						$pagedata['user'][] = app::get('sysuser')->database()->executeQuery("SELECT name,grade_id,username,sex,point,refer_url,regtime,area,experience,lang,vocation  FROM `sysuser_user` where  `area` like '%".$selArea."%' ")->fetchAll();
						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('1')")->fetchAll();
						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('2')")->fetchAll();
						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('3')")->fetchAll();
						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('4')")->fetchAll();
						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('5')")->fetchAll();
						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('6')")->fetchAll();
						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('7')")->fetchAll();
						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('8')")->fetchAll();
						
					//2：  返回下一级select框,即该市下，被代理的县
						$agent_id = pamAccount::getAccountId();
	 		 				//根据登录代理商的ID，查询其代理区域
						$agent_area = app::get('agent')->model('account')->getRow('agent_area', array('agent_id'=>$agent_id));
						
						$data =unserialize($agent_area['agent_area']);

						foreach ($data['code'] as $key => $value) {

							if(substr($value,4,2)!="00" && substr($value, 0,4)==substr($selArea, 0,4))
							{
	 								/*$parent1=kernel::single('agent_data_area')->getHighArea($value);
	 								if(!array_key_exists($parent1,$data2))
	 								{
	          			 					//getAreaNameById()函数，用于，根据编码，获取区域名称
	 									
	 									$strNameTwo = kernel::single('agent_data_area')->getAreaNameById($parent1);

	 									$data2[$parent1] = $strNameTwo;
	 								}*/
	 								if(!array_key_exists($value,$data2))
	 								{
	          			 					//getAreaNameById()函数，用于，根据编码，获取区域名称
	 									$strNameTwo = kernel::single('agent_data_area')->getAreaNameById($value);
	 									$data2[$value] = $strNameTwo;
	 								}
	 							}else if(substr($value,4,2)=="00" && substr($value, 0,4)==substr($selArea, 0,4))
	 							{
	 								if(!array_key_exists($value,$data2))
	 								{
	          			 					//getAreaNameById()函数，用于，根据编码，获取区域名称
	 									$strNameTwo = kernel::single('agent_data_area')->getAreaNameById($value);
	 									$data2[$value] = $strNameTwo;
	 								}
	 							}
	 						}
	 						$pagedata['areaNameSecond'] =  $data2;
	 						$pagedata['nextSel'] = "1";

	 						return response::json($pagedata);

						/*if($selArea =="110100" || $selArea=="120100" || $selArea=="310100" || $selArea=="500100")
						{
							$code = substr($selArea, 0,4);
							$pagedata1['FirstNextSel'] = app::get('sysuser')->database()->executeQuery("SELECT area FROM `sysuser_user` where  `area` like '%/".$code."__%' ")->fetchAll();
							$secondCode="";
							foreach ($pagedata1['FirstNextSel'] as $key => $value) {
								$arr=explode("/",($value['area']));
								pr($arr);
							}
						}else
						{
							$code = substr($selArea, 0,4);
							$pagedata1['FirstNextSel'] = app::get('sysuser')->database()->executeQuery("SELECT area FROM `sysuser_user` where  `area` like '%/".$code."__%' ")->fetchAll();

							$secondCode="";
							foreach ($pagedata1['FirstNextSel'] as $key => $value) {

								$arr=explode("/",($value['area']));
								$secondCode = $arr[4];
								if(!array_key_exists($secondCode,$strNameTwo)){
						  			//getAreaNameById()函数，用于，根据编码，获取区域名称
									$secondCodeName = kernel::single('agent_data_area')->getAreaNameById($arr[4]);
									$strNameTwo[$secondCode] = $secondCodeName ;
								}
							}
							$pagedata['areaNameSecond'] =  $strNameTwo;
							$pagedata['nextSel'] = "1";

							return response::json($pagedata);
						}*/
						

					}else if(substr($selArea, 4,2) != "00")
					{
						/*-------------------------------县代理-----------------------------------*/
						$pagedata['user'][] = app::get('sysuser')->database()->executeQuery("SELECT name,grade_id,username,sex,point,refer_url,regtime,area,experience,lang,vocation FROM `sysuser_user` where  `area` like '%".$selArea."%' ")->fetchAll();
						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('1')")->fetchAll();
						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('2')")->fetchAll();
						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('3')")->fetchAll();
						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('4')")->fetchAll();
						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('5')")->fetchAll();
						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('6')")->fetchAll();
						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('7')")->fetchAll();
						$pagedata["grade1"][]=app::get('sysuser')->database()->executeQuery("SELECT count(*) as gradeTol FROM `sysuser_user` where  `area` like '%".$selArea."%' and grade_id in ('8')")->fetchAll();
						
						$pagedata['nextSel'] = "0";

        		/*//如果是县，则直接输出，而没有下级的select框
        		$agent_id = pamAccount::getAccountId();
	 		 	//根据登录代理商的ID，查询其代理区域
        		$agent_area = app::get('agent')->model('account')->getRow('agent_area', array('agent_id'=>$agent_id));
        		$data =unserialize($agent_area['agent_area']);
        		$filter = "addr like '%{$selArea}%'";
        		$pagedata['user'][]= app::get('sysuser')->database()->createQueryBuilder()->from('sysuser_user')->select('*')->where( $filter)->execute()->fetchall();
       			 */		
        		return response::json($pagedata);
        	}
        }
    }




}

?>