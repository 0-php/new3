<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */

class  syshdtj_ctl_admin_shop extends desktop_controller{

	public function index()
	{
        return $this->finder('syshdtj_mdl_shop',array(
            'title' => app::get('syshdtj')->_('供应商结算'),
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
        $mdl_aftersales = app::get('syshdtj')->model('shop');
        $sub_menu = array(
            0=>array('label'=>app::get('syshdtj')->_('全部'),'optional'=>false,'filter'=>array()),
            1=>array('label'=>app::get('syshdtj')->_('未结算'),'optional'=>false,'filter'=>array('settlement_status'=>'1')),
            2=>array('label'=>app::get('syshdtj')->_('已结算'),'optional'=>false,'filter'=>array('settlement_status'=>'2')),
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
                $show_menu[$k]['href'] = '?app=syshdtj&ctl=admin_shop&act=index&view='.($k).(isset($_GET['optional_view'])?'&optional_view='.$_GET['optional_view'].'&view_from=dashboard':'');
            }
            elseif(($_GET['view_from']=='dashboard')&&$k==$_GET['view'])
            {
                $show_menu[$k] = $v;
            }
        }
        return $show_menu;
    }

    public function confirm($settlementNo) //弹出确定框
    {
        $pagedata['settlement_no'] = $settlementNo;
        return $this->page('syshdtj/admin/confirm.html', $pagedata);
    }

    public function doConfirm()  //确定结算操作
    {
        $this->begin("?app=syshdtj&ctl=admin_topd&act=index");
        $settlementNo = input::get('settlement_no');
        $status = input::get('settlement_status');
        try
        {

            kernel::single('syshdtj_settlement')->doConfirm($settlementNo, $status);
            $this->adminlog("确认结算单[分类ID:{$settlementNo}]", 1);
        }
        catch(Exception $e)
        {
            $this->adminlog("确认结算单[分类ID:{$settlementNo}]", 0);
            $msg = $e->getMessage();
            $this->end(false,$msg);
        }
        $this->end(true);
    }

}
