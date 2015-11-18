<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */
 

$setting = array(
    'base.site_params_separator'=>array('type'=>SET_T_ENUM, 'default'=>'-', 'required'=>true, 'options'=>array('-'=>'-'), 'desc'=>app::get('site')->_('URL参数分隔符')),
    'base.site_page_cache'=>array('type'=>SET_T_BOOL, 'default'=>'true', 'required'=>true, 'desc'=>app::get('site')->_('启用全页缓存')),
    'site.name'=>array('type'=>SET_T_STR,'vtype'=>'maxLength', 'default'=>app::get('site')->_('点此设置您商店的名称'), 'desc'=>app::get('site')->_('站点名称'),'javascript'=>'validatorMap.set("maxLength",["最大长度32个字",function(el,v){return v.length < 33;}]);'),
    'page.default_title'=>array('type'=>SET_T_STR, 'default'=>'', 'desc'=>app::get('site')->_('网页默认标题')),
    'page.default_keywords'=>array('type'=>SET_T_STR, 'default'=>'', 'desc'=>app::get('site')->_('网页默认关键字')),
    'page.default_description'=>array('type'=>SET_T_TXT, 'default'=>'', 'desc'=>app::get('site')->_('网页默认简介')),
    'system.foot_edit' => array('type'=>SET_T_HTML, 'desc'=>app::get('site')->_('网页底部信息'), 'default'=>'<div class="theme-footer"> 
    <p><b style="color:red;">'.app::get('site')->_('修改本区域内容，请到').' '.app::get('site')->_('商店管理后台').' &gt;&gt; '.app::get('site')->_('站点').' &gt;&gt; '.app::get('site')->_('站点配置').' '.app::get('site')->_('进行编辑').'</b></p>
    <p>© 2013 All rights reserved.</p>
    <p>'.app::get('site')->_('本商店顾客个人信息将不会被泄漏给其他任何机构和个人').'<br/>'.app::get('site')->_('本商店logo和图片都已经申请保护，未经授权不得使用').'<br/>'.app::get('site')->_('有任何购物问题请联系我们在线客服 | 电话：800-800-8000 | 工作时间：周一至周五 8:00－18:00').' </p>
    </div>'),
    'system.site_icp'=>array('type'=>SET_T_STR, 'default'=>'', 'desc'=>app::get('site')->_('备案号')),
    'base.shop_topd'=>array('type'=>SET_T_STR, 'default'=>'0', 'desc'=>app::get('site')->_('公众店铺提成（占‘销售价-成本价’后的百分比）')),
    'base.shop_gysszx'=>array('type'=>SET_T_STR, 'default'=>'0', 'desc'=>app::get('site')->_('供应商所在县提成（占除分销提成外的百分比）')),
    'base.shop_xzsheng'=>array('type'=>SET_T_STR, 'default'=>'0', 'desc'=>app::get('site')->_('行政省提成（占除分销提成外的百分比）')),
    'base.shop_xzshi'=>array('type'=>SET_T_STR, 'default'=>'0', 'desc'=>app::get('site')->_('行政市提成（占除分销提成外的百分比）')),
    'base.shop_xzxian'=>array('type'=>SET_T_STR, 'default'=>'0', 'desc'=>app::get('site')->_('行政县提成（占除分销提成外的百分比）')),
    'base.shop_zxs'=>array('type'=>SET_T_STR, 'default'=>'0', 'desc'=>app::get('site')->_('直辖市提成（和行政省、市、县对立）')),
    'base.shop_zxx'=>array('type'=>SET_T_STR, 'default'=>'0', 'desc'=>app::get('site')->_('直辖市中的区提成（和行政省、市、县对立）')),
    'base.topd_topd'=>array('type'=>SET_T_STR, 'default'=>'0', 'desc'=>app::get('site')->_('公众店铺推荐开店提成')),
    'base.topd_xzsheng'=>array('type'=>SET_T_STR, 'default'=>'0', 'desc'=>app::get('site')->_('行政省提成（占除分销提成外的百分比）')),
    'base.topd_xzshi'=>array('type'=>SET_T_STR, 'default'=>'0', 'desc'=>app::get('site')->_('行政市提成（占除分销提成外的百分比）')),
    'base.topd_xzxian'=>array('type'=>SET_T_STR, 'default'=>'0', 'desc'=>app::get('site')->_('行政县提成（占除分销提成外的百分比）')),
    'base.topd_zxs'=>array('type'=>SET_T_STR, 'default'=>'0', 'desc'=>app::get('site')->_('直辖市提成（和行政省、市、县对立）')),
    'base.topd_zxx'=>array('type'=>SET_T_STR, 'default'=>'0', 'desc'=>app::get('site')->_('直辖市中的区提成（和行政省、市、县对立）')),
    'base.concert_topd'=>array('type'=>SET_T_STR, 'default'=>'0', 'desc'=>app::get('site')->_('公众店铺提成（占‘销售价-成本价’后的百分比）')),
    'base.concert_gysszx'=>array('type'=>SET_T_STR, 'default'=>'0', 'desc'=>app::get('site')->_('演唱会举办所在县提成（占除分销提成外的百分比）')),
    'base.concert_xzsheng'=>array('type'=>SET_T_STR, 'default'=>'0', 'desc'=>app::get('site')->_('行政省提成（占除分销提成外的百分比）')),
    'base.concert_xzshi'=>array('type'=>SET_T_STR, 'default'=>'0', 'desc'=>app::get('site')->_('行政市提成（占除分销提成外的百分比）')),
    'base.concert_xzxian'=>array('type'=>SET_T_STR, 'default'=>'0', 'desc'=>app::get('site')->_('行政县提成（占除分销提成外的百分比）')),
    'base.concert_zxs'=>array('type'=>SET_T_STR, 'default'=>'0', 'desc'=>app::get('site')->_('直辖市提成（和行政省、市、县对立）')),
    'base.concert_zxx'=>array('type'=>SET_T_STR, 'default'=>'0', 'desc'=>app::get('site')->_('直辖市中的区提成（和行政省、市、县对立）')),
);
