<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */

class topd_ctl_sysstat_sysbusiness extends topd_controller
{
	public function index()
	{
		$topd_id = $this->userInfo['userId'];
		$postSend = input::get();
		$pagedata = kernel::single('topd_sysbusiness')->index($postSend,$topd_id);
		return $this->page('topd/sysstat/sysbusiness.html', $pagedata);
	}

	/**
	 * 异步获取数据  图表用
	 * @param null
	 * @return array
	 */

	public function ajaxTrade()
	{
		$postData = input::get();
		//api的参数
		$all = $this->__getParams('graphall',$postData,'trade');
		$datas =  app::get('topd')->rpcCall('sysstat.data.get',$all,'user');

		return response::json($datas);

	}
}
