<?php
class agent_ctl_index extends agent_controller
{

	public function index()
	{

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

	 			 $agent_id = pamAccount::getAccountId();
	 			$pagedata['MonthReport'][]= app::get('syshdtj')->database()->executeQuery("SELECT   settlement_no,shopnum, shopprice,topdnum,concertnum,shopprice,topdprice,concertprice,settlement_status,FROM_UNIXTIME( account_start_time, '%Y-%m-%d' )  as account_start_time,FROM_UNIXTIME( account_end_time, '%Y-%m-%d' ) as account_end_time,  FROM_UNIXTIME( settlement_time, '%Y-%m-%d' ) as settlement_time FROM  `syshdtj_concert`  where `agent_id` in('".$agent_id."') limit ?,?",[$filter['start'],$filter['limit']])->fetchAll();
	 			$totalMonth= app::get('syshdtj')->database()->executeQuery("SELECT  count(*) as tol FROM  `syshdtj_concert` where `agent_id` in('".$agent_id."') ")->fetchAll();

	 			//业务报表分页
	 				$totalPage = ceil($totalMonth[0]["tol"]/$filter['limit']);//返回分页页数
	 				$filter['pages'] = time();
	 				$pagers = array(
	 					'link'=>url::action('agent_ctl_index@index',$filter),
            		'current'=>$current,//当前起始条数
            		'use_app' => 'agent',
            		'total'=>$totalPage,
            		'token'=>time(),
            		);
	 				$pagedata['pagersMonth'] = $pagers; 
	 				/*------------业务报表----End-----------*/
	 				

	 			 return $this->page('agent/index.html',$pagedata);
	 			}
	 		}

	 		?>