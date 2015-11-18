<?php

class topshop_ctl_uedtest extends topshop_controller {


	function test1()
	{

		return $this->page('topshop/uedtest/nav_manage.html');
	}
	function test2()
	{

		return $this->page('topshop/uedtest/nav_edit.html');
	}
	function test3()
	{

		return $this->page('topshop/uedtest/goods_forsale.html');
	}
	function test4()
	{

		return $this->page('topshop/uedtest/expresstmpl_config.html');
	}
	function test5()
	{

		return $this->page('topshop/uedtest/expresstmpl_edit.html');
	}
	function test6()
	{

		return $this->page('topshop/uedtest/unsubmit_enter.html');
	}
	function test7()
	{

		return $this->page('topshop/uedtest/order_list.html');
	}
	function test8()
	{

		return $this->page('topshop/uedtest/order_detail.html');
	}
	function test9()
	{

		return $this->page('topshop/uedtest/homepage.html');
	}
	function test10()
	{

		return $this->page('topshop/uedtest/report.html');
	}
	function test11()
	{

		return $this->page('topshop/uedtest/report_deal.html');
	}
	function test12()
	{

		return $this->page('topshop/uedtest/report_business.html');
	}
	function test13()
	{

		return $this->page('topshop/uedtest/report_sale.html');
	}
	function test14()
	{

		return $this->page('topshop/uedtest/settlement_detail.html');
	}
	function test15()
	{

		return $this->page('topshop/uedtest/settlement_total.html');
	}
	function test16()
	{

		return $this->page('topshop/uedtest/settlement_total_detail.html');
	}
	function test17()
	{

		return $this->page('topshop/uedtest/merchant_info.html');
	}
	function test18()
	{
		return view::make('topshop/uedtest/error.html');
	}
}
