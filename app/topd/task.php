<?php
/**
 * 恒大微购
 * Ric
 */

class topd_task{

    public function post_install($options)
    {
        kernel::single('base_initial', 'topd')->init();
        pamAccount::registerAuthType('topd','member',app::get('topd')->_('公众店铺'));
    }

    public function post_uninstall()
    {
        pamAccount::unregisterAuthType('topd');
    }

    public function post_update($dbver)
    {
        if($dbver['dbver'] > 0.1)
        {

        }
    }

}

