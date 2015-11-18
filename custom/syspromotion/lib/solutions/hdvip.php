<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 * @author  lujunyi@shopex.cn
 */
class syspromotion_solutions_hdvip{

    public function useCoupon($params)
    {
        //判断卡号是否存在
        $qb = app::get('syspromotion')->database()->createQueryBuilder()
            ->select('p.coupon_id, card_account, card_id, canuse_start_time, canuse_end_time, card_status', 'type', 'coupon_name','used_platform','card_password','money','c.deduct_money')
            ->from('syspromotion_hdvip_card', 'c')
            ->leftJoin('c', 'syspromotion_hdvip', 'p', 'c.coupon_id = p.coupon_id')
            ->where("p.coupon_status = 'agree'")
            ->andWhere("p.use_bound = '3'")
            ->andWhere("c.card_account = '".$params['vip_card']."'")
            ->execute()->fetch();
        if(empty($qb)){
            return '卡号不存在，请核对！';
        }
        if(!pam_encrypt::check($params['vip_pas'],$qb['card_password'])){
            return '密码错误！';
        }
        if(!in_array($qb['used_platform'],array(0,$params['used_platform']))){
            return '活动不适用于该平台！';
        }
        if($qb['card_status'] == 0){
            return '该卡已失效！';
        }
        //支付处理
        $temzt = app::get('topd')->model('dp')->update(array('fenxiao_payprice' => $qb['deduct_money'], 'fenxiao_paytime' => time(), 'fenxiao_zt' => 'successful', 'payment_app_id' => serialize($params), 'payment_pay_id' => serialize($qb), 'payment_id' => $params['vip_card']), array('user_id' => $params['user_id'], 'fenxiao_zt' => 'active'));//更新支付状态
        if($temzt){
            $userInfo = userAuth::getUserInfo(); //会员信息
            $ret = array(
                'money' => $qb['deduct_money'],
                't_payed' =>time(),
                'bank' => "VIP开店卡",
                'payment_id' => 'HDVIP'.$qb['card_account'],
            );
            kernel::single('ectools_payment_api')->_hdvgtopd($ret,$userInfo['fenxiaodp']);
            if(app::get('syspromotion')->model('hdvip_card')->update(array('deduct_money' => 0, 'card_status' => 0), array('card_id' => $qb['card_id']))){
                return 'true';
            }
        }
        return '支付失败，请与客服联系！';
    }
}
