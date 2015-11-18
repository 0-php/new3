<?php

/**
 * @brief 结算处理
 */
class  syshdtj_ctl_settlement extends desktop_controller{
    public function confirm($settlementNo,$model) //弹出确定框
    {
        $pagedata['settlement_no'] = $settlementNo;
        $pagedata['model'] = $model;
        return $this->page('syshdtj/admin/confirm.html', $pagedata);
    }

    public function doConfirm()  //确定结算操作
    {
        $this->begin("?app=syshdtj&ctl=admin_topd&act=index");
        $settlementNo = input::get('settlement_no');
        $status = input::get('settlement_status');
        $model = input::get('model');
        try
        {

            kernel::single('syshdtj_settlement')->doConfirm($settlementNo, $status, $model);
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
