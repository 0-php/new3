<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2012 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 *
 * 商家管理中心菜单定义
 */

return array(
    /*
   |--------------------------------------------------------------------------
   | 分销商中心之首页
   |--------------------------------------------------------------------------
    */
    'index' => array(
        'label' => '首页',
        'display' => true,
        'shopIndex' => true,
        'action' => 'topd_ctl_index@index',
        'icon' => 'glyphicon glyphicon-home',
        'menu' => array(
            array('label'=>'首页','display'=>false,'action'=>'topd_ctl_index@index','url'=>'/','method'=>'get'),
        )
    ),

    /*
    |--------------------------------------------------------------------------
    | 分销商中心之商家商品管理
    |--------------------------------------------------------------------------
     */
    'item' => array(
        'label' => '商品管理',
        'display' => true,
        'action'=> 'topd_ctl_goods@add',
        'icon' => 'glyphicon glyphicon-edit',
        'menu' => array(
            array('label'=>'商品管理','display'=>true,'action'=>'topd_ctl_goods@add','url'=>'topd/goods_add.html','method'=>'get'),),
    ),

    /*
    |--------------------------------------------------------------------------
    | 分销商中心之店铺管理
    |--------------------------------------------------------------------------
     */
    'DistributorService' => array(
        'label' => '店铺管理',
        'display' => true,
        'action' => 'topd_ctl_shop_info@shop',
        'icon' => 'glyphicon glyphicon-cloud',
        'menu' => array(
            array('label'=>'店铺信息','display'=>true, 'action'=>'topd_ctl_shop_info@shop','url'=>'shop/info_shop.html','method'=>'get'),
            array('label'=>'店铺设置','display'=>true,'action'=>'topd_ctl_shop_info@xgshop','url'=>'shop/info_xg.html','method'=>'get'),
            array('label'=>'店铺通知','display'=>true,'action'=>'topd_ctl_shop_notice@index','url'=>'shop/shopnotice.html','method'=>'get'),
            array('label'=>'通知详情','display'=>false,'action'=>'topd_ctl_shop_notice@noticeInfo','url'=>'shop/shopnoticeinto.html','method'=>'get'),
            array('label'=>'店铺配置保存','display'=>false,'action'=>'topd_ctl_shop_info@saveshop','url'=>'shop/info_save.html','method'=>'post'),
        )
    ),

    /*
    |--------------------------------------------------------------------------
    | 分销商中心之报表管理
    |--------------------------------------------------------------------------
     */
    'report' => array(
        'label' => '报表',
        'display' => true,
        'action' => 'topd_ctl_sysstat_sysbusiness@index',
        'icon' => 'glyphicon glyphicon-list-alt',
        'menu' => array(
            array('label'=>'收益结算汇总','display'=>true,'action'=>'topd_ctl_clearing_settlement@index','url'=>'shop/settlement.html','method'=>'get'),
            array('label'=>'业务数据分析','display'=>true,'action'=>'topd_ctl_sysstat_sysbusiness@index','url'=>'sysstat/sysbusiness.html','method'=>'get'),
        )
    ),


    /*
    |--------------------------------------------------------------------------
    | 商家管理中心之商家入驻申请
    |--------------------------------------------------------------------------
     */
    'enterapply' => array(
        'label' => '商家入驻',
        'display' => false,
        'action'=>'topd_ctl_enterapply@apply',
        'menu' => array(
            array('label'=>'入驻申请页','display'=>false,'action'=>'topd_ctl_enterapply@apply','url'=>'apply.html','method'=>'get'),
            array('label'=>'入驻申请页保存','display'=>false,'action'=>'topd_ctl_enterapply@saveApply','url'=>'apply/save.html','method'=>'post'),
            array('label'=>'入驻申请更改','display'=>false,'action'=>'topd_ctl_enterapply@updateApply','url'=>'apply/update.html','method'=>'get'),
            array('label'=>'入驻申请查看','display'=>false,'action'=>'topd_ctl_enterapply@checkPlan','url'=>'apply/checkplan.html','method'=>'get'),
            //图片管理
            array('label'=>'图片管理','display'=>true,'action'=>'topd_ctl_shop_info@index','url'=>'image.html','method'=>'get'),
            array('label'=>'根据条件搜索图片,tab切换','display'=>false,'action'=>'topd_ctl_shop_info@search','url'=>'image/search.html','method'=>'post'),
            array('label'=>'删除图片','display'=>false,'action'=>'topd_ctl_shop_info@delImgLink','url'=>'image/delimglink.html','method'=>'post'),
            array('label'=>'修改图片名称','display'=>false,'action'=>'topd_ctl_shop_info@upImgName','url'=>'image/upimgname.html','method'=>'post'),
            array('label'=>'公众店铺使用图片加载modal','display'=>false,'action'=>'topd_ctl_shop_info@loadImageModal','url'=>'image/loadimagemodal.html','method'=>'get'),
        )
    ),


    /*
    |--------------------------------------------------------------------------
    | 商家管理中心之通用显示数据调用
    |--------------------------------------------------------------------------
     */
    'util' => array(
        'label' => '通用显示数据调用',
        'display' => false,
        'action' => 'topd_ctl_uedtest@index',
        'menu' => array(
            array('label'=>'系统分类','display'=>false, 'action' => 'toputil_ctl_syscat@getChildrenCatList','url'=>'util/syscat.html','method'=>'post'),
            array('label'=>'自然属性','display'=>false, 'action' => 'toputil_ctl_syscat@getNatureProps','url'=>'util/natureprops.html','method'=>'post'),
            array('label'=>'销售属性','display'=>false, 'action' => 'toputil_ctl_syscat@getSpecProps','url'=>'util/specprops.html','method'=>'post'),
            // array('label'=>'验证码','display'=>false, 'action' => 'toputil_ctl_vcode@gen_vcode','url'=>'util/vcode.html','method'=>'get'),
            array('label'=>'上传图片','display'=>false, 'action' => 'toputil_ctl_image@uploadImages','url'=>'util/upload_images.html','method'=>'post'),
            array('label'=>'获取商品的默认图片','display'=>false, 'action' => 'toputil_ctl_image@getItemPic','url'=>'util/item_pic.html','method'=>'get'),
        )
    ),
);
