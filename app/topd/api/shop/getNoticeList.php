<?php
class topd_api_shop_getNoticeList{
    public $apiDescription = "获取公众店铺通知";
    public function getParams()
    {
        $return['params'] = array(
            'user_id' => ['type'=>'int','valid'=>'required','description'=>'店铺id','default'=>'','example'=>''],
            'notice_type' => ['type'=>'string','valid'=>'','description'=>'通知类型','default'=>'','example'=>''],
            'page_no' => ['type'=>'int','valid'=>'', 'default'=>'', 'example'=>'', 'description'=>'分页当前页数,默认为1','default'=>'','example'=>''],
            'page_size' => ['type'=>'int','valid'=>'', 'default'=>'', 'example'=>'', 'description'=>'每页数据条数,默认20条','default'=>'','example'=>''],
            'fields'=> ['type'=>'field_list','valid'=>'', 'default'=>'', 'example'=>'', 'description'=>'需要的字段','default'=>'','example'=>''],
            'orderBy' => ['type'=>'string','valid'=>'', 'default'=>'', 'example'=>'', 'description'=>'排序','default'=>'','example'=>''],
        );
        return $return;
    }
    public function getNoticeList($params)
    {
        $shopNoticeLib = kernel::single('topd_data_shopnotice');
        $params['user_id'] = array($params['user_id'],0);
        
        $result = $shopNoticeLib->getNoticeList($params);
        return $result;
    }
}
