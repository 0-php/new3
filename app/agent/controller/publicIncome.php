<?php
class agent_ctl_publicIncome extends agent_controller
{
	public function index()
	{
		$order_from = "店铺开店";
		$postdata = input::get();
		$filter = input::get();

		$type = $postdata['sendtype'];
		$pagedata['sendtype'] = $type;
		/*----------处理分页查询相关数据Begin----*/
	 			 $limit = 10;//设置页面限定长度
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


	 			 if($type == "yesterday")
	 			 {
				//计算昨天的日期
	 			 	$TimeStar=strtotime(date("Y-m-d",strtotime("-1 day")));
	 			 	$TimeEnd=strtotime(date("y-m-d",time())); 


				//如果$type为yesterday或着beforday，则计算上周同期
	 			 	$LastTimeStar=strtotime(date("Y-m-d",strtotime("-8 day")));
	 			 	$LastTimeEnd=strtotime(date("y-m-d",strtotime("-7 day"))); 
	 			 	
	 			 }else if($type=="today")
	 			 {
	 			 	$TimeStar=strtotime(date("y-m-d",time()));
	 			 	$TimeEnd= time();
	 			 	
	 			 }
	 			 else if($type == "beforday")
	 			 {
	 			 	$TimeStar=strtotime(date("Y-m-d",strtotime("-2 day")));
	 			 	$TimeEnd=strtotime(date("Y-m-d",strtotime("-1 day")));

				//如果$type为yesterday或着beforday，则计算上周同期
	 			 	$LastTimeStar=strtotime(date("Y-m-d",strtotime("-8 day")));
	 			 	$LastTimeEnd=strtotime(date("y-m-d",strtotime("-7 day"))); 
	 			 	
	 			 }else if($type=="week")
	 			 {
	 			 	$TimeStar=strtotime(date("Y-m-d",strtotime("-7 day")));
	 			 	$TimeEnd=time();
	 			 }else if($type == "month")
	 			 {
	 			 	$TimeStar=strtotime(date("Y-m-d",strtotime("-30 day")));
	 			 	$TimeEnd=time();
	 			 }else if($type=="all")
	 			 {
	 			 	$TimeStar = 0;
	 			 	$TimeEnd = time();
	 			 }else if($type=="search")
	 			 {
	 			 	$time = explode("-",$postdata['regtime']);
	 			 	$TimeStar=strtotime($time[0]);
	 			 	$TimeEnd=strtotime($time[1]);
	 			 	unset($postdata['regtime']);
	 			 	
	 			 }



			//计算昨天的新增订单金额
			//计算昨天新增订单数
			//计算昨天平均单价
			//字段
			//新增订单金额  addOrderMoney  
			//新增订单数    addOrderNum
			//平均单价   avgPrice
	 			 $pagedata['order'][]= app::get('syshdtj')->database()->executeQuery("SELECT sum(payment) as addOrderMoney,sum(num) as addOrderNum,avg(price) as avgPrice FROM  `syshdtj_order` where  `order_from` in('". $order_from."')  and (  `payed_time`  between ? AND  ?)",[$TimeStar,$TimeEnd])->fetchAll();
			//上周同期
	 			 
	 			 $pagedata['orderLast'][]= app::get('syshdtj')->database()->executeQuery("SELECT sum(payment) as addOrderMoney,sum(num) as addOrderNum,avg(price) as avgPrice FROM  `syshdtj_order` where   `order_from` in('". $order_from."')  and ( `payed_time`  between ? AND  ?)",[$LastTimeStar,$LastTimeEnd])->fetchAll();
	 			 
			//详细交易数据
	 			 $agent_area = app::get('agent')->model('account')->getRow('agent_area,agent_grade', array('agent_id'=>$agent_id));
	 			 $agent_grade=$agent_area["agent_grade"];
	 			 $pagedata['agent_grade']= $agent_grade;
	 			 if($agent_grade=="省")
	 			 {
	 			 	$pagedata['all'][]= app::get('syshdtj')->database()->executeQuery("SELECT area,szs_price,szshi_price,szx_price, price ,num ,title, bank,oid,order_from,FROM_UNIXTIME( payed_time, '%Y-%m-%d' )  as  payed_time    FROM  `syshdtj_order` where   `order_from` in('". $order_from."')  and ( `payed_time`  between ? AND  ? ) limit ?,?",[$TimeStar,$TimeEnd,$filter['start'],$filter['limit']])->fetchAll();
	 			 	$reTotal= app::get('syshdtj')->database()->executeQuery("SELECT count(*) as tol FROM  `syshdtj_order` where   `order_from` in('". $order_from."')  and ( `payed_time`  between ? AND  ? )",[$TimeStar,$TimeEnd])->fetchAll();
	 			 	$total=$reTotal[0]['tol'];
	 			 }else if($agent_grade=="市")
	 			 {
	 			 	$pagedata['all'][]= app::get('syshdtj')->database()->executeQuery("SELECT area,szshi_price,szx_price, price ,num ,title, bank,oid,order_from,FROM_UNIXTIME( payed_time, '%Y-%m-%d' )  as  payed_time    FROM  `syshdtj_order` where   `order_from` in('". $order_from."')  and ( `payed_time`  between ? AND  ? ) limit ?,?",[$TimeStar,$TimeEnd,$filter['start'],$filter['limit']])->fetchAll();
	 			 	$reTotal= app::get('syshdtj')->database()->executeQuery("SELECT count(*) as tol FROM  `syshdtj_order` where   `order_from` in('". $order_from."')  and ( `payed_time`  between ? AND  ? )",[$TimeStar,$TimeEnd])->fetchAll();
	 			 	$total=$reTotal[0]['tol'];
	 			 }else if($agent_grade=="县")
	 			 {
	 			 	$pagedata['all'][]= app::get('syshdtj')->database()->executeQuery("SELECT area,szx_price, price ,num ,title, bank,oid,order_from,FROM_UNIXTIME( payed_time, '%Y-%m-%d' )  as  payed_time    FROM  `syshdtj_order` where   `order_from` in('". $order_from."')  and ( `payed_time`  between ? AND  ? ) limit ?,?",[$TimeStar,$TimeEnd,$filter['start'],$filter['limit']])->fetchAll();
	 			 	$reTotal= app::get('syshdtj')->database()->executeQuery("SELECT count(*) as tol FROM  `syshdtj_order` where   `order_from` in('". $order_from."')  and ( `payed_time`  between ? AND  ? )",[$TimeStar,$TimeEnd])->fetchAll();
	 			 	$total=$reTotal[0]['tol'];
	 			 }

	 			 
			//报表交易数据的表格    时间，新增订单金额，新增订单数量，平均客单价
			//昨天
				//计算昨天的日期
	 			 $yesTimeStar=strtotime(date("Y-m-d",strtotime("-1 day")));
	 			 $yesTimeEnd=strtotime(date("y-m-d",time())); 
	 			 $pagedata['yesterday'][]= app::get('syshdtj')->database()->executeQuery("SELECT sum(payment) as addOrderMoney,sum(num) as addOrderNum,avg(price) as avgPrice FROM  `syshdtj_order` where   `order_from` in('". $order_from."')  and ( `payed_time`  between ? AND  ? )",[$yesTimeStar,$yesTimeEnd])->fetchAll();
			//前日
	 			 $beforeTimeStar=strtotime(date("Y-m-d",strtotime("-2 day")));
	 			 $beforeTimeEnd=strtotime(date("Y-m-d",strtotime("-1 day")));
	 			 $pagedata['beforeday'][]= app::get('syshdtj')->database()->executeQuery("SELECT sum(payment) as addOrderMoney,sum(num) as addOrderNum,avg(price) as avgPrice FROM  `syshdtj_order` where   `order_from` in('". $order_from."')  and ( `payed_time`  between ? AND  ? )",[$beforeTimeStar,$beforeTimeEnd])->fetchAll();
	 			 
			//近七天
	 			 $weekTimeStar=strtotime(date("Y-m-d",strtotime("-7 day")));
	 			 $weekTimeEnd=time();
	 			 $pagedata['week'][]= app::get('syshdtj')->database()->executeQuery("SELECT sum(payment) as addOrderMoney,sum(num) as addOrderNum,avg(price) as avgPrice FROM  `syshdtj_order` where   `order_from` in('". $order_from."')  and ( `payed_time`  between ? AND  ? )",[$weekTimeStar,$weekTimeEnd])->fetchAll();
	 			 
			//近30天	
	 			 $monthTimeStar=strtotime(date("Y-m-d",strtotime("-30 day")));
	 			 $monthTimeEnd=time();
	 			 $pagedata['month'][]= app::get('syshdtj')->database()->executeQuery("SELECT sum(payment) as addOrderMoney,sum(num) as addOrderNum,avg(price) as avgPrice FROM  `syshdtj_order` where  `order_from` in('". $order_from."')  and (  `payed_time`  between ? AND  ? )",[$TimeStar,$LastTimeEnd])->fetchAll();
	 			 

	 			 

	 			 /*分页*/
	 			  $totalPage = ceil($total/$filter['limit']);//返回分页页数
	 			  $filter['pages'] = time();
	 			  $pagers = array(
	 			  	'link'=>url::action('agent_ctl_publicIncome@index',$filter),
            		'current'=>$current,//当前起始条数
            		'use_app' => 'agent',
            		'total'=>$totalPage,
            		'token'=>time(),
            		);
	 			  $pagedata['pagers'] = $pagers; 
	 			  
	 			  
	 			  
	 			  /*------------业务报表----Begin-----------*/
	 		//获取所代理区域
	 			  $agent_id = pamAccount::getAccountId();
	 			  
	 			/*$pagedata['MonthReport'][]= app::get('syshdtj')->database()->executeQuery("SELECT   settlement_no,concertnum, concertprice,settlement_status,account_start_time,account_end_time,settlement_time FROM  `syshdtj_concert`   limit ?,?",[$filter['start'],$filter['limit']])->fetchAll();
	 			$totalMonth= app::get('syshdtj')->database()->executeQuery("SELECT  count(*) as tol FROM  `syshdtj_concert` ")->fetchAll();
*/
	 			$pagedata['MonthReport'][]= app::get('syshdtj')->database()->executeQuery("SELECT   settlement_no,topdnum, topdprice,settlement_status,FROM_UNIXTIME( account_start_time, '%Y-%m-%d' )  as account_start_time,FROM_UNIXTIME( account_end_time, '%Y-%m-%d' ) as account_end_time,  FROM_UNIXTIME( settlement_time, '%Y-%m-%d' ) as settlement_time FROM  `syshdtj_concert`  where `agent_id` in('".$agent_id."') limit ?,?",[$filter['start'],$filter['limit']])->fetchAll();
	 			$totalMonth= app::get('syshdtj')->database()->executeQuery("SELECT  count(*) as tol FROM  `syshdtj_concert` where `agent_id` in('".$agent_id."') ")->fetchAll();

	 			//业务报表分页
	 				$totalPage = ceil($totalMonth[0]["tol"]/$filter['limit']);//返回分页页数
	 				$filter['pages'] = time();
	 				$pagers = array(
	 					'link'=>url::action('agent_ctl_publicIncome@index',$filter),
            		'current'=>$current,//当前起始条数
            		'use_app' => 'agent',
            		'total'=>$totalPage,
            		'token'=>time(),
            		);
	 				$pagedata['pagersMonth'] = $pagers; 
	 				/*------------业务报表----End-----------*/
	 				


	 				return $this->page('agent/publicIncome.html', $pagedata);
	 			}


	 			public function ajaxOrder()
	 			{

	 			}
	 			public function searchRegtimeAll()
	 			{
	 				$order_from = "店铺开店";
	 				$postdata = input::get();
	 				$time=explode("-", $postdata['time']);
	 				
	 				$startTime = strtotime($time[0]);
	 				$endTime = strtotime($time[1]);
	 				$pagedata['searchRegtimeAll'][]= app::get('syshdtj')->database()->executeQuery("SELECT *  FROM  `syshdtj_order` where `order_from` in('". $order_from."')  and (  `payed_time`  between ? AND  ? )",[$startTime,$endTime])->fetchAll();
	 				
	 				return response::json($pagedata);	
	 			}

	 			public function searchRegtime()
	 			{
	 				$order_from = "店铺开店";
	 				$postdata = input::get();
	 				$time=explode("-", $postdata['time']);
	 				
	 				$startTime = strtotime($time[0]);
	 				$endTime = strtotime($time[1]);
	 				
	 				$pagedata['searchRegtime'][]= app::get('syshdtj')->database()->executeQuery("SELECT  count(*) as tol , sum(payment) as addOrderMoney,sum(num) as addOrderNum,avg(price) as avgPrice FROM  `syshdtj_order` where  `order_from` in('". $order_from."')  and ( `payed_time`  between ? AND  ? )",[$startTime,$endTime])->fetchAll();
	 				
	 				return response::json($pagedata);
	 			}

	 		}
	 		?>