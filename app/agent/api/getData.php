<?php
class agent_api_getData
{
	public $apiDescription = "获取代理商，分销商会员统计的数据";
	public function getParams()
	{
			//定义字段的格式
		$return['params'] = array(
			'inforType' => ['type'=>'string','valid'=>'required', 'default'=>'', 'example'=>'','description'=>'传入的类型 一共有4种（trade,tradecount,item,itemcount）'],
			'timeType' => ['type'=>'string','valid'=>'required', 'default'=>'', 'example'=>'','description'=>'传入的时间类型 一共有6种(yesterday,beforday,week,month,selecttime,select)'],
			'starttime' => ['type'=>'string','valid'=>'', 'default'=>'', 'example'=>'','description'=>'起始时间段。如：2015/05/15-2015/05/15'],
			'endtime' => ['type'=>'string','valid'=>'', 'default'=>'', 'example'=>'','description'=>'结束时间段。如：2015/05/03-2015/05/03'],
			'limit' => ['type'=>'int','valid'=>'', 'default'=>'', 'example'=>'','description'=>'查询限制的条数'],
			'start' => ['type'=>'int','valid'=>'', 'default'=>'', 'example'=>'','description'=>'查询开始的条数'],
			'orderBy' => ['type'=>'string','valid'=>'', 'default'=>'', 'example'=>'','description'=>'查询按什么排序'],
			'dataType' => ['type'=>'string','valid'=>'', 'default'=>'', 'example'=>'','description'=>'获取的数据类型'],
			'tradeType' =>['type'=>'string','valid'=>'', 'default'=>'', 'example'=>'','description'=>'报表ajax请求的数据类型'],
			);
		return $return;
}
	public function getData($params)
	{
		$params['timefile'] = array('starttime'=>$params['starttime'],'endtime'=>$params['endtime']);
		pr($params);
	}
}

?>