<?php
class topd_api_shop_saveNotice{
    public $apiDescription = "保存店铺通知";
    public function getParams()
    {
        $return['params'] = array(
            'notice_id' => ['type'=>'string','valid'=>'','description'=>'通知id','default'=>'','example'=>''],
            'notice_title' => ['type'=>'string','valid'=>'required','description'=>'店铺通知标题','default'=>'','example'=>'http://img0.cn/aa.jpg'],
            'notice_content' => ['type'=>'string','valid'=>'required','description'=>'店铺通知内容','default'=>'','example'=>""],
            'notice_type' => ['type'=>'string','valid'=>'required','description'=>'店铺通知类型','default'=>'','example'=>''],
            'user_id' => ['type'=>'string','valid'=>'','description'=>'店铺id','default'=>'0','example'=>''],
        );
        return $return;
    }
    public function saveNotice($params)
    {
        $shopNoticeLib = kernel::single('topd_data_shopnotice');
        $result = $shopNoticeLib->saveShopNotice($params);
        return $result;
    }
}
