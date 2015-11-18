<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2012 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */

class topd_controller extends base_routing_controller
{
    public $userInfo;
    /**
     * 页面不需要menu
     * 公众店铺
     */
    public $nomenu = false;

    public function __construct($app)
    {
        kernel::single('base_session')->start();
        if(!$this->action) $this->action = 'index';
        $this->action_view = $this->action.".html";
        $fenxiao_tuijuan = trim(input::get('fenxiao_tuijuan')); //推荐
        if(!empty($fenxiao_tuijuan)){
            setcookie('fenxiao_tuijuan',$fenxiao_tuijuan,time()+3600,'/'); //一小时内注册有效
            $_COOKIE['fenxiao_tuijuan'] = $fenxiao_tuijuan;
        }
        // 检测是否登录
        if( !userAuth::check() )
        {
            redirect::action('topc_ctl_passport@signin',array('appurl'=>'public'))->send();exit;
        }
        $this->limit = 20;
        $this->userInfo = userAuth::getUserInfo(); //会员信息
        $this->passport = kernel::single('topc_passport');
    }

    /**
     * @brief 错误或者成功输出
     * 公众店铺
     * @param string $status
     * @param stirng $url
     * @param string $msg
     * @param string $method
     * @param array $params
     *
     * @return string
     */
    public function splash($status='success',$url=null,$msg=null,$ajax=true){
        $status = ($status == 'failed') ? 'error' : $status;
        //如果需要返回则ajax
        if($ajax==true||request::ajax()){
            return response::json(array(
                $status => true,
                'message'=>$msg,
                'redirect' => $url,
            ));
        }

        if($url && !$msg){//如果有url地址但是没有信息输出则直接跳转
            return redirect::to($url);
        }
    }

    /*
     * 公众店铺
     * */
    public function isValidMsg($status)
    {
        $status = ($status == 'true') ? 'true' : 'false';
        $res['valid'] = $status;
        return response::json($res);
    }

    /**
     * @brief 商家中心页面加载，默认包含商家中心头、尾、导航、和左边栏
     * 公众店铺
     * @param string $view  html路径
     * @param stirng $app   html路径所在app
     *
     * @return html
     */
    public function page($view, $pagedata = array())
    {
        $pagedata['userInfo'] = $this->userInfo;
        $topdPageParams['path'] = $this->runtimePath;//设置面包屑


        if( $this->contentHeaderTitle )
        {
            $topdPageParams['contentTitle'] = $this->contentHeaderTitle;
        }

        //当前页面调用的action
        $topdPageParams['currentActionName']= route::current()->getActionName();

        $menuArr = $this->__getMenu();
        if( $menuArr && !$this->nomenu )
        {
            $topdPageParams['navbar'] = $menuArr['navbar'];
            $topdPageParams['sidebar'] = $menuArr['sidebar'];
        }

        $topdPageParams['view'] = $view;

        $pagedata['topd'] = $topdPageParams;
        $pagedata['icon'] =  kernel::base_url(1).'/public/statics/favicon.ico';
        if( !$this->tmplName )
        {
            $this->tmplName = 'topd/tmpl/page.html';
        }
        return view::make($this->tmplName, $pagedata);
    }

    /*
     * 公众店铺
     * Ric
     * */
    public function set_tmpl($tmpl)
    {
        $tmplName = 'topd/tmpl/'.$tmpl.'.html';
        $this->tmplName = $tmplName;
    }

    /**
     * @brief 获取到商家中心的导航菜单和左边栏菜单
     *
     * @return array $res
     */
    private function __getMenu()
    {
        $defaultActionName = route::current()->getActionName();
        $shopMenu = config::get('public');

        $commonUserMenu = $this->__commonUserMenu();
        $sidebar['commonUser']['label'] = '常用菜单';
        $sidebar['commonUser']['active'] = true; //是否展开
        $sidebar['commonUser']['icon'] = 'glyphicon glyphicon-heart';
        $sidebar['commonUser']['menu'] = $commonUserMenu;

        foreach( (array)$shopMenu as $menu => $row )
        {
            if( $row['display'] === false ) continue;

            $navbar[$menu]['label'] = $row['label'];
            $navbar[$menu]['icon'] = $row['icon'];
            $navbar[$menu]['action'] = $row['action'];

            foreach( (array)$row['menu'] as $k=>$params )
            {
                if( $params['action'] == $defaultActionName )
                {
                    $navbar[$menu]['default'] = true;
                }
                if( $shopIndex )
                {
                    $shopIndex = true;
                    $sidebar[$menu] = $navbar[$menu];
                    $sidebar[$menu]['active'] = false; //是否展开
                    $sidebar[$menu]['menu'] = $row['menu'];
                    continue;
                }
                if( $row['shopIndex'] && $params['action'] == $defaultActionName )
                {
                    $shopIndex = $row['shopIndex'];
                    continue;
                }

                if( $params['action'] == $defaultActionName )
                {
                    $sidebar[$menu]['label'] = $row['label'];
                    $row['menu'][$k]['default'] = true;
                    $sidebar[$menu]['active'] = true; //是否展开
                    $sidebar[$menu]['icon'] = $row['icon'];
                    $sidebar[$menu]['menu'] = $row['menu'];
                }
            }
        }

        $res['navbar'] = $navbar;
        $res['sidebar'] = $sidebar;

        return $res;
    }

    private function __commonUserMenu()
    {
        return [
            array('label'=>'运营报表','display'=>true,'action'=>'topd_ctl_sysstat_sysbusiness@index'),
            array('label'=>'商品管理','display'=>true,'action'=>'topd_ctl_goods@add'),
        ];
    }

    /**
     * 用于指示商家操作者的标志
     * @return array 商家登录用户信息
     */
    public function operator()
    {
        return array(
            'user_type' => 'user',
            'op_id' => pamAccount::getAccountId(),
            'op_account' => pamAccount::getLoginName(),
        );
    }

}

