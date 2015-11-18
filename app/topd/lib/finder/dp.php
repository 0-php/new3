<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */

class topd_finder_dp{
    public $column_edit = '操作';
    public $column_edit_order = 2;
    public $column_edit_width = 200;

    public function column_edit(&$colList, $list) {
        foreach($list as $k=>$row)
        {
            $colList[$k] = $this->_column_edit($row);
        }
    }

    public function _column_edit($row)
    {
        $return = '';
        if($row['fenxiao_zt'] == 'active')
        {
            //免费开店
            $url = '?app=topd&ctl=admin_shop&act=mffenxiao&finder_id='.$_GET['_finder']['finder_id'].'&p[0]='.$row['user_id'];
            $title = app::get('topd')->_('免费开通');
            $target = 'dialog::  {title:\''.app::get('sysdistribution')->_('公众店铺免费开店').'\', width:500, height:400}';
            $return .= '<a href="' . $url . '" target="' . $target . '">' . $title . '</a> | ';
        }

        if($row['fenxiao_zt'] == 'successful')
        {
            //审核通过
            $url = '?app=topd&ctl=admin_shop&act=openfenxiao&finder_id='.$_GET['_finder']['finder_id'].'&p[0]='.$row['user_id'];
            $title = app::get('topd')->_('通过');
            $target = 'dialog::  {title:\''.app::get('sysdistribution')->_('公众店铺审核').'\', width:500, height:400}';
            $return .= '<a href="' . $url . '" target="' . $target . '">' . $title . '</a> | ';
            //审核驳回
            $url = '?app=topd&ctl=admin_shop&act=bhfenxiao&finder_id='.$_GET['_finder']['finder_id'].'&p[0]='.$row['user_id'];
            $title = app::get('topd')->_('驳回');
            $return .= '<a href="' . $url . '">' . $title . '</a>';
        }

        if($row['fenxiao_zt'] == 'finish')
        {
            //店铺暂停
            $url = '?app=topd&ctl=admin_shop&act=ztfenxiao&finder_id='.$_GET['_finder']['finder_id'].'&p[0]='.$row['user_id'];
            $title = app::get('topd')->_('暂停');
            $return .= '<a href="' . $url . '">' . $title . '</a>';
        }

        if($row['fenxiao_zt'] == 'stop')
        {
            //店铺恢复
            $url = '?app=topd&ctl=admin_shop&act=hffenxiao&finder_id='.$_GET['_finder']['finder_id'].'&p[0]='.$row['user_id'];
            $title = app::get('topd')->_('恢复');
            $return .= '<a href="' . $url . '">' . $title . '</a>';
        }

        $url = '?app=topd&ctl=admin_shopnotice&act=addNotice&finder_id='.$_GET['_finder']['finder_id'].'&user_id='.$row['user_id'];
        $target = 'dialog::  {title:\''.app::get('sysshop')->_('').'\', width:500, height:400}';
        $title = app::get('topd')->_('添加店铺通知');
        $return .= ' |<a href="' . $url . '" target="' . $target . '">' . $title . '</a>';

        return $return;
    }
}
