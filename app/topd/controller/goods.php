<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */

class topd_ctl_goods extends topd_controller {

    public $topdtic; //分销商提成率

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
        $this->topdtic = app::get('site')->getconf('base.shop_topd');
    }

    /**
     * 商品管理列表
     * Ric
     * @return html
     */
    public function add()
    {
        $pagedata['status'] = input::get('status');//空即恒大微购商品库，mysqle为我的商品，onsale为上架商品，instock仓库商品
        if($pagedata['status'] == 'mysale') {
            $pagedata['initem'] = app::get('topd')->model('goods')->getList("item_id", array('user_id' => $this->userInfo['userId']));
            $pagedata['item_id'] =  implode(",", array_column($pagedata['initem'],'item_id'));
            $pagedata['item_id'] = empty($pagedata['item_id']) ? 1 : $pagedata['item_id']; //商品id为1就为空
            $this->contentHeaderTitle = app::get('topd')->_('我的商品');
        }elseif($pagedata['status'] == 'onsale') {
            $pagedata['initem'] = app::get('topd')->model('goods')->getList("item_id", array('user_id' => $this->userInfo['userId'], 'goods_zt' => 'onsale'));
            $pagedata['item_id'] =  implode(",", array_column($pagedata['initem'],'item_id'));
            $pagedata['item_id'] = empty($pagedata['item_id']) ? 1 : $pagedata['item_id'];//商品id为1就为空
            $this->contentHeaderTitle = app::get('topd')->_('上架商品');
        }elseif($pagedata['status'] == 'instock') {
            $pagedata['initem'] = app::get('topd')->model('goods')->getList("item_id", array('user_id' => $this->userInfo['userId'], 'goods_zt' => 'instock'));
            $pagedata['item_id'] =  implode(",", array_column($pagedata['initem'],'item_id'));
            $pagedata['item_id'] = empty($pagedata['item_id']) ? 1 : $pagedata['item_id'];//商品id为1就为空
            $this->contentHeaderTitle = app::get('topd')->_('仓库商品');
        }else{
            $pagedata['item_id'] = '';
            $pagedata['item_id'] =  implode(",", array_column($pagedata['initem'],'item_id'));
            $this->contentHeaderTitle = app::get('topd')->_('恒大微购商品库');
        }
        $pagedata['image_default_id'] = app::get('image')->getConf('image.set'); //获取默认图片
        $pages =  input::get('pages',1);  //获取页数
        if(input::get('min_price')&&input::get('max_price'))  //商品的价格范围
        {
            if (input::get('min_price') > input::get('max_price')) {
                $msg = app::get('topd')->_('最大值不能小于最小值！');
                return $this->splash('error', null, $msg, true);
            }
        }
        //拼接搜索条件开始
        $filter = array(
            'approve_status' => 'onsale',
            'search_keywords' => input::get('item_title'),
            'min_price' => input::get('min_price'),
            'max_price' => input::get('max_price'),
            'page_no' =>$pages,
            'page_size' => $this->limit,
            'cat_id' =>input::get('cat_id'),
            'brand_id' => input::get('brand_id'),
            'item_id' => $pagedata['item_id'],
        );
        //拼接搜索条件结束
        $filter['fields'] = 'item_id,title,image_default_id,price,approve_status,store';
        $itemsList = app::get('topshop')->rpcCall('item.search',$filter);
        $pagedata['item_list'] = $itemsList['list'];
        $pagedata['total'] = $itemsList['total_found'];
        $totalPage = ceil($itemsList['total_found']/$this->limit);
        $pagersFilter['pages'] = time();
        $pagersFilter['status'] = $pagedata['status'];
        $pagers = array(
            'link'=>url::action('topd_ctl_goods@add',$pagersFilter),
            'current'=>$pages,
            'use_app' => 'topd',
            'total'=>$totalPage,
            'token'=>time(),
        );
        $pagedata['pagers'] = $pagers;
        foreach($pagedata['item_list'] as $key => $val){
            $pagedata['item_list'][$key]['goods_zt'] = app::get('topd')->model('goods')->getRow("goods_zt",array('user_id'=>$this->userInfo['userId'],'item_id'=>$val['item_id']));
            $qb = app::get('topd')->database()->createQueryBuilder();
            $qb->select('min(price) as miprice, max(price) as maprice, min((price - cost_price)*'.$this->topdtic.') as minprice, max((price - cost_price)*'.$this->topdtic.') as maxprice')
                ->from('sysitem_sku')
                ->where("item_id = '".$val['item_id']."'");
            $pagedata['item_list'][$key]['sx'] = $qb->execute()->fetchAll();
        }
        $pagedata['shopCatList'] = app::get('syscategory')->rpcCall('category.cat.get.list');
        $pagedata['filter'] = input::get();
        $pagedata['twocat'] = $pagedata['shopCatList'][$pagedata['filter']['onecat']]['lv2'];
        $pagedata['threecat'] = $pagedata['twocat'][$pagedata['filter']['twocat']]['lv3'];
        return $this->page('topd/goods/edit.html', $pagedata);
    }

    //根据3级分类id获取所有品牌
    public function getBrandList()
    {
        $catId = input::get('catId');
        $params = array(
            'cat_id'=>$catId,
            'fields'=>'brand_id,brand_name,brand_url'
        );
        $brands = app::get('syscategory')->rpcCall('category.get.cat.rel.brand',$params);
        return response::json($brands);
    }

    /**
     * 根据父类id获取子类列表
     * @return json
     */
    public function getChildrenCatList()
    {
        $catId = intval(input::get('cat_id'));
        if($catId)
        {
            $catList = app::get('toputil')->rpcCall('category.cat.get.info',array('parent_id'=>$catId,'fields'=>'cat_id,cat_name,child_count'));
            foreach($catList as $key=>$value) {
                $newList[$key] = array(
                    'value' => $value['cat_id'],
                    'text' => $value['cat_name'],
                    'hasChild' => ($value['child_count'] >0) ? true : false,
                );
            }
            $data['data']['options'] = $newList;
        }
        else
        {
            $data=array();
        }
        return response::json($data);
    }

    /**
     * @brief 添加、上、下架、删除商品
     * 公众店铺
     * Ric
     */
    public function ajaxGotoshop() {
        $params['user_id'] = $this->userInfo['userId'];
        $fields = '*';
        if(input::get('type') == 'ajaxGotoshop' || input::get('type') == 'ajaxGotoshopsj'){
            $goods_id = app::get('sysitem')->model('item')->getRow('item_id',array('item_id'=>intval(input::get('goods_id'))));
            if(empty($goods_id['item_id'])){
                return $this->splash('error',null, '该商品已经不存在了哦！', true);
            }
            $params['goods_zt'] = 'onsale';
            //判断分销商品数量
            $goodsnum= app::get('topd')->model('goods')->getList($fields, $params);
            if(count($goodsnum) >= 1000){
                $params['goods_zt'] = 'instock';
                $msg = '上架商品已经超过1000个，宝贝只能呆在仓库中了！';
            }else{
                $msg = '商品上架成功！';
            }
            $params['item_id'] = $goods_id['item_id'];
        }elseif(input::get('type') == 'ajaxGotoshopxj'){
            $params['goods_zt'] = 'instock';
            $params['item_id'] = intval(input::get('goods_id'));
            $msg = '商品下架成功！';
        }else{
            $params['item_id'] = intval(input::get('goods_id'));
            if(app::get('topd')->model('goods')->delete($params)) {
                return $this->splash('success',null,'商品删除成功！', true);
            } else {
                return $this->splash('error',null, '删除失败', true);
            }
        }
        if(app::get('topd')->model('goods')->save($params)) {
            return $this->splash('success',null, $msg, true);
        } else {
            return $this->splash('error',null, '操作失败！', true);
        }
    }
}

