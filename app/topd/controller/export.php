<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */

class topd_ctl_export extends topd_controller{

    public function view()
    {
        //导出方式 直接导出还是通过队列导出
        $pagedata['check_policy'] = 'download';

        $filetype = array(
            'csv'=>'.csv',
            'xls'=>'.xls',
        );

        $pagedata['model'] = input::get('model');
        $pagedata['app'] = input::get('app');

        $supportType = input::get('supportType');
        //支持导出类型
        if( $supportType && $filetype[$supportType] )
        {
            $pagedata['export_type'] = array($supportType=>$filetype[$supportType]);
        }
        else
        {
            $pagedata['export_type'] = $filetype;
        }
        return view::make('topd/export/export.html', $pagedata);
    }

    public function export()
    {
        //导出
        if( input::get('filter') )
        {
            $filter = json_decode(input::get('filter'),true);
        }

        $model = input::get('app').'_mdl_'.input::get('model');
        $filter['user_id'] = $this->userInfo['userId'];
        kernel::single('importexport_export')->fileDownload(input::get('filetype'), $model, input::get('name'), $filter);

    }

}
