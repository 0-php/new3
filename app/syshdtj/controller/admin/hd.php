<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */

class  syshdtj_ctl_admin_hd extends desktop_controller{

	public function index()
	{
        return $this->finder('syshdtj_mdl_hdvg',array(
            'title' => app::get('syshdtj')->_('恒大微购结算'),
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
}
