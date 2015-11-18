<?php

/**
 * @brief 结算处理
 */
class syshdtj_settlement{

    public function doConfirm($settlementNo, $status, $model)
    {
        if($status=='2')
        {
            $status = '2';
        }
        else
        {
            return fase;
        }
        return app::get('syshdtj')->model($model)->update(array('settlement_status'=>$status),array('settlement_no'=>$settlementNo));
    }
}
