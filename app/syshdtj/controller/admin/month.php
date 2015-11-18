<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */

class  syshdtj_ctl_admin_month extends desktop_controller{

	public function index()
	{
        return $this->finder('syshdtj_mdl_month',array(
            'title' => app::get('syshdtj')->_('月结报表'),
            'use_buildin_delete'=>false,
            'use_buildin_set_tag'=>true,
            'use_buildin_filter'=>true,
            'use_buildin_tagedit'=>true,
            'use_buildin_set_tag'=>true,
            'use_buildin_export'=>true,
            'allow_detail_popup'=>true,
            'use_view_tab'=>true,
        ));
    }

    /**
     * 桌面订单相信汇总显示
     * @param null
     * @return null
     */
    public function _views()
    {
        $mdl_aftersales = app::get('syshdtj')->model('month');
        $sub_menu = array(
            0=>array('label'=>app::get('syshdtj')->_('全部'),'optional'=>false,'filter'=>array()),
            2=>array('label'=>app::get('syshdtj')->_('供应商'),'optional'=>false,'filter'=>array('type'=>'供应商')),
            3=>array('label'=>app::get('syshdtj')->_('公众店铺'),'optional'=>false,'filter'=>array('type'=>'公众店铺')),
            4=>array('label'=>app::get('syshdtj')->_('供应商所在县'),'optional'=>false,'filter'=>array('type'=>'供应商所在县')),
            5=>array('label'=>app::get('syshdtj')->_('行政省'),'optional'=>false,'filter'=>array('type'=>'行政省')),
            6=>array('label'=>app::get('syshdtj')->_('行政市'),'optional'=>false,'filter'=>array('type'=>'行政市')),
            7=>array('label'=>app::get('syshdtj')->_('行政县'),'optional'=>false,'filter'=>array('type'=>'行政县')),
            8=>array('label'=>app::get('syshdtj')->_('恒大微购'),'optional'=>false,'filter'=>array('type'=>'恒大微购')),
        );

        foreach($sub_menu as $k=>$v)
        {
            if($v['optional']==false)
            {
                $show_menu[$k] = $v;
                if(is_array($v['filter']))
                {
                    $v['filter'] = array_merge(array(),$v['filter']);
                }
                else
                {
                    $v['filter'] = array();
                }
                $show_menu[$k]['filter'] = $v['filter']?$v['filter']:null;
                if($k==$_GET['view'])
                {
                    $show_menu[$k]['newcount'] = true;
                    $show_menu[$k]['addon'] = $mdl_aftersales->count($v['filter']);
                }
                $show_menu[$k]['href'] = '?app=syshdtj&ctl=admin_month&act=index&view='.($k).(isset($_GET['optional_view'])?'&optional_view='.$_GET['optional_view'].'&view_from=dashboard':'');
            }
            elseif(($_GET['view_from']=='dashboard')&&$k==$_GET['view'])
            {
                $show_menu[$k] = $v;
            }
        }
        return $show_menu;
    }

}
