<?php
class syspromotion_ctl_admin_hdvip extends desktop_controller{

    public function index()
    {
        return $this->finder('syspromotion_mdl_hdvip',array(
            'title' => app::get('syspromotion')->_('恒大微购折扣卡系列列表'),
            'use_buildin_delete' => false,
            'actions' => array(

            ),
        ));
    }

}