<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 * @author  lujunyi@shopex.cn
 */
class syspromotion_solutions_coupon implements syspromotion_interface_promotions{

    public function apply($cartData)
    {
        $cartItemsInfo = explode('|', $cartData['cartItemsInfo']);
        $cartItemsArray = array();
        foreach($cartItemsInfo as $v)
        {
            $itemInfo = explode('_', $v);
            $cartItemsArray[] = array('item_id'=>$itemInfo['0'],'total_price'=>$itemInfo['1']);
        }

        if($this->checkCouponApply($cartData['user_id'], $cartData['coupon_code'], $cartItemsArray, $couponInfo))
        {
            return $couponInfo['deduct_money'];
        }
        else
        {
            return 0;
        }
    }

    public function useCoupon($params)
    {
        if($params['coupon_code'] != 'HDVGVIP' && (!$this->checkCouponUse($params['user_id'], $params['coupon_code'], $params['mode'], $params['platform'], $couponInfo)))
        {
            return false;
        }

        $cartCouponData = array('coupon_code'=>$params['coupon_code'], 'shop_id'=>$couponInfo['shop_id'], 'user_id'=>$params['user_id']);
        app::get('syspromotion')->rpcCall('trade.cart.cartCouponAdd', $cartCouponData);

        return true;
    }

    public function checkCouponUse($user_id, $coupon_code, $buyMode, $platform, &$couponInfo)
    {
        $filter['user_id'] = $user_id;
        $filter['coupon_code'] = $coupon_code;
        //Ric 修改，增加VIP折扣卡使用规则
        if(substr($filter['coupon_code'],0,7) == 'HDVGVIP'){
            $time = time();
            $userCoupon = app::get('syspromotion')->rpcCall('user.hdvip.get', $filter);
            //是否查询到该优惠
            if(!$userCoupon || !$userCoupon['coupon_id'])
            {
                $msg = app::get('syspromotion')->_('应用红包失败！');
                throw new \LogicException($msg);
            }
            //优惠券使用状态
            if($userCoupon['card_status'] == 0){
                $msg = app::get('syspromotion')->_('红包已经失效！');
                throw new \LogicException($msg);
            }elseif($userCoupon['card_status'] == 1 && ($userCoupon['canuse_start_time'] > $time || $userCoupon['canuse_end_time'] < $time)){
                $msg = app::get('syspromotion')->_('红包不在使用期内！');
                throw new \LogicException($msg);
            }elseif($userCoupon['card_status'] == 4){
                $msg = app::get('syspromotion')->_('该红包已经被暂停，请与客服联系！');
                throw new \LogicException($msg);
            }
            //查询折扣卡信息
            $objMdlCoupon = app::get('syspromotion')->model('hdvip');
            $couponInfo = $objMdlCoupon->getRow('*', array('coupon_id'=>$userCoupon['coupon_id']) );
            //判断是否审核通过
            if(!$couponInfo || $couponInfo['coupon_status'] != 'agree')
            {
                throw new \LogicException('不能使用此优惠券!');
            }
            $couponInfo['userCoupon'] = $userCoupon;
            //查询购物车信息
            $cartFilter['mode'] = $buyMode;
            $cartFilter['needInvalid'] = false;
            $cartFilter['platform'] = $platform;
            $cartFilter['user_id'] = $user_id;
            $cartData = app::get('syspromotion')->rpcCall('trade.cart.getCartInfo', $cartFilter);
            if($cartData['totalCart']['totalAfterDiscount']<$couponInfo['limit_money'])
            {
                throw new \LogicException('未达到折扣金额!');
            }
        }else{
        	$userCoupon = app::get('syspromotion')->rpcCall('user.coupon.get', $filter);
	        if(!$userCoupon || !$userCoupon['coupon_id'])
	        {
	            $msg = app::get('syspromotion')->_('应用优惠券失败！');
	            throw new \LogicException($msg);
	        }
	        if(!$userCoupon['is_valid'])
	        {
	           $msg = app::get('syspromotion')->_('优惠券无效！');
	           throw new \LogicException($msg);
	        }

	        $objMdlCoupon = app::get('syspromotion')->model('coupon');
	        $couponInfo = $objMdlCoupon->getRow('*', array('coupon_id'=>$userCoupon['coupon_id']) );

	        if(!$couponInfo || $couponInfo['coupon_status'] != 'agree')
	        {
	            throw new \LogicException('不能使用此优惠券!');
	        }
	        $now = time();
	        if( $now<=$couponInfo['canuse_start_time'] )
	        {
	            throw new \LogicException('尚未到优惠券使用时间!');
	        }
	        if( $now>=$couponInfo['canuse_end_time'] )
	        {
	            throw new \LogicException('优惠券已经过期!');
	        }

	        $cartFilter['mode'] = $buyMode;
	        $cartFilter['needInvalid'] = false;
	        $cartFilter['platform'] = $platform;
	        $cartFilter['user_id'] = $user_id;
	        $cartData = app::get('syspromotion')->rpcCall('trade.cart.getCartInfo', $cartFilter);
	        if(!$cartData['resultCartData'][$couponInfo['shop_id']])
	        {
	            throw new \LogicException('非法优惠券!');
	        }

	        $objMdlCouponItem = app::get('syspromotion')->model('coupon_item');
	        $couponItems = $objMdlCouponItem->getList('item_id', array('coupon_id'=>$userCoupon['coupon_id']) );
	        $couponItemsIdArr = array_column($couponItems, 'item_id');

	        $validItemTotalFee = array();
	        foreach($cartData['resultCartData'][$couponInfo['shop_id']]['object'] as $v)
	        {
	            if( in_array($v['item_id'], $couponItemsIdArr) )
	            {
	                $validItemTotalFee[] = $v['price']['total_price'];
	            }
	        }
	        $validTotalMoney = ecmath::number_plus($validItemTotalFee);
	        if($validTotalMoney<=0)
	        {
	            throw new \LogicException('商品不支持使用此优惠券!');
	        }
	        if($validTotalMoney<$couponInfo['limit_money'])
	        {
	            throw new \LogicException('未达到优惠券满减金额!');
	        }
	}
        return true;
    }

    public function checkCouponApply($user_id, $coupon_code, $cartItemsArray, &$couponInfo)
    {
        $filter['user_id'] = $user_id;
        $filter['coupon_code'] = $coupon_code;
        $userCoupon = app::get('syspromotion')->rpcCall('user.coupon.get', $filter);
        if(!$userCoupon || !$userCoupon['coupon_id'])
        {
            return 0;
        }
        if(!$userCoupon['is_valid'])
        {
            return 0;
        }

        $objMdlCoupon = app::get('syspromotion')->model('coupon');
        $couponInfo = $objMdlCoupon->getRow('*', array('coupon_id'=>$userCoupon['coupon_id']) );

        if(!$couponInfo || $couponInfo['coupon_status'] != 'agree')
        {
            return 0;
        }
        $now = time();
        if( $now<=$couponInfo['canuse_start_time'] )
        {
            return 0;
        }
        if( $now>=$couponInfo['canuse_end_time'] )
        {
            return 0;
        }

        $objMdlCouponItem = app::get('syspromotion')->model('coupon_item');
        $couponItems = $objMdlCouponItem->getList('item_id', array('coupon_id'=>$userCoupon['coupon_id']) );
        $couponItemsIdArr = array_column($couponItems, 'item_id');

        $validItemTotalFee = array();
        foreach($cartItemsArray as $v)
        {
            if( in_array($v['item_id'], $couponItemsIdArr) )
            {
                $validItemTotalFee[] = $v['total_price'];
            }
        }
        $validTotalMoney = ecmath::number_plus($validItemTotalFee);
        if($validTotalMoney<$couponInfo['limit_money'])
        {
            return 0;
        }

        return true;
    }

}
