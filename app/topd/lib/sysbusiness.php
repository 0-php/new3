<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */

class topd_sysbusiness
{
	public function index($postSend,$topd_id)
	{
		$type = $postSend['sendtype'];
		if(!$postSend || !in_array($postSend['sendtype'],array('yesterday','beforday','week','month','selecttime')))
		{
			$type='yesterday';
		}
		$postSend['sendtype'] = $type;
		//获取页面显示的时间
		$pagetime = app::get('topd')->rpcCall('sysstat.datatime.get',$this->__getParams('all',$postSend,'item'),'seller');
		if(!empty($postSend['starttime'])){
			$pagetime = $postSend['starttime'];
		}
		//统计总条数
		$count= app::get('syshdtj')->database()->createQueryBuilder()
			->select('oid,payed_time,order_from,title,topd_price')
			->from('syshdtj_order')
			->where("topd_id= $topd_id")
			->execute()->rowCount();
		//每页多少条
		$params['limit'] = 5;
		//当前页
		if($count>0) $total = ceil($count/$params['limit']);
		$current = ((int)$postSend['pages']>0) ? ((int)$postSend['pages']>$count?$count:$postSend['pages']) : 1;
		//分页赋值
		$pagedata['limits'] = $params['limit'];
		$pagedata['pages'] = $current;
		$postSend['pages'] = time();
		$pagedata['pagers'] = array(
			'link'=>url::action('topd_ctl_sysstat_sysbusiness@index',$postSend),
			'current'=>$current,
			'total'=>$total,
			'token'=>$postSend['pages']
		);
		//分页查询
		$offset = ($current - 1) * $params['limit'];
		$offset = (int)$offset<0 ? 0 : (int)$offset;
		$qb1 = app::get('syshdtj')->database()->createQueryBuilder()
			->select('oid,payed_time,order_from,title,topd_price')
			->from('syshdtj_order')
			->where("topd_id= $topd_id");
		//处理时间范围
		if(!empty($pagetime)){
			$totime=explode('-',$pagetime);
			$qb2 = $qb1 ->andWhere('payed_time >= '.strtotime($totime[0]));
			$qb3 = $qb2 ->andWhere('payed_time <= '.strtotime($totime[1]));
		}
		$qb = $qb3->setFirstResult($offset)
			->setMaxResults($params['limit'])
			->orderBy('payed_time','DESC')
			->execute()->fetchAll();
		$this->zt($qb,$sum);

		$pagedata['itemInfo'] = $qb;
		$pagedata['count'] = $sum;
		//搜索条件
		$pagedata['pagetime'] = $pagetime;
		$pagedata['sendtype'] = $type;
		return $pagedata;
	}

	/*
	 * 状态处理
	 * Ric
	 * */
	public function zt(&$qb,&$count){
		foreach($qb as $key => $val){
			if($val['order_from'] == '产品订单'){
				$row = app::get('systrade')->model('order')->getRow('status,aftersales_status,complaints_status', array('oid'=>$val['oid'],));
				if($row['status'] == 'WAIT_SELLER_SEND_GOODS'){
					$qb[$key]['zt'] = '<sanp style="color: #FF0000;">等待发货</sanp>';
					$count['wwjprice'] += $val['topd_price'];
					$count['wwj']++;
				}elseif($row['status'] == 'WAIT_BUYER_CONFIRM_GOODS'){
					$qb[$key]['zt'] = '<sanp style="color: #FF0000;">等待确认收货</sanp>';
					$count['wwjprice'] += $val['topd_price'];
					$count['wwj']++;
				}elseif($row['status'] == 'TRADE_BUYER_SIGNED'){
					$qb[$key]['zt'] = '<sanp style="color: #FF0000;">买家已签收</sanp>';
					$count['wjprice'] += $val['topd_price'];
					$count['wj']++;
				}elseif($row['status'] == 'TRADE_FINISHED'){
					$qb[$key]['zt'] = '<sanp style="color: #0046d4;">买家已签收</sanp>';
					$count['wjprice'] += $val['topd_price'];
					$count['wj']++;
				}elseif($row['status'] == 'TRADE_CLOSED_AFTER_PAY'){
					$qb[$key]['zt'] = '<sanp style="color: #FF0000;">买家退款</sanp>';
					$count['tkprice'] += $val['topd_price'];
					$count['tk']++;
				}else{
					$qb[$key]['zt'] = '<sanp style="color: #FF0000;">未付款</sanp>';
				}
				$count['shop']++;
			}elseif($val['order_from'] == '店铺开店'){
				$qb[$key]['zt'] = '交易完成';
				$count['topd']++;
				$count['topdprice'] += $val['topd_price'];
				$count['wjprice'] += $val['topd_price'];
				$count['wj']++;
			}elseif($val['order_from'] == '演唱会门票'){
				$qb[$key]['zt'] = '交易完成';
				$count['ych']++;
				$count['ychprice'] += $val['topd_price'];
				$count['wjprice'] += $val['topd_price'];
				$count['wj']++;
			}
			$count['sum']++;
			$count['sumprice'] += $val['topd_price'];
		}
	}

	//api参数组织
	private function __getParams($type,$postSend,$objType,$data=null)
	{
		if($type=='all')
		{
			$params = array(
				'inforType'=>$objType,
				'timeType'=>$postSend['sendtype'],
				'starttime'=>$postSend['starttime'],
				'endtime'=>$postSend['endtime'],
				'dataType'=>$type
			);
		}
		elseif($type=='notall')
		{
			$params = array(
				'inforType'=>$objType,
				'timeType'=>$postSend['sendtype'],
				'starttime'=>$postSend['starttime'],
				'endtime'=>$postSend['endtime'],
				'dataType'=>$type,
				'limit'=>$data['limit'],
				'start'=>$data['start']
			);
		}
		elseif($type=='graphall')
		{
			$params = array(
				'inforType'=>$objType,
				'tradeType'=>$postSend['trade'],
				'timeType'=>$postSend['sendtype'],
				'starttime'=>$postSend['starttime'],
				'endtime'=>$postSend['endtime'],
				'dataType'=>$type
			);
		}
		return $params;
	}
}
