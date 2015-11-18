<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */

class  syshdtj_ctl_admin_hdvg extends desktop_controller{

	public function index()
	{
        return $this->finder('syshdtj_mdl_order',array(
            'use_buildin_filter'=>true,
            'use_view_tab'=>true,
            'title' => app::get('syshdtj')->_('详细报表'),
            'use_buildin_delete'=>false,
            'use_buildin_set_tag'=>true,
            'use_buildin_tagedit'=>true,
            'use_buildin_set_tag'=>true,
            'use_buildin_export'=>true,
            'allow_detail_popup'=>true,
        ));
    }

    /**
     * 桌面订单相信汇总显示
     * @param null
     * @return null
     */
    public function _views()
    {
        $mdl_aftersales = app::get('syshdtj')->model('order');
        $sub_menu = array(
            0=>array('label'=>app::get('syshdtj')->_('全部'),'optional'=>false,'filter'=>array()),
/*            2=>array('label'=>app::get('syshdtj')->_('已日结'),'optional'=>false,'filter'=>array('status'=>'succ')),
            3=>array('label'=>app::get('syshdtj')->_('未日结'),'optional'=>false,'filter'=>array('status'=>'cancel')),  状态另取新表了*/
            4=>array('label'=>app::get('syshdtj')->_('产品订单'),'optional'=>false,'filter'=>array('order_from'=>'产品订单')),
            5=>array('label'=>app::get('syshdtj')->_('公众店铺开店'),'optional'=>false,'filter'=>array('order_from'=>'店铺开店')),
            6=>array('label'=>app::get('syshdtj')->_('演唱会门票'),'optional'=>false,'filter'=>array('order_from'=>'演唱门票')),
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
                $show_menu[$k]['href'] = '?app=syshdtj&ctl=admin_hdvg&act=index&view='.($k).(isset($_GET['optional_view'])?'&optional_view='.$_GET['optional_view'].'&view_from=dashboard':'');
            }
            elseif(($_GET['view_from']=='dashboard')&&$k==$_GET['view'])
            {
                $show_menu[$k] = $v;
            }
        }
        return $show_menu;
    }

}
