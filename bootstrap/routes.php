<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2012 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */

/*
|--------------------------------------------------------------------------
| api
|--------------------------------------------------------------------------
 */
route::group(array('prefix' => 'api'), function()
{
    route::match(array('GET','POST'),'/api.json',['as'=>'api/api.json','uses'=>'system_ctl_getApiJson@index']);
    route::match(array('GET','POST'),'/', ['uses'=>'base_rpc_server@process']);
});

/*
|--------------------------------------------------------------------------
| PC端消费者平台
|--------------------------------------------------------------------------
*/

route::group(array('middleware' => ['topc_middleware_redirectIfFromWap', 'theme_middleware_preview']), function() {
    /*
    |--------------------------------------------------------------------------
    | 会员登录注册相关
    |--------------------------------------------------------------------------
    */
    route::group(array(), function() {
        # 登陆页
        route::get('passport-signin.html', [ 'middleware' => 'topc_middleware_redirectIfAuthenticated',
                                             'uses' => 'topc_ctl_passport@signin' ]);
        # 登陆
        route::post('passport-signin.html', [ 'middleware' => 'topc_middleware_redirectIfAuthenticated',
                                              'uses' => 'topc_ctl_passport@login' ]);
        # 注册页
        route::get('passport-signup.html', ['middleware' => 'topc_middleware_redirectIfAuthenticated',
                                            'uses' => 'topc_ctl_passport@signup' ]);
        # 注册成功页
        route::get('passport-signup-success.html', ['middleware' => 'topc_middleware_authenticate',
                                                    'uses' => 'topc_ctl_passport@signupSuccess']);
        # 注册
        route::post('passport-signup.html', [ 'uses' => 'topc_ctl_passport@create' ]);
        route::post('passport-sendVcode.html', [ 'uses' => 'topc_ctl_passport@sendVcode' ]);
        # 注册验证
        route::post('passport-signupcheck.html', [ 'uses' => 'topc_ctl_passport@checkLoginAccount' ]);
        # 找回密码1
        route::get('passport-findpwd.html', [ 'uses' => 'topc_ctl_passport@findPwd' ]);
        # 找回密码2
        route::post('passport-findpwdtwo.html', [ 'uses' => 'topc_ctl_passport@findPwdTwo' ]);
        route::get('passport-findpwdtwo.html', [ 'uses' => 'topc_ctl_passport@findPwdTwo' ]);
        # 找回密码3
        route::match(array('GET', 'POST'), 'passport-findpwdthree.html', ['uses' => 'topc_ctl_passport@findPwdThree']);
        # #找回密码短信验证码发送
        route::post('passport-findpwdfour.html', [ 'uses' => 'topc_ctl_passport@findPwdFour' ]);
        # 找回密码短信验证码发送
        route::post('passport-sendVcode.html', [ 'uses' => 'topc_ctl_passport@sendVcode' ]);
        # 信任登陆
        # callback
        /*
        route::get('trustlogin-bind.html', [ 'uses' => 'topc_ctl_trustlogin@callBack' ]);
        # 设置新的账号
        route::post('trustlogin.html', [ 'uses' => 'topc_ctl_trustlogin@setLogin' ]);
        # 绑定已有账户
        route::post('trustlogin-binds.html', [ 'uses' => 'topc_ctl_trustlogin@checkLogin' ]);
        # 模拟登陆
        route::get('trustlogin-postlogin.html', [ 'uses' => 'topc_ctl_trustlogin@postLogin' ]);
        */
        route::get('trustlogin-bind.html', [ 'middleware' => 'topc_middleware_redirectIfAuthenticated',
                                             'uses' => 'topc_ctl_trustlogin@callBack' ]);
        route::post('bindDefaultCreateUser.html', [ 'middleware' => 'topc_middleware_redirectIfAuthenticated',
                                                    'uses' => 'topc_ctl_trustlogin@bindDefaultCreateUser' ]);
        route::post('bindExistsUser.html', [  'middleware' => 'topc_middleware_redirectIfAuthenticated',
                                              'uses' => 'topc_ctl_trustlogin@bindExistsUser' ]);
        route::post('bindSignupUser.html', [ 'middleware' => 'topc_middleware_redirectIfAuthenticated',
                                             'uses' => 'topc_ctl_trustlogin@bindSignupUser' ]);

        # 退出
        route::get('passport-logout.html', [ 'middleware' => 'topc_middleware_authenticate',
                                             'uses' => 'topc_ctl_passport@logout' ]);
    });

    /*
    |--------------------------------------------------------------------------
    | 文章相关
    |--------------------------------------------------------------------------
    */
    route::group(array(), function() {

        route::get('content-index.html', [ 'uses' => 'topc_ctl_content@index' ]);
        route::get('content-info.html', [ 'uses' => 'topc_ctl_content@getContentInfo' ]);
    });
    /*
    |--------------------------------------------------------------------------
    | 商品首页详细页相关
    |--------------------------------------------------------------------------
    */
    route::group(array(), function() {
        # 首页
        route::get('/', [ 'as' => 'topc', 'uses' => 'topc_ctl_default@index']);
        //----------
        # 商品收藏
        route::post('member_fav.html', [ 'middleware' => 'topc_middleware_authenticate',
                                         'uses' => 'topc_ctl_collect@ajaxFav' ]);
        route::post('member-collectdel.html', [ 'middleware' => 'topc_middleware_authenticate',
                                                'uses' => 'topc_ctl_collect@ajaxFavDel' ]);
        # 店铺收藏
        route::post('member_favshop.html', [ 'middleware' => 'topc_middleware_authenticate',
                                             'uses' => 'topc_ctl_collect@ajaxFavshop' ]);
        route::post('member-collectshopdel.html', [ 'middleware' => 'topc_middleware_authenticate',
                                                    'uses' => 'topc_ctl_collect@ajaxFavshopDel' ]);
        # 商品列表
        route::get('list.html', [ 'uses' => 'topc_ctl_list@index' ]);
        # 商城一级类目页
        route::get('topics-{cat_id}.html', [ 'uses' => 'topc_ctl_topics@index' ]);
        # 品牌列表
        route::get('brand.html', [ 'uses' => 'topc_ctl_brand@index' ]);

        # 商品详情
        route::get('item.html', ['as' =>'topc.item.detail', 'uses' => 'topc_ctl_item@index' ]);
        #商品详情页，评价列表
        route::get('item-rate.html', [ 'uses' => 'topc_ctl_item@getItemRate' ]);
        route::get('item-rate-list.html', [ 'uses' => 'topc_ctl_item@getItemRateList' ]);

        #商品详情页，促销
        route::get('promotion-item.html', [ 'uses' => 'topc_ctl_promotion@getPromotionItem' ]);
        #商品详情页,到货通知
        route::post('user-item.html', [ 'uses' => 'topc_ctl_memberItem@userNotifyItem' ]);

        #商品详情页，咨询
        route::get('item-gack.html', [ 'uses' => 'topc_ctl_item@getItemConsultation' ]);
        route::get('item-gack-list.html', [ 'uses' => 'topc_ctl_item@getItemConsultationList' ]);
        #提交资讯信息
        route::post('item-gack-add.html', [ 'uses' => 'topc_ctl_item@commitConsultation' ]);
        #活动列表页
        route::get('activity/list.html', [ 'uses' => 'topc_ctl_activity@index' ]);
        route::get('activity/search.html', [ 'uses' => 'topc_ctl_activity@search' ]);
        route::get('activity/item/list.html', [ 'uses' => 'topc_ctl_activity@itemlist' ]);
        route::get('activity/detail.html', [ 'uses' => 'topc_ctl_activity@detail' ]);
        route::get('activity/lv3.html', [ 'uses' => 'topc_ctl_activity@getCatLv3' ]);
    });

    /*
    |--------------------------------------------------------------------------
    | 店铺相关
    |--------------------------------------------------------------------------
    */
    route::group(array(), function() {
        # 店铺首页
        route::get('shopcenter.html', [ 'uses' => 'topc_ctl_shopcenter@index' ]);
        # 店铺前台优惠券列表页
        route::get('shopCouponList.html', [ 'uses' => 'topc_ctl_shopcenter@shopCouponList' ]);
        # 领取优惠券
        route::post('getCoupon.html', [ 'middleware' => 'topc_middleware_authenticate',
                                        'uses' => 'topc_ctl_shopcenter@getCouponCode' ]);
        # 领取优惠券成功页
        route::get('getCouponResult.html', [ 'uses' => 'topc_ctl_shopcenter@getCouponResult' ]);
        route::get('search.html', [ 'uses' => 'topc_ctl_shopcenter@search' ]);

        #搜索
        route::post('search.html', [ 'uses' => 'topc_ctl_search@index' ]);

        #sitemap
        # 列表
        //route::get('sitemaps.html', [ 'uses' => 'site_ctl_sitemaps@catalog' ]);
        # 目录明细
        //route::get('sitemaps/{id}.html', [ 'uses' => 'site_ctl_sitemaps@index' ]);

    });

    /*
    |--------------------------------------------------------------------------
    | 会员中心
    |--------------------------------------------------------------------------
    */
    route::group(array('middleware' => 'topc_middleware_authenticate'), function() {
        # 会员中心首页
        route::get('member-index.html', [ 'as' => 'home', 'uses' => 'topc_ctl_member@index' ]);
        # 会员中心个人资料
        route::get('member-seinfoset.html', [ 'uses' => 'topc_ctl_member@seInfoSet' ]);
        # 会员中心个人资料
        route::post('member-saveinfoset.html', [ 'uses' => 'topc_ctl_member@saveInfoSet' ]);
        # 会员中心信任登陆密码修改
        route::get('member-pwdset.html', [ 'uses' => 'topc_ctl_member@pwdSet' ]);
        # 会员中心信任登陆密码修改
        route::post('member-savepwdset.html', [ 'uses' => 'topc_ctl_member@savePwdSet' ]);
        # 会员中心安全中心
        route::get('member-security.html', [ 'uses' => 'topc_ctl_member@security' ]);
        # 会员中心安全中心密码修改
        route::get('member-modifypwd.html', [ 'uses' => 'topc_ctl_member@modifyPwd' ]);
        # 会员中心安全中心密码修改
        route::post('member-savemodifypwd.html', [ 'uses' => 'topc_ctl_member@saveModifyPwd' ]);
        # 会员中心手机/邮箱绑定
        route::get('member-setuserinfo.html', [ 'uses' => 'topc_ctl_member@verify' ]);
        # 会员中心登录检测
        route::post('member-checkUserLogin.html', [ 'uses' => 'topc_ctl_member@CheckSetInfo' ]);
        route::get('member-setinfoone.html', [ 'uses' => 'topc_ctl_member@setUserInfoOne' ]);# 会员中心手机
        # 会员中心短信验证码发送
        route::post('member-sendVcode.html', [ 'uses' => 'topc_ctl_member@sendVcode' ]);
        # 会员中心个人信息最后保存
        route::post('member-bindMobile.html', [ 'uses' => 'topc_ctl_member@bindMobile' ]);
        route::get('member-bindEmail.html', [ 'uses' => 'topc_ctl_member@bindEmail' ]);
        # 会员中心个人信息最后保存后的跳转
        route::get('member-setinfolast.html', [ 'uses' => 'topc_ctl_member@setUserInfoLast' ]);
        # 会员中心解绑手机邮箱
        route::get('member-unverify.html', [ 'uses' => 'topc_ctl_member@unVerifyOne' ]);
        route::post('member-checkvcode.html', [ 'uses' => 'topc_ctl_member@checkVcode' ]);
        route::get('member-unverifytwo.html', [ 'uses' => 'topc_ctl_member@unVerifyTwo' ]);
        route::post('member-unverifymobile.html', [ 'uses' => 'topc_ctl_member@unVerifyMobile' ]);
        route::get('member-unverifyemail.html', [ 'uses' => 'topc_ctl_member@unVerifyEmail' ]);
        route::get('member-unverifylast.html', [ 'uses' => 'topc_ctl_member@unVerifyLast' ]);
        # 会员中心收货地址管理
        route::get('member-address.html', [ 'uses' => 'topc_ctl_member@address' ]);
        # 会员中心收货地址修改
        route::post('member-updateaddr.html', [ 'uses' => 'topc_ctl_member@ajaxAddrUpdate' ]);
        # 会员中心默认收货地址
        route::post('member-addrdef.html', [ 'uses' => 'topc_ctl_member@ajaxAddrDef' ]);
        # 删除会员中心收货地址
        route::post('member-deladdr.html', [ 'uses' => 'topc_ctl_member@ajaxDelAddr' ]);
        #会员中心收货地址添加
        route::post('member-address.html', [ 'uses' => 'topc_ctl_member@saveAddress' ]);
        # 会员中心店铺收藏
        route::get('member-collectshops.html', [ 'uses' => 'topc_ctl_member@shopsCollect' ]);
        # 会员中心商品收藏
        route::get('member-collectitems.html', [ 'uses' => 'topc_ctl_member@itemsCollect' ]);
        # 会员中心优惠券列表
        route::get('member-coupon.html', [ 'uses' => 'topc_ctl_member_coupon@couponList' ]);

        #会员中心售后申请
        route::get('member-aftersales-apply.html', [ 'uses' => 'topc_ctl_member_aftersales@aftersalesApply' ]);
        route::post('member-aftersales-commit.html', [ 'uses' => 'topc_ctl_member_aftersales@commitAftersalesApply' ]);
        route::get('member-aftersales-list.html', [ 'uses' => 'topc_ctl_member_aftersales@aftersalesList' ]);
        route::get('member-aftersales-detail.html', [ 'uses' => 'topc_ctl_member_aftersales@aftersalesDetail' ]);
        route::get('member-aftersales-godetail.html', [ 'uses' => 'topc_ctl_member_aftersales@goAftersalesDetail' ]);
        route::post('member-aftersales-sendback.html', [ 'uses' => 'topc_ctl_member_aftersales@sendback' ]);
        route::get('trade-aftersales-logistics.html', [ 'uses' => 'topc_ctl_member_aftersales@ajaxLogistics' ]);

        #订单投诉
        route::get('complaints-view.html', [ 'uses' => 'topc_ctl_member_complaints@complaintsView']);
        route::post('complaints-ci.html', [ 'uses' => 'topc_ctl_member_complaints@complaintsCi']);
        route::get('complaints-detail.html', [ 'uses' => 'topc_ctl_member_complaints@detail']);
        route::post('complaints-close.html', [ 'uses' => 'topc_ctl_member_complaints@closeComplaints']);

        #会员中心评价
        route::get('member-rate-add.html',  [ 'uses' => 'topc_ctl_member_rate@createRate' ]);
        route::post('member-rate-add.html', [ 'uses' => 'topc_ctl_member_rate@doCreateRate' ]);
        route::get('member-rate-index.html', [ 'uses' => 'topc_ctl_member_rate@index' ]);
        route::get('member-rate-list.html', [ 'uses' => 'topc_ctl_member_rate@ratelist' ]);
        route::get('member-rate-setAnony.html', [ 'uses' => 'topc_ctl_member_rate@setAnony' ]);
        route::get('member-rate-del.html',  [ 'uses' => 'topc_ctl_member_rate@doDelete' ]);
        route::post('member-rate-update.html', [ 'uses' => 'topc_ctl_member_rate@update' ]);
        route::get('member-rate-edit.html', [ 'uses' => 'topc_ctl_member_rate@edit' ]);

        #会员中心咨询
        route::get('member-gack-index.html', [ 'uses' => 'topc_ctl_member_consultation@index' ]);
        route::post('member-gack-del.html', [ 'uses' => 'topc_ctl_member_consultation@doDelete' ]);

        # 会员中心成长值及极分
        route::get('member-myexp.html', [ 'uses' => 'topc_ctl_member_experience@experience' ]);
        route::get('member-mypoint.html', [ 'uses' => 'topc_ctl_member_point@point' ]);
        route::get('member-mygrade.html', [ 'uses' => 'topc_ctl_member@grade' ]);

        # 会员中心我的订单
        route::get('trade-list.html', [ 'uses' => 'topc_ctl_member_trade@tradeList' ]);
        route::post('logi.html', [ 'uses' => 'topc_ctl_member_trade@ajaxGetLogi' ]);

        # 会员中心订单详情
        route::get('trade-detail.html', [ 'uses' => 'topc_ctl_member_trade@tradeDetail' ]);
        route::get('trade-cancel.html', [ 'uses' => 'topc_ctl_member_trade@ajaxCancelTrade' ]);
        route::get('trade-confirm.html', [ 'uses' => 'topc_ctl_member_trade@ajaxConfirmTrade' ]);
        route::match(array('GET', 'POST'), 'confirm-buyer.html', ['uses' => 'topc_ctl_member_trade@confirmReceipt']);
        route::match(array('GET', 'POST'), 'cancel-buyer.html', ['uses' => 'topc_ctl_member_trade@cancelOrderBuyer']);
    });

    /*
    |--------------------------------------------------------------------------
    | 交易
    |--------------------------------------------------------------------------
    */
    route::group(array('middleware' => 'topc_middleware_authenticate'), function() {
        // 购物车
        route::get('cart.html', [ 'uses' => 'topc_ctl_cart@index' ]);
        #显示购物车
        route::post('cart-add.html', [ 'uses' => 'topc_ctl_cart@add' ]); #加入购物车
        route::post('cart-update.html', [ 'uses' => 'topc_ctl_cart@updateCart' ]); #更新购物车
        route::post('cart-remove.html', [ 'uses' => 'topc_ctl_cart@removeCart' ]); #删除
        route::post('cart.html', [ 'uses' => 'topc_ctl_cart@ajaxBasicCart' ]); #购物车页
        route::post('trade-create.html', [ 'uses' => 'topc_ctl_trade@create' ]); #生成订单
        route::post('cart-total.html', [ 'uses' => 'topc_ctl_cart@total' ]); #统计总金额

        // 订单确认页
        route::get('cart-checkout.html', [ 'uses' => 'topc_ctl_cart@checkout' ]); #立即购买
        route::post('cart-checkout.html', [ 'uses' => 'topc_ctl_cart@checkout' ]); #购物信息结算页
        route::post('cart-saveAddress.html', [ 'uses' => 'topc_ctl_cart@saveAddress' ]); #购物信息结算页
        route::post('cart-addrDialog.html', [ 'uses' => 'topc_ctl_cart@addr_dialog' ]); #收货地址弹框
        route::post('cart-getCoupons.html', [ 'uses' => 'topc_ctl_cart@getCoupons' ]); #获取用户的优惠券
        route::post('cart-useCoupon.html', [ 'uses' => 'topc_ctl_cart@useCoupon' ]); #使用优惠券
        route::post('cart-cancelCoupon.html', [ 'uses' => 'topc_ctl_cart@cancelCoupon' ]); #取消优惠券
        route::post('cart-getDtyList.html', [ 'uses' => 'topc_ctl_cart@getDtyList' ]); #获取指定店铺的配送方式列表

        //获取自提列表
        route::post('trade-ziti.html', [ 'uses' => 'topc_ctl_cart@getZitiList' ]); #生成订单
    });

    /*
    |--------------------------------------------------------------------------
    | 支付中心
    |--------------------------------------------------------------------------
    */
    route::group(array('middleware' => 'topc_middleware_authenticate'), function() {
        #支付中心
        route::get('payment.html', [ 'uses' => 'topc_ctl_paycenter@index' ]);
        route::match(array('GET', 'POST'), 'create.html', ['uses' => 'topc_ctl_paycenter@createPay']);
        route::post('dopayment.html', [ 'uses' => 'topc_ctl_paycenter@dopayment' ]);
        route::get('finish.html', [ 'uses' => 'topc_ctl_paycenter@finish' ]);
    });
 });


/*
|--------------------------------------------------------------------------
| WAP端消费者平台
|--------------------------------------------------------------------------
 */
route::group(array('prefix' => 'wap', 'middleware' => 'theme_middleware_preview'), function() {
    /*
    |--------------------------------------------------------------------------
    | 会员登录注册相关
    |--------------------------------------------------------------------------
    */
    route::group(array(), function() {
        # 登录
        route::get('passport-signin.html', [ 'middleware' => 'topm_middleware_redirectIfAuthenticated',
                                             'uses' => 'topm_ctl_passport@signin' ]);
        route::post('passport-signin.html', [ 'middleware' => 'topm_middleware_redirectIfAuthenticated',
                                              'uses' => 'topm_ctl_passport@login' ]);
        route::post('passport-saveuname.html', [ 'uses' => 'topm_ctl_passport@saveUname' ]); # 保存登陆设置用户名
        # 退出
        route::get('passport-logout.html', [ 'uses' => 'topm_ctl_passport@logout' ]);
        # 注册
        route::get('passport-signup.html', [ 'middleware' => 'topm_middleware_redirectIfAuthenticated',
                                             'uses' => 'topm_ctl_passport@signup' ]);
        route::get('passport-license.html', [ 'uses' => 'topm_ctl_passport@license' ]);
        route::post('passport-signup.html', [ 'uses' => 'topm_ctl_passport@create' ]);
        route::post('passport-signupcheck.html', [ 'uses' => 'topm_ctl_passport@checkLoginAccount' ]);# 注册验证
        # 找回密码
        route::get('passport-findpwd.html', [ 'uses' => 'topm_ctl_passport@findPwd' ]);#找回密码1
        route::post('passport-findpwdtwo.html', [ 'uses' => 'topm_ctl_passport@findPwdTwo' ]);#找回密码2
        route::get('passport-findpwdtwo.html', [ 'uses' => 'topm_ctl_passport@findPwdTwo' ]);
        route::post('passport-findpwdthree.html', [ 'uses' => 'topm_ctl_passport@findPwdThree' ]);#找回密码3
        route::get('passport-findpwdthree.html', [ 'uses' => 'topm_ctl_passport@findPwdThree' ]);#找回密码3
        route::post('passport-sendVcode.html', [ 'uses' => 'topm_ctl_passport@sendVcode' ]);#找回密码短信验证码发送
        route::post('passport-findpwdfour.html', [ 'uses' => 'topm_ctl_passport@findPwdFour' ]);#找回密码4

        # 信任登陆
        route::get('trustlogin-bind.html', [ 'uses' => 'topm_ctl_trustlogin@callBack' ]);
        route::post('bindDefaultCreateUser.html', [ 'uses' => 'topm_ctl_trustlogin@bindDefaultCreateUser' ]);
        route::post('bindExistsUser.html', [ 'uses' => 'topm_ctl_trustlogin@bindExistsUser' ]);
        route::post('bindSignupUser.html', [ 'uses' => 'topm_ctl_trustlogin@bindSignupUser' ]);


        /*
        route::get('trustwaplogin-bind.html', [ 'uses' => 'topm_ctl_trustlogin@callBack' ]);
        route::post('trustwaplogin.html', [ 'uses' => 'topm_ctl_trustlogin@setLogin' ]);
        route::post('trustwaplogin-binds.html', [ 'uses' => 'topm_ctl_trustlogin@checkLogin' ]);
        route::get('trustwaplogin-postlogin.html', [ 'uses' => 'topm_ctl_trustlogin@postLogin' ]);
        */
    });

    /*
    |--------------------------------------------------------------------------
    | 文章相关
    |--------------------------------------------------------------------------
    */
    route::group(array(), function() {

        route::get('node-list.html', [ 'uses' => 'topm_ctl_content@nodeList' ]);
        route::get('content-list.html', [ 'uses' => 'topm_ctl_content@contentList' ]);
        route::get('content-info.html', [ 'uses' => 'topm_ctl_content@getContentInfo' ]);
    });

    /*
    |--------------------------------------------------------------------------
    | 首页,商品详细页,类目相关
    |--------------------------------------------------------------------------
    */
    route::group(array(), function() {
        # 系统分类
        route::get('/', [ 'as' => 'topm', 'uses' => 'topm_ctl_default@index' ]);
        # 切换到手机端
        route::get('to-pc.html', [ 'as' => 'siwtchToPc', 'uses' => 'topm_ctl_default@switchToPc']);
        #商品搜索
        route::post('search.html', [ 'uses' => 'topm_ctl_search@index' ]);
        route::get('search.html', [ 'uses' => 'topm_ctl_shopcenter@search' ]);
        #商品搜索结果页
        route::get('list.html', [ 'uses' => 'topm_ctl_list@index' ]);
        route::get('ajaxitemshow.html', [ 'uses' => 'topm_ctl_list@ajaxItemShow' ]);

        # 一级类目页
        route::get('categroy.html', [ 'uses' => 'topm_ctl_category@index' ]);
        route::get('catlistinfo.html', [ 'uses' => 'topm_ctl_category@catList' ]);#一级下面类目页

        # 商品详情
        route::get('item.html', ['as' => 'topm.item.detail', 'uses' => 'topm_ctl_item@index' ]);
        route::post('item-collect.html', [ 'uses' => 'topm_ctl_collect@ajaxFav' ]);#收藏
        route::post('shop-collect.html', [ 'uses' => 'topm_ctl_collect@ajaxFavshop' ]);#收藏
        route::get('itempic.html', [ 'uses' => 'topm_ctl_item@itemPic' ]);#图文详情
        route::get('itemparams.html', [ 'uses' => 'topm_ctl_item@itemParams' ]);#图文详情
        #商品详情页，评价列表
        route::get('item-rate.html', [ 'uses' => 'topm_ctl_item@getItemRate' ]);
        route::get('item-rate-list.html', [ 'uses' => 'topm_ctl_item@getItemRateList' ]);

        # 店铺收藏删除,商品收藏删除
        route::post('member-collectdel.html', [ 'uses' => 'topm_ctl_collect@ajaxFavDel' ]);
        route::post('member-collectshopdel.html', [ 'uses' => 'topm_ctl_collect@ajaxFavshopDel' ]);
        # 商品列表

        #商品详情页，促销
        route::get('promotion-item.html', [ 'uses' => 'topm_ctl_promotion@getPromotionItem' ]);
        route::get('promotion-itemlist.html', [ 'uses' => 'topm_ctl_promotion@ajaxPromotionItemShow' ]);

        #商品详情页,到货通知
        route::post('user-item.html', [ 'uses' => 'topm_ctl_memberItem@userNotifyItem' ]);
        #活动列表页
        route::get('activity-list.html', [ 'uses' => 'topm_ctl_activity@index' ]);
        route::get('activity-search.html', [ 'uses' => 'topm_ctl_activity@search' ]);
        route::get('activity-data.html', [ 'uses' => 'topm_ctl_activity@datalist' ]);
        route::get('activity-ajax.html', [ 'uses' => 'topm_ctl_activity@ajaxItemShow' ]);
        route::get('activity-detail.html', [ 'uses' => 'topm_ctl_activity@detail' ]);
        route::get('activity-lv3.html', [ 'uses' => 'topm_ctl_activity@getCatLv3' ]);

    });

    /*
    |--------------------------------------------------------------------------
    | 会员中心
    |--------------------------------------------------------------------------
    */
    route::group(array('middleware' => 'topm_middleware_authenticate'), function() {
        # 会员中心
        route::get('member-index.html', [ 'as' => 'wap.home', 'uses' => 'topm_ctl_member@index' ]);
        route::get('member-infoset.html', [ 'uses' => 'topm_ctl_member@userinfoSet' ]);# 会员中心个人资料
        route::post('member-saveinfoset.html', [ 'uses' => 'topm_ctl_member@saveInfoSet' ]);# 会员中心个人资料
        route::get('member-collectshops.html', [ 'uses' => 'topm_ctl_member@shopsCollect' ]);# 会员中心商品收藏
        route::get('member-collectitems.html', [ 'uses' => 'topm_ctl_member@itemsCollect' ]);# 会员中心店铺收藏
        route::get('ajaxtradeshow.html', [ 'uses' => 'topm_ctl_member_trade@ajaxTradeShow' ]);

        # 会员中心安全中心
        route::get('member-security.html', [ 'uses' => 'topm_ctl_member@security' ]);
        route::get('member-modifypwd.html', [ 'uses' => 'topm_ctl_member@modifyPwd' ]);# 会员中心安全中心密码修改
        route::post('member-savemodifypwd.html', [ 'uses' => 'topm_ctl_member@saveModifyPwd' ]);# 会员中心安全中心密码修改

        route::get('member-setuserinfo.html', [ 'uses' => 'topm_ctl_member@verify' ]); # 会员中心手机/邮箱绑定
        route::post('member-checkUserLogin.html', [ 'uses' => 'topm_ctl_member@CheckSetInfo' ]);# 会员中心登录检测
        route::get('member-setinfoone.html', [ 'uses' => 'topm_ctl_member@setUserInfoOne' ]);# 会员中心手机
        route::post('member-sendVcode.html', [ 'uses' => 'topm_ctl_member@sendVcode' ]);# 会员中心短信验证码发送
        route::post('member-saveSetInfo.html', [ 'uses' => 'topm_ctl_member@saveSetInfo' ]); # 会员中心个人信息最后保存
        route::get('member-setinfotwo.html', [ 'uses' => 'topm_ctl_member@setUserInfoTwo' ]);# 会员中心个人信息最后保存后的跳转
        route::get('member-bindEmail.html', [ 'uses' => 'topm_ctl_member@bindEmail' ]);# 邮箱认证
        route::get('member-verifyRoute.html', [ 'uses' => 'topm_ctl_member@verifyRoute' ]);# 安全中心绑定手机页面路由
         # 会员中心解绑手机邮箱
        route::get('member-unverify.html', [ 'uses' => 'topm_ctl_member@unVerifyOne' ]);
        route::post('member-checkvcode.html', [ 'uses' => 'topm_ctl_member@checkVcode' ]);
        route::get('member-unverifytwo.html', [ 'uses' => 'topm_ctl_member@unVerifyTwo' ]);
        route::post('member-unverifymobile.html', [ 'uses' => 'topm_ctl_member@unVerifyMobile' ]);
        route::get('member-unverifyemail.html', [ 'uses' => 'topm_ctl_member@unVerifyEmail' ]);
        route::get('member-unverifylast.html', [ 'uses' => 'topm_ctl_member@unVerifyLast' ]);
        # 户名设置
        route::post('member-updateaccount.html', [ 'uses' => 'topm_ctl_member@saveUserAccount' ]);
        # 会员中心收货地址管理
        route::get('member-address.html', [ 'uses' => 'topm_ctl_member@address' ]);
        route::get('member-updateaddr.html', [ 'uses' => 'topm_ctl_member@addrUpdate' ]);# 会员中心收货地址修改
        route::post('member-addrdef.html', [ 'uses' => 'topm_ctl_member@ajaxAddrDef' ]);# 会员中心默认收货地址
        route::post('member-deladdr.html', [ 'uses' => 'topm_ctl_member@ajaxDelAddr' ]);# 删除会员中心收货地址
        route::post('member-address.html', [ 'uses' => 'topm_ctl_member@saveAddress' ]);#会员中心收货地址添加

        #会员中心评价
        route::get('member-rate-add.html',  [ 'uses' => 'topm_ctl_member_rate@createRate' ]);
        route::post('member-rate-add.html', [ 'uses' => 'topm_ctl_member_rate@doCreateRate' ]);

        # 会员中心优惠券列表
        route::get('member-couponList.html', [ 'uses' => 'topm_ctl_member_coupon@couponList' ]);
        route::get('member-ajaxCouponData.html', [ 'uses' => 'topm_ctl_member_coupon@ajaxCouponData' ]);
        #删除优惠券
        route::post('member-deleteCoupon.html', [ 'uses' => 'topm_ctl_member_coupon@deleteCoupon' ]);
        // 会员中心查看优惠券详情
        route::get('member-couponDetail.html', [ 'uses' => 'topm_ctl_member_coupon@couponDetail' ]);

        #会员中心售后申请
        route::get('member-aftersales-exchange.html', [ 'uses' => 'topm_ctl_member_aftersales@exchange' ]);
        route::get('member-aftersales-apply.html', [ 'uses' => 'topm_ctl_member_aftersales@aftersalesApply' ]);
        route::post('member-aftersales-commit.html', [ 'uses' => 'topm_ctl_member_aftersales@commitAftersalesApply' ]);
        //route::get('member-aftersales-list.html', [ 'uses' => 'topm_ctl_member_aftersales@aftersalesList' ]);
        route::get('member-aftersales-detail.html', [ 'uses' => 'topm_ctl_member_aftersales@aftersalesDetail' ]);
        route::get('member-aftersales-godetail.html', [ 'uses' => 'topm_ctl_member_aftersales@goAftersalesDetail' ]);
        route::post('member-aftersales-sendback.html', [ 'uses' => 'topm_ctl_member_aftersales@sendback' ]);

        #订单投诉
        route::get('complaints-view.html', [ 'uses' => 'topm_ctl_member_complaints@complaintsView']);
        route::post('complaints-ci.html', [ 'uses' => 'topm_ctl_member_complaints@complaintsCi']);
        route::get('complaints-detail.html', [ 'uses' => 'topm_ctl_member_complaints@detail']);
        route::post('complaints-close.html', [ 'uses' => 'topm_ctl_member_complaints@closeComplaints']);
        route::get('complaints-close.html', [ 'uses' => 'topm_ctl_member_complaints@closeView']);


        #会员积分成长值
        route::get('myexp.html', [ 'uses' => 'topm_ctl_member_experience@experience' ]);
        route::get('mypoint.html', [ 'uses' => 'topm_ctl_member_point@point' ]);
        route::get('exp-detail.html', [ 'uses' => 'topm_ctl_member_experience@grade' ]);

        # 会员中心我的订单  订单详情
        route::get('tradelist.html', [ 'uses' => 'topm_ctl_member_trade@index']);  #会员中心订单列表）
        route::get('trade-list.html', [ 'uses' => 'topm_ctl_member_trade@tradeList']);  #会员中心订单列表(tab)
        route::get('trade-detail.html', [ 'uses' => 'topm_ctl_member_trade@detail' ]);
        route::get('cancel.html', [ 'uses' => 'topm_ctl_member_trade@cancel' ]);
        route::get('confirm.html', [ 'uses' => 'topm_ctl_member_trade@confirm' ]);
        route::post('logim.html', [ 'uses' => 'topm_ctl_member_trade@ajaxGetLogi' ]);
        route::match(array('GET', 'POST'), 'confirm-buyer.html', ['uses' => 'topm_ctl_member_trade@confirmReceipt']);
        route::match(array('GET', 'POST'), 'cancel-buyer.html', ['uses' => 'topm_ctl_member_trade@cancelBuyer']);
    });

    /*
    |--------------------------------------------------------------------------
    | 店铺相关
    |--------------------------------------------------------------------------
    */
    route::group(array(), function() {
        # 店铺首页
        route::get('shopcenter.html', [ 'uses' => 'topm_ctl_shopcenter@index' ]);
        /*route::get('getTagsInfo.html', [ 'uses' => 'topm_ctl_shopcenter@getTagsInfo' ]);
          route::get('ajaxTagsShow.html', [ 'uses' => 'topm_ctl_shopcenter@ajaxTagsShow' ]);*/
        # 店铺前台优惠券列表页
        route::get('shopCouponList.html', [ 'uses' => 'topm_ctl_shopcenter@shopCouponList' ]);
        # 领取优惠券
        route::post('getCoupon.html', [ 'uses' => 'topm_ctl_shopcenter@getCouponCode' ]);
        # 领取优惠券成功页
        route::get('getCouponResult.html', [ 'uses' => 'topm_ctl_shopcenter@getCouponResult' ]);
        route::get('ajaxshopitemshow.html', [ 'uses' => 'topm_ctl_shopcenter@ajaxItemShow' ]);
        route::get('vcode.html', [ 'as' => 'toputil.vcode', 'uses' => 'toputil_ctl_vcode@gen_vcode' ]);
    });

    /*
    |--------------------------------------------------------------------------
    | 交易
    |--------------------------------------------------------------------------
    */
    route::group(array(), function() {
        //购物车
        route::post('cart-add.html', [ 'uses' => 'topm_ctl_cart@add' ]); #加入购物车
        route::get('cart.html',['uses' => 'topm_ctl_cart@index']);   #购物车页
        route::post('cart-update.html',['uses' => 'topm_ctl_cart@updateCart']);  #更新购物车
        route::post('cart.html',['uses' => 'topm_ctl_cart@ajaxBasicCart']);    #购物车页
        route::post('cart-remove.html', [ 'uses' => 'topm_ctl_cart@removeCart' ]); #删除
        route::post('cart-total.html', [ 'uses' => 'topm_ctl_cart@total' ]); #统计总金额

        // 订单确认页
        route::post('cart-checkout.html',['uses' => 'topm_ctl_cart@checkout']);  #立即购买
        route::get('cart-checkout.html',['uses' => 'topm_ctl_cart@checkout']);  #购物信息结算页
        route::post('cart-saveAddress.html', [ 'uses' => 'topm_ctl_cart@saveAddress' ]); #购物信息结算页
        route::get('cart-editaddr.html', [ 'uses' => 'topm_ctl_cart@editAddr' ]); #收货地址弹框
        route::get('cart-addrList.html', [ 'uses' => 'topm_ctl_cart@getAddrList' ]); #收货地址弹框
        route::get('cart-payTypeList.html', [ 'uses' => 'topm_ctl_cart@getPayTypeList' ]); #收货地址弹框
        route::get('deladdr.html', [ 'uses' => 'topm_ctl_cart@delAddr' ]);# 删除会员中心收货地址
        route::post('cart-useCoupon.html', [ 'uses' => 'topm_ctl_cart@useCoupon' ]); #使用优惠券
        route::post('cart-cancelCoupon.html', [ 'uses' => 'topm_ctl_cart@cancelCoupon' ]); #取消优惠券

        //订单处理
        route::post('trade-create.html', [ 'uses' => 'topm_ctl_trade@create' ]); #生成订单

        #支付中心
        route::get('payment.html', [ 'uses' => 'topm_ctl_paycenter@index' ]);
        route::match(array('GET', 'POST'), 'create.html', ['uses' => 'topm_ctl_paycenter@createPay']);
        route::post('dopayment.html', [ 'uses' => 'topm_ctl_paycenter@dopayment' ]);
        route::get('finish.html', [ 'uses' => 'topm_ctl_paycenter@finish' ]);

        //微信的数据做转发。。。
        route::match(array('GET', 'POST', 'PUT', 'DELETE'), 'wxpayjsapi.html', ['uses' => 'topm_ctl_wechat@wxpayjsapi']);
    });
});

/*
|---------------------------------------------------------------------------
|代理商
|---------------------------------------------------------------------------
*/
route::group(array('prefix' => 'agent'), function(){
    #首页
    route::get('/', ['as' => 'agent.home' , 'uses' => 'agent_ctl_index@index']);
    route::get('passport/signin.html', ['as' => 'agent.signin', 'uses' => 'agent_ctl_passport@signin']);
    route::post('passport/signin.html', [ 'as' => 'agent.postsignin', 'uses' => 'agent_ctl_passport@login' ]);
    //route::get('login.html', ['as' => 'agent.lo', 'uses' => 'agent_ctl_passport@login']);
    route::get('passport/update.html', [ 'as' => 'agent.update', 'uses' => 'agent_ctl_passport@update' ]);
    route::post('passport/update.html', [ 'as' => 'agent.postupdatepwd', 'uses' => 'agent_ctl_passport@updatepwd' ]);
    route::get('passport/logout.html', [ 'as' => 'agent.logout', 'uses' => 'agent_ctl_passport@logout' ]);

    //分销商统计
    route::get('member/distributorList.html', [ 'as' => 'agent.logout', 'uses' => 'agent_ctl_member_distributorList@index' ]);
     route::post('member/distributorList/searchUserName4.html', ['as' => 'agent.user', 'uses' => 'agent_ctl_member_distributorList@searchRegtime']);
     route::post('member/distributorList/searchUserName2.html', ['as' => 'agent.user', 'uses' => 'agent_ctl_member_distributorList@searchArea']);
    route::post('member/distributorList/searchUserName5.html', ['as' => 'agent.user', 'uses' => 'agent_ctl_member_distributorList@searchAreaThree']);
   
    //根据会员等级筛选信息
    route::post('member/distributorList/searchUserName3.html', ['as' => 'agent.user', 'uses' => 'agent_ctl_member_distributorList@searchGradeId']);
    //根据会员注册时间进行筛选信息
    route::post('member/distributorList/searchUserName4.html', ['as' => 'agent.user', 'uses' => 'agent_ctl_member_distributorList@searchRegtime']);

    //会员管理
    route::get('member/list.html', [ 'as' => 'agent.logout', 'uses' => 'agent_ctl_member_list@index' ]);
    //根据用户名筛选信息
    route::post('member/searchUserName1.html', ['as' => 'agent.user', 'uses' => 'agent_ctl_member_list@searchName']);
    //根据区域筛选信息
    route::post('member/searchUserName2.html', ['as' => 'agent.user', 'uses' => 'agent_ctl_member_list@searchArea']);
    route::post('member/searchUserName5.html', ['as' => 'agent.user', 'uses' => 'agent_ctl_member_list@searchAreaSec']);
    route::post('member/searchAreaThree.html', ['as' => 'agent.user', 'uses' => 'agent_ctl_member_list@searchAreaThree']);
    
    //根据会员等级筛选信息
    route::post('member/searchUserName3.html', ['as' => 'agent.user', 'uses' => 'agent_ctl_member_list@searchGradeId']);
    //根据会员注册时间进行筛选信息
    route::post('member/searchUserName4.html', ['as' => 'agent.user', 'uses' => 'agent_ctl_member_list@searchRegtime']);
    
    //报表管理
     //订单收入
    route::get('orderIncome.html', [ 'as' => 'agent.logout', 'uses' => 'agent_ctl_orderIncome@index' ]);
    route::post('orderAjaxOrder.html', [ 'as' => 'agent.logout', 'uses' => 'agent_ctl_orderIncome@ajaxOrder' ]);
    route::post('orderSearchRegtime.html', [ 'as' => 'agent.logout', 'uses' => 'agent_ctl_orderIncome@searchRegtime' ]);
    route::post('orderSearchRegtimeAll.html', [ 'as' => 'agent.logout', 'uses' => 'agent_ctl_orderIncome@searchRegtimeAll' ]);     


    //公众店铺收入
    route::get('publicIncome.html', [ 'as' => 'agent.logout', 'uses' => 'agent_ctl_publicIncome@index' ]);
    route::post('publicajaxOrder.html', [ 'as' => 'agent.logout', 'uses' => 'agent_ctl_publicIncome@ajaxOrder' ]);
    route::post('publicsearchRegtime.html', [ 'as' => 'agent.logout', 'uses' => 'agent_ctl_publicIncome@searchRegtime' ]);
    route::post('publicsearchRegtimeAll.html', [ 'as' => 'agent.logout', 'uses' => 'agent_ctl_publicIncome@searchRegtimeAll' ]);  
    
    //演出会门票收入
    route::get('ticketIncome.html', [ 'as' => 'agent.logout', 'uses' => 'agent_ctl_ticketIncome@index' ]);
    route::post('ticketIncome1.html', [ 'as' => 'agent.logout', 'uses' => 'agent_ctl_ticketIncome@ajaxOrder' ]);
    route::post('ticketIncome11.html', [ 'as' => 'agent.logout', 'uses' => 'agent_ctl_ticketIncome@searchRegtime' ]);
    route::post('ticketIncome12.html', [ 'as' => 'agent.logout', 'uses' => 'agent_ctl_ticketIncome@searchRegtimeAll' ]);     

    //代理商信息
    route::get('agentMes.html', [ 'as' => 'agent.logout', 'uses' => 'agent_ctl_mes@index' ]);

    //会员信息
      // route::get('member/list.html', [ 'as' => 'agent.list', 'uses' => 'agent_ctl_member_list@gradeIdSearch' ]);

});


/*
|--------------------------------------------------------------------------
| 商家管理中心
|--------------------------------------------------------------------------
*/

route::group(array('prefix' => 'shop','middleware' => 'topshop_middleware_permission'), function() {

    # 首页
    route::get('/', [ 'as' => 'topshop.home', 'uses' => 'topshop_ctl_index@index' ]);

    route::get('nopermission.html', [ 'as' => 'topshop.nopermission', 'uses' => 'topshop_ctl_index@nopermission' ]);
    route::get('onlySelfManagement.html', [ 'as' => 'topshop.onlySelfManagement', 'uses' => 'topshop_ctl_index@onlySelfManagement' ]);

    # 登录
    route::get('passport/signin.html', [ 'as' => 'topshop.signin', 'uses' => 'topshop_ctl_passport@signin' ]);
    route::post('passport/signin.html', [ 'as' => 'topshop.postsignin', 'uses' => 'topshop_ctl_passport@login' ]);
    # 注册
    route::get('passport/signup.html', [ 'as' => 'topshop.signup', 'uses' => 'topshop_ctl_passport@signup' ]);
    route::post('passport/signup.html', [ 'as' => 'topshop.postsignup', 'uses' => 'topshop_ctl_passport@create' ]);
    # 退出
    route::get('passport/logout.html', [ 'as' => 'topshop.logout', 'uses' => 'topshop_ctl_passport@logout' ]);
    # 账户是否存在
    route::get('passport/isexists.html', [ 'as' => 'topshop.userexists', 'uses' => 'topshop_ctl_passport@isExists' ]);
    # 商家修改密码
    route::get('passport/update.html', [ 'as' => 'topshop.update', 'uses' => 'topshop_ctl_passport@update' ]);
    route::post('passport/update.html', [ 'as' => 'topshop.postupdatepwd', 'uses' => 'topshop_ctl_passport@updatepwd' ]);
    #商家信息查看
    route::get('enterapply/applyInfo.html', [ 'as' => 'topshop.getApplyInfo', 'uses' => 'topshop_ctl_enterapply@getApplyInfo' ]);
    #促销管理
    #满减
    route::post('promotion/fullminuslist.html', [ 'as' => 'topshop.promotion.fullminuslist', 'uses' => 'topshop_ctl_promotion_fullminus@searchItem' ]);
    route::post('promotion/fullminusbrand.html', [ 'as' => 'topshop.promotion.fullminus', 'uses' => 'topshop_ctl_promotion_fullminus@getBrandList' ]);
    #满折
    route::post('promotion/fulldiscountlist.html', [ 'as' => 'topshop.promotion.fulldiscountlist', 'uses' => 'topshop_ctl_promotion_fulldiscount@searchItem' ]);
    route::post('promotion/fulldiscountbrand.html', [ 'as' => 'topshop.promotion.fulldiscount', 'uses' => 'topshop_ctl_promotion_fulldiscount@getBrandList' ]);
    #优惠券
    route::post('promotion/couponItemSearch.html', [ 'as' => 'topshop.promotion.couponlist', 'uses' => 'topshop_ctl_promotion_coupon@searchItem' ]);
    route::post('promotion/couponbrand.html', [ 'as' => 'topshop.promotion.coupon', 'uses' => 'topshop_ctl_promotion_coupon@getBrandList' ]);
    #免邮
    route::post('promotion/freepostageItemSearch.html', [ 'as' => 'topshop.promotion.freepostagelist', 'uses' => 'topshop_ctl_promotion_freepostage@searchItem' ]);
    route::post('promotion/freepostagebrand.html', [ 'as' => 'topshop.promotion.freepostage', 'uses' => 'topshop_ctl_promotion_freepostage@getBrandList' ]);
    #免邮
    route::post('promotion/xydiscountItemSearch.html', [ 'as' => 'topshop.promotion.xydiscountlist', 'uses' => 'topshop_ctl_promotion_xydiscount@searchItem' ]);
    route::post('promotion/xydiscountbrand.html', [ 'as' => 'topshop.promotion.xydiscount', 'uses' => 'topshop_ctl_promotion_xydiscount@getBrandList' ]);
    # 不可报名活动详情
    route::get('activity/noregistered_detail.html', [ 'as' => 'topshop.activity.noregistered_detail', 'uses' => 'topshop_ctl_promotion_activity@noregistered_detail' ]);
    # 活动报名表单
    route::get('activity/canregistered_apply.html', [ 'as' => 'topshop.activity.canregistered_apply', 'uses' => 'topshop_ctl_promotion_activity@canregistered_apply' ]);
    # 保存活动报名表单
    route::post('activity/canregistered_apply_save.html', [ 'as' => 'topshop.activity.canregistered_apply_save', 'uses' => 'topshop_ctl_promotion_activity@canregistered_apply_save' ]);
    # 根据活动搜索报名的商品
    route::post('activity/activityItemSearch.html', [ 'as' => 'topshop.activity.itemsearch', 'uses' => 'topshop_ctl_promotion_activity@searchItem' ]);
    # 入驻申请页
    route::post('apply.html', [ 'as' => 'topshop.apply.create', 'uses' => 'topshop_ctl_enterapply@apply' ]);
    # 入驻申请页保存
    route::post('apply/save.html', [ 'as' => 'topshop.apply.save', 'uses' => 'topshop_ctl_enterapply@saveApply' ]);
    # 入驻申请更改
    route::get('apply/update.html', [ 'as' => 'topshop.apply.update', 'uses' => 'topshop_ctl_enterapply@updateApply' ]);
    # 入驻申请查看
    route::get('apply/checkplan.html', [ 'as' => 'topshop.apply.checkplan', 'uses' => 'topshop_ctl_enterapply@checkPlan' ]);
    # 入驻申请-ajax请求类目下的品牌
    route::post('ajax/cat/brand.html', [ 'as' => 'topshop.ajax.catbrand', 'uses' => 'topshop_ctl_enterapply@ajaxCatBrand' ]);
    # 获取自然属性页面
    route::post('natureprops.html', [ 'as' => 'toputil.syscat.nature', 'uses' => 'topshop_ctl_sku@getNatureProps' ]);
    # 获取详细参数页面
    route::post('params.html', [ 'as' => 'toputil.syscat.params', 'uses' => 'topshop_ctl_sku@getParams' ]);
    # 获取销售属性页面
    route::post('spec/props.html', [ 'as' => 'toputil.syscat.spec.props', 'uses' => 'topshop_ctl_sku@getSpecProps' ]);
    # 获取销售属性选择信息
    route::post('spec/selectprops.html', [ 'as' => 'toputil.syscat.spec.selectprops', 'uses' => 'topshop_ctl_sku@set_spec_index' ]);
    # 商家后台报表
    route::post('sysstat/sysstat.html', [ 'as' => 'topshop.sysstat.sysstat', 'uses' => 'topshop_ctl_sysstat_sysstat@ajaxTrade' ]);
    route::post('sysstat/stattrade.html', [ 'as' => 'topshop.sysstat.stattrade', 'uses' => 'topshop_ctl_sysstat_stattrade@ajaxTrade' ]);
    route::post('sysstat/sysbusiness.html', [ 'as' => 'topshop.sysstat.sysbusiness', 'uses' => 'topshop_ctl_sysstat_sysbusiness@ajaxTrade' ]);
    route::post('sysstat/itemtrade.html', [ 'as' => 'topshop.sysstat.itemtrade', 'uses' => 'topshop_ctl_sysstat_itemtrade@ajaxTrade' ]);
    # 商家发货
    route::group(array('middleware'=>'topshop_middleware_developerMode'), function() {
        route::get('trade/godelivery.html', [ 'as' => 'topshop.trade.godelivery', 'uses' => 'topshop_ctl_trade_flow@godelivery' ]);
        route::post('trade/dodelivery.html', [ 'as' => 'topshop.trade.dodelivery', 'uses' => 'topshop_ctl_trade_flow@dodelivery' ]);
    });

    //wap配置
    route::post('wap/searchItem.html', [ 'as' => 'topshop.wap.decorate.searchItem', 'uses' => 'topshop_ctl_wap_decorate@searchItem' ]);
    route::post('wap/getBrandList.html', [ 'as' => 'topshop.wap.decorate.getBrandList', 'uses' => 'topshop_ctl_wap_decorate@getBrandList' ]);
    #意见反馈
    route::post('feedback.html', [ 'as' => 'topshop.feedback', 'uses' => 'topshop_ctl_index@feedback' ]);

    #编辑常用菜单
    route::post('common/user/menu.html', [ 'as' => 'topshop.commonUserMenu', 'uses' => 'topshop_ctl_menu@index' ]);

    route::get('export.html', [ 'as' => 'toputil.export.view', 'uses' => 'topshop_ctl_export@view' ]);
    route::post('export.html', [ 'as' => 'toputil.export.do', 'uses' => 'topshop_ctl_export@export' ]);

    $menus = config::get('shop');
    foreach($menus as $subMenus)
    {
        foreach($subMenus['menu'] as $menu)
        {
            $method = array_key_exists('method', $menu) ? strtolower($menu['mehtod']) : 'get';
            $parameters = array($menu['url'], ['as' => $menu['as'], 'uses' => $menu['action'], 'middleware'=>$menu['middleware']]);
            forward_static_call_array(array('route', $menu['method']), $parameters);
        }
    }
});

/*
|--------------------------------------------------------------------------
| 店铺通用显示数据处理
|--------------------------------------------------------------------------
 */
route::group(array('prefix' => 'utils'), function() {
    # 系统分类
    route::post('syscat.html', [ 'as' => 'toputil.syscat', 'uses' => 'toputil_ctl_syscat@getChildrenCatList' ]);
    route::get('vcode.html', [ 'as' => 'toputil.vcode', 'uses' => 'toputil_ctl_vcode@gen_vcode' ]);
    route::post('util/upload_images.html', [ 'as' => 'toputil.uploadImages', 'uses' => 'toputil_ctl_image@uploadImages' ]);
    route::get('util/item_pic.html', [ 'as' => 'toputil.getDefaultItemPic', 'uses' => 'toputil_ctl_image@getItemPic' ]);
});

/*
|--------------------------------------------------------------------------
| 后台通用route
|--------------------------------------------------------------------------
 */
route::group(array('prefix' => 'shopadmin'), function() {

    # 系统分类
    route::match(array('GET', 'POST'), '/', array('as' => 'shopadmin', 'uses' => 'desktop_router@dispatch'));
});

/*
|--------------------------------------------------------------------------
| setup
|--------------------------------------------------------------------------
 */
route::group(array('prefix' => 'setup'), function() {
    # 安装首页
    route::match(array('GET', 'POST'), '/', ['as' => 'setup', 'uses' => 'setup_ctl_default@index']);
    # 安装页
    route::match(array('GET', 'POST'), '/default/process', ['uses' => 'setup_ctl_default@process']);
    # 命令行安装
    route::match(array('GET', 'POST'), '/default/install_app', ['uses' => 'setup_ctl_default@install_app']);
    # console
    route::match(array('GET', 'POST'), '/default/console', ['uses' => 'setup_ctl_default@console']);
    # 激活
    route::match(array('GET', 'POST'), '/default/active', ['uses' => 'setup_ctl_default@active']);
    # 激活成功
    route::match(array('GET', 'POST'), '/default/success', ['uses' => 'setup_ctl_default@success']);
    # 环境初始化
    route::match(array('GET', 'POST'), '/default/initenv', ['uses' => 'setup_ctl_default@initenv']);
    # 女装初始化数据
    route::match(array('GET', 'POST'), '/default/install_demodata', ['uses' => 'setup_ctl_default@install_demodata']);
    route::match(array('GET', 'POST'), '/default/setuptools', ['uses' => 'setup_ctl_default@setuptools']);

});

/*
|--------------------------------------------------------------------------
| 公众店铺 Ric
|--------------------------------------------------------------------------
*/

route::group(array('prefix' => 'public'), function() {
    # 首页
    route::get('/', [ 'as' => 'topd.home', 'uses' => 'topd_ctl_index@index' ]);

    #商家信息查看
    route::get('enterapply/applyInfo.html', [ 'as' => 'topd.getApplyInfo', 'uses' => 'topd_ctl_enterapply@getApplyInfo' ]);
    # 入驻申请页
    route::post('apply.html', [ 'as' => 'topd.apply.create', 'uses' => 'topd_ctl_enterapply@apply' ]);
    # 入驻申请页保存
    route::post('apply/save.html', [ 'as' => 'topd.apply.save', 'uses' => 'topd_ctl_enterapply@saveApply' ]);
    # 入驻申请更改
    route::get('apply/update.html', [ 'as' => 'topd.apply.update', 'uses' => 'topd_ctl_enterapply@updateApply' ]);
    # 入驻申请支付
    route::get('apply/checkPay.html', [ 'as' => 'topd.apply.checkPay', 'uses' => 'topd_ctl_enterapply@checkPay' ]);
    # 入驻申请查看
    route::get('apply/checkplan.html', [ 'as' => 'topd.apply.checkplan', 'uses' => 'topd_ctl_enterapply@checkPlan' ]);

    # 商家后台报表
    route::post('sysstat/sysbusiness.html', [ 'as' => 'topd.sysstat.sysbusiness', 'uses' => 'topd_ctl_sysstat_sysbusiness@ajaxTrade' ]);

    #意见反馈
    route::post('feedback.html', [ 'as' => 'topd.feedback', 'uses' => 'topd_ctl_index@feedback' ]);

    route::get('export.html', [ 'as' => 'toputil.export.view', 'uses' => 'topd_ctl_export@view' ]);
    route::post('export.html', [ 'as' => 'toputil.export.do', 'uses' => 'topd_ctl_export@export' ]);
    
    # 商品列表
    route::get('topd/goods_index.html', [ 'as' => 'topd.goods', 'uses' => 'topd_ctl_goods@index' ]);
    # 添加商品
    route::match(array('GET', 'POST'),'topd/goods_add.html', [ 'as' => 'topd.goods', 'uses' => 'topd_ctl_goods@add' ]);
    # 下架商品
    route::get('topd/goods_dl.html', [ 'as' => 'topd.goods', 'uses' => 'topd_ctl_goods@dl' ]);
    route::post('topd/member_Gotoshopxj.html', [ 'uses' => 'topd_ctl_goods@ajaxGotoshopxj' ]); //下架分销商品提交
    route::get('topd/member_Gotoshopxj.html', [ 'uses' => 'topd_ctl_goods@ajaxGotoshopxj' ]); //下架分销商品提交
    route::post('topd/member_Gotoshopsj.html', [ 'uses' => 'topd_ctl_goods@ajaxGotoshopsj' ]); //上架分销商品提交
    route::get('topd/member_Gotoshopsj.html', [ 'uses' => 'topd_ctl_goods@ajaxGotoshopsj' ]); //上架分销商品提交
    route::post('topd/member_Gotoshopsc.html', [ 'uses' => 'topd_ctl_goods@ajaxGotoshopsc' ]); //删除分销商品提交
    route::get('topd/member_Gotoshopsc.html', [ 'uses' => 'topd_ctl_goods@ajaxGotoshopsc' ]); //删除分销商品提交
    route::post('topd/member_Gotoshop.html', [ 'uses' => 'topd_ctl_goods@ajaxGotoshop' ]); //删除分销商品提交
    route::get('topd/member_Gotoshop.html', [ 'uses' => 'topd_ctl_goods@ajaxGotoshop' ]); //删除分销商品提交

    #分销商服务
    route::get('shop/info.html',['as' => 'topd.shop','uses'=> 'topd_ctl_shop_info@index']);//入驻信息
    route::get('shop/info_shop.html',['as' => 'topd.shop','uses'=> 'topd_ctl_shop_info@shop']);//店铺信息
    route::get('shop/info_xg.html',['as' => 'topd.shop','uses'=> 'topd_ctl_shop_info@xgshop']);//店铺修改
    route::post('shop/info_save.html',['as' => 'topd.shop','uses'=> 'topd_ctl_shop_info@saveshop']);//店铺保存
    #报表
    route::get('sysstat/sysbusiness.html', [ 'as' => 'topd.sysstat', 'uses' => 'topd_ctl_sysstat_sysbusiness@index' ]);//业务数据分析
    #资金管理
    route::get('capital/sysstat.html', [ 'as' => 'topd.capital', 'uses' => 'topd_ctl_capital_sysstat@index' ]);//我的资金

    #分销首页
    route::get('index.html',['as' => 'topd.index','uses'=> 'topd_ctl_info@index']);
    route::post('getBrandList.html',['as' => 'topd.getBrandList','uses'=> 'topd_ctl_goods@getBrandList']); //获取品牌
    route::post('syscat.html', [ 'as' => 'topd.syscat', 'uses' => 'topd_ctl_goods@getChildrenCatList' ]); //获取分类

    #分销支付成功返回
    route::get('finish.html',['as' => 'topd.finish','uses'=>'topd_ctl_index@finish']);
    route::post('finish.html',['as' => 'topd.finish','uses'=>'topd_ctl_index@finish']);
    #分销支付失败返回
    route::get('error.html',['as' => 'topd.finish','uses'=>'topd_ctl_index@error']);
    route::post('error.html',['as' => 'topd.finish','uses'=>'topd_ctl_index@error']);
    # 个人店铺列表
    route::get('fxlist.html', [ 'uses' => 'topc_ctl_list@fxindex' ]);

    #支付中心
    route::post('payment.html', [ 'uses' => 'topd_ctl_paycenter@index' ]);
    route::match(array('GET', 'POST'), 'create.html', ['uses' => 'topd_ctl_paycenter@createPay']);
    route::post('dopayment.html', [ 'uses' => 'topd_ctl_paycenter@dopayment' ]);
    route::get('finish.html', [ 'uses' => 'topd_ctl_paycenter@finish' ]);

    $menus = config::get('public');
    foreach($menus as $subMenus)
    {
        foreach($subMenus['menu'] as $menu)
        {
            $method = array_key_exists('method', $menu) ? strtolower($menu['mehtod']) : 'get';
            $parameters = array($menu['url'], ['uses' => $menu['action']]);
            forward_static_call_array(array('route', $menu['method']), $parameters);
        }
    }
});
