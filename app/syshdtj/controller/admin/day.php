<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */

class  syshdtj_ctl_admin_day extends desktop_controller{

    public function index()
    {
        return kernel::single('syshdtj_analysis_day',app::get('syshdtj'))->set_params($_POST)->fetch();
    }

}
