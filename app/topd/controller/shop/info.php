<?php
class topd_ctl_shop_info extends topd_controller{

    /**
     * @brief 构造函数
     * 分销商
     * @return
     */
    public function __construct()
    {
        parent::__construct();
        //检测是否开店
        if($this->userInfo['fenxiaodp']['fenxiao_zt'] != 'finish'){
            redirect::action('topd_ctl_enterapply@apply')->send();exit;
        }
        $this->limit = 10;
        $this->_check($this->userInfo['fenxiaodp']);
    }

    /**
     * @brief 店铺信息
     * 分销商
     * Ric
     */
    public function shop(){
        //获取店铺信息
        $pagedata['fenxiaodp'] = $this->userInfo['fenxiaodp'];
        //业务信息
        $topd_id = $this->userInfo['userId'];
        $pagedata['pd'] = app::get('syshdtj')->database()->createQueryBuilder()
            ->select("COUNT( CASE WHEN `order_from` = '产品订单' THEN 1 ELSE NULL END ) AS `shop`")
            ->addSelect("COUNT( CASE WHEN `order_from` = '店铺开店' THEN 1 ELSE NULL END ) AS `topd`")
            ->addSelect("COUNT( CASE WHEN `order_from` = '演唱会门票' THEN 1 ELSE NULL END ) AS `yhk`")
            ->addSelect("SUM( CASE WHEN `order_from` = '产品订单' THEN price ELSE 0 END ) AS `shopprice`")
            ->addSelect("SUM( CASE WHEN `order_from` = '店铺开店' THEN price ELSE 0 END ) AS `topdprice`")
            ->addSelect("SUM( CASE WHEN `order_from` = '演唱会门票' THEN price ELSE 0 END ) AS `yhkprice`")
            ->from('syshdtj_order')
            ->where("topd_id= $topd_id")
            ->execute()->fetch();
        return $this->page('topd/shop/myfenxiao.html', $pagedata);
    }

    /**
     * @brief 店铺设置
     * 分销商
     * Ric
     */
    public function xgshop(){
        $pagedata['fenxiaodp'] = $this->userInfo['fenxiaodp'];
        $this->contentHeaderTitle = app::get('topshop')->_('店铺设置');
        return $this->page('topd/shop/setting.html', $pagedata);
    }

    /**
     * @brief 店铺设置保存
     * 分销商
     * Ric
     */
    public function saveshop(){
        $postData = input::get();
        $postData['fenxiaodp']['user_id']= $this->userInfo['userId'];
        try
        {
            if( app::get('topd')->model('dp')->save($postData['fenxiaodp']) )
            {
                $msg = app::get('topd')->_('设置成功');
                $result = 'success';
            }
            else
            {
                $msg = app::get('topd')->_('设置失败');
                $result = 'error';
            }
        }
        catch(Exception $e)
        {
            $msg = $e->getMessage();
        }
        $url = url::action('topd_ctl_shop_info@shop');
        return $this->splash($result,$url,$msg,true);
    }

    /*
     * 图片上传
     * Ric
     * */
    public function loadImageModal()
    {
        $isImageModal = true;
        $result = $this->__getListData($isImageModal);
        $pagedata['imagedata'] = $result['data']['list'];
        $pagedata['pagers'] = $result['pagers'];
        $pagedata['imageModal'] = true;
        return view::make('topd/shop/image/upload.html', $pagedata);
    }

    /*
     * 图片上传 数据处理
     * Ric
     * */
    private function __getListData($isImageModal)
    {
        if( $isImageModal )
        {
            $this->limit = 12;
        }

        $params['page_no'] = input::get('pages',1);
        $params['page_size'] = $this->limit;
        $params['img_type'] = input::get('img_type','item');
        $params['orderBy'] = input::get('orderBy','last_modified desc');
        if( input::get('image_name') )
        {
            $params['image_name'] = input::get('image_name');
        }
        $result['data'] = app::get('topd')->rpcCall('image.topd.list', $params, 'user');
        $result['pagers'] = $this->__pager($params, $result['data']['total'], $isImageModal);
        return $result;
    }

    /*
     * 图片上传 分页
     * Ric
     * */
    private function __pager($filter, $count, $isImageModal)
    {
        $params['img_type'] = $filter['img_type'];
        $params['orderBy'] = $filter['orderBy'];
        if( $filter['image_name'] )
        {
            $params['image_name'] = $filter['image_name'];
        }

        if( $isImageModal )
        {
            $params['imageModal'] = true;
        }

        $params['pages'] = time();
        $total = ceil($count/$this->limit);
        $pagers = array(
            'link'=>url::action('topd_ctl_shop_info@search',$params),
            'current'=>$filter['page_no'],
            'use_app' => 'topd',
            'total'=>$total,
            'token'=>time(),
        );
        return $pagers;
    }

    /*
     * 图片搜索
     * Ric
     * */
    public function search()
    {
        if( input::get('imageModal',false) )
        {
            $isImageModal = true;
            $pagedata['imageModal'] = true;
        }
        $result = $this->__getListData($isImageModal);
        $pagedata['imagedata'] = $result['data']['list'];
        $pagedata['pagers'] = $result['pagers'];
        $pagedata['image_name'] = input::get('image_name',false);
        return view::make('topshop/shop/image/list.html', $pagedata);
    }


    /**
     * 转换输出格式
     * @param 分销信息 $userinfo
     * @return array 分销信息
     */
    private function _check(&$fenxiaodp){
        //类型转换
        if($fenxiaodp['fenxiao_type'] == 'student'){
            $fenxiaodp['fenxiao_type'] ='在校学生';
        } else {
            $fenxiaodp['fenxiao_type'] ='社会人士';
        }
        //区域转换
        list($fenxiaodp['regions'],$fenxiaodp['region_id']) = explode(':', $fenxiaodp['fenxiao_shengid']);
        $fenxiaodp['region_id'] = str_replace('/', ',', $fenxiaodp['region_id']);
        //点击量转换
        list($fenxiaodp['fenxiao_clk0'],$fenxiaodp['fenxiao_clk1'],$fenxiaodp['fenxiao_clk2']) = explode('|', $fenxiaodp['fenxiao_clk']);
        if(strtotime(date('Y-m-d')) >= $fenxiaodp['fenxiao_clk0']){
            $fenxiaodp['fenxiao_clk1'] = 0;
        }
        //开店状态转换
        if($fenxiaodp['fenxiao_zt'] == 'active'){
            $fenxiaodp['fenxiao_zt'] ='未支付';
        } elseif($fenxiaodp['fenxiao_zt'] == 'successful') {
            $fenxiaodp['fenxiao_zt'] ='待审核';
        } elseif($fenxiaodp['fenxiao_zt'] == 'failing') {
            $fenxiaodp['fenxiao_zt'] ='审核驳回';
        } elseif($fenxiaodp['fenxiao_zt'] == 'finish') {
            $fenxiaodp['fenxiao_zt'] ='审核通过';
        } elseif($fenxiaodp['fenxiao_zt'] == 'stop') {
            $fenxiaodp['fenxiao_zt'] ='暂停使用';
        }
        //暂时性模板功能
        $fenxiaodp['options'] = array('模板1','模板2','模板3','模板4');
    }
}