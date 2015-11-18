<?php
/**
 * HDVG 角色提成 提成日结报表
 * Ric
 */
class syshdtj_analysis_day extends ectools_analysis_abstract implements ectools_analysis_interface
{
	public $detail_options = array(
		'hidden' => false,
		'force_ext' => false,
	);
	public $graph_options = array(
		'hidden' => true,
	);
	public $logs_options = array(
		'1' => array(
			'name' => '总订单',
			'flag' => array(),
			'memo' => '完结订单数总计',
			'icon' => 'mceico_40.gif',
		),
		'2' => array(
			'name' => '产品单',
			'flag' => array(),
			'memo' => '完结的产品订单数',
			'icon' => 'mceico_40.gif',
		),
		'3' => array(
			'name' => '店铺单',
			'flag' => array(),
			'memo' => '完结的公众店铺开店数',
			'icon' => 'mceico_40.gif',
		),
        '4' => array(
            'name' => '演唱会',
            'flag' => array(),
            'memo' => '完结的演唱会门票数',
            'icon' => 'mceico_40.gif',
        ),
        '5' => array(
            'name' => '总收入',
            'flag' => array(),
            'memo' => '完结订单总业绩',
            'icon' => 'coins.gif',
        ),
        '6' => array(
            'name' => '产品收入',
            'flag' => array(),
            'memo' => '产品订单业绩',
            'icon' => 'coins.gif',
        ),
        '7' => array(
            'name' => '开店收入',
            'flag' => array(),
            'memo' => '公众店铺业绩',
            'icon' => 'coins.gif',
        ),
        '8' => array(
            'name' => '演唱会收入',
            'flag' => array(),
            'memo' => '演唱会业绩',
            'icon' => 'coins.gif',
        ),
        '9' => array(
            'name' => '供应商',
            'flag' => array(),
            'memo' => '需要给供应商的成本',
            'icon' => 'coins.gif',
        ),
        '10' => array(
            'name' => '分销商',
            'flag' => array(),
            'memo' => '需要给分销商的提成',
            'icon' => 'coins.gif',
        ),
        '11' => array(
            'name' => '供所在县',
            'flag' => array(),
            'memo' => '需要给供应商所在行政县的提成',
            'icon' => 'coins.gif',
        ),
        '12' => array(
            'name' => '行政省',
            'flag' => array(),
            'memo' => '需要给行政省的提成',
            'icon' => 'coins.gif',
        ),
        '13' => array(
            'name' => '行政市',
            'flag' => array(),
            'memo' => '需要给行政市的提成',
            'icon' => 'coins.gif',
        ),
        '14' => array(
            'name' => '行政县',
            'flag' => array(),
            'memo' => '需要给行政县的提成',
            'icon' => 'coins.gif',
        ),
        '15' => array(
            'name' => '恒大微购',
            'flag' => array(),
            'memo' => '恒大微购基本提成',
            'icon' => 'coins.gif',
        ),
	);

	public function ext_detail(&$detail)
	{
		$filter = $this->_params;
		$filter['time_from'] = isset($filter['time_from'])?strtotime($filter['time_from']):'';
		$filter['time_to'] = isset($filter['time_to'])?(strtotime($filter['time_to'])+86400):'';
		$saleObj = app::get('syshdtj')->model('day')->get_pay_money($filter);
		$detail['总订单']['value'] = $saleObj['countnum'];
        $detail['产品单']['value'] = $saleObj['shopnum'];
        $detail['店铺单']['value'] = $saleObj['topdnum'];
        $detail['演唱会']['value'] = $saleObj['concertnum'];
        $detail['总收入']['value'] = $saleObj['countprice'];
        $detail['产品收入']['value'] = $saleObj['shopprice'];
        $detail['开店收入']['value'] = $saleObj['topdprice'];
        $detail['演唱会收入']['value'] = $saleObj['concertprice'];
        $detail['供应商']['value'] = $saleObj['gprice'];
        $detail['分销商']['value'] = $saleObj['tprice'];
        $detail['供所在县']['value'] = $saleObj['gxprice'];
        $detail['行政省']['value'] = $saleObj['xzsprice'];
        $detail['行政市']['value'] = $saleObj['xzshiprice'];
        $detail['行政县']['value'] = $saleObj['xzxprice'];
        $detail['恒大微购']['value'] = $saleObj['hdvgprice'];
	}

	public function finder()
	{
		return array(
			'model' => 'syshdtj_mdl_day',
			'params' => array(
				'actions'=>array(
					array(
						'label'=>app::get('syshdtj')->_('生成报表'),
						'class'=>'export',
						'icon'=>'add.gif',
						'href' => '?app=importexport&ctl=admin_export&act=export_view&_params[app]=syshdtj&_params[mdl]=syshdtj_mdl_day',
						'target'=>'{width:400,height:170,title:\''.app::get('syshdtj')->_('生成报表').'\'}'),
				),
				'title'=>app::get('sysstat')->_('日结报表'),
				'use_buildin_selectrow'=>false,
                'use_buildin_delete'=>false,
			),
		);
	}

}
