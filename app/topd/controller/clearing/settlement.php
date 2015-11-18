<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */

class topd_ctl_clearing_settlement extends topd_controller
{
    /**
     * 结算汇总
     * @return
     */
    public function index()
    {
        $this->contentHeaderTitle = app::get('topd')->_('收益结算汇总');
        $filter['user_id'] = $this->userInfo['userId'];
        $postSend = input::get();

        if($postSend['timearea'])
        {
            $pagedata['timearea'] = $postSend['timearea'];
            $timeArray = explode('-', $postSend['timearea']);
            $filter['settlement_time|than']  = strtotime($timeArray[0]);
            $filter['settlement_time|lthan'] = strtotime($timeArray[1]);
        }
        else
        {
            $filter['settlement_time|than']  = strtotime(date('Y-m-01 00:00:00', strtotime('-1 month')));
            $filter['settlement_time|lthan'] = strtotime(date('Y-m-t  23:59:59', strtotime('-1 month')));
            $pagedata['timearea'] = date('Y/m/01', strtotime('-1 month')).'-'.date('Y/m/t', strtotime('-1 month'));
        }

        if($postSend['settlement_type'])
        {
            $filter['settlement_status'] = $postSend['settlement_type'];
            $pagedata['settlement_type'] = $postSend['settlement_type'];
        }
        //处理翻页数据
        $pagedata['page']   = $page      = $postSend['page'] ? $postSend['page'] : 1;
        $pagedata['limits'] = $pageLimit = 10;
        $objMdlSettlement = app::get('syshdtj')->model('pd');
        $pagedata['settlement_list'] = $objMdlSettlement->getList('*', $filter,($page-1)*$pageLimit,$pageLimit,'settlement_time desc');
        $count = $objMdlSettlement->count($filter);
        $postSend['token'] = time();
        if($count>0)
        {
            $total = ceil($count/$pageLimit);
        }
        $pagedata['pagers'] = array(
            'link'=>url::action('topd_ctl_clearing_settlement@index',$postSend),
            'current'=>$page,
            'total'=>$total,
            'token'=>$postSend['token'],
        );

        return $this->page('topd/clearing/settlement.html', $pagedata);
    }

}
