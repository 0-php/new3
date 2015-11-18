<?php
/**
 * 测试定时任务用，可以删除
 */

/**
 * 实现报表定时任务
 * @auther hdvg
 * @version 0.1
 * @Ric
 */
class syshdtj_ctl_task{
    public function __construct()
    {
        //$this->day_time = strtotime("today");
        $this->day_time = time();
    }

    /**
     * 每天例行任务
     * @param null
     * @return null
     * 日结到至今为止的所有未结已经确定收货订单
     */
    public function analysis_day()
    {
        /*$qb = app::get('syshdtj')->database()->createQueryBuilder();
        $da = $qb->select('*')
		->from('syshdtj_day')
        ->where($qb->expr()->eq('day_time', ($this->day_time)))
        ->execute()->fetch();*/
        $countnum = !empty($da['countnum']) ? $da['countnum'] : 0;
        $shopnum = !empty($da['shopnum']) ? $da['shopnum'] : 0;
        $topdnum = !empty($da['topdnum']) ? $da['topdnum'] : 0;
        $concertnum = !empty($da['concertnum']) ? $da['concertnum'] : 0;
        $countprice = !empty($da['countprice']) ? $da['countprice'] : 0;
        $shopprice = !empty($da['shopprice']) ? $da['shopprice'] : 0;
        $topdprice = !empty($da['topdprice']) ? $da['topdprice'] : 0;
        $concertprice = !empty($da['concertprice']) ? $da['concertprice'] : 0;
        $gprice = !empty($da['gprice']) ? $da['gprice'] : 0;
        $tprice = !empty($da['tprice']) ? $da['tprice'] : 0;
        $gxprice = !empty($da['gxprice']) ? $da['gxprice'] : 0;
        $xzsprice = !empty($da['xzsprice']) ? $da['xzsprice'] : 0;
        $xzshiprice = !empty($da['xzshiprice']) ? $da['xzshiprice'] : 0;
        $xzxprice = !empty($da['xzxprice']) ? $da['xzxprice'] : 0;
        $hdvgprice = !empty($da['hdvgprice']) ? $da['hdvgprice'] : 0;
        $qb = app::get('syshdtj')->database()->createQueryBuilder(); //必须重新注册
        $cancel = $qb->select('*')
            ->from('syshdtj_status', 's')
            ->leftJoin('s', 'syshdtj_order', 'o', "o.oid=s.oid and o.order_from=s.order_from")
            ->where("s.status = 'cancel' and o.payed_time < {$this->day_time}")
            ->execute()->fetchAll();
        $db = app::get('syshdtj')->database();
        $db->beginTransaction();
        try {
            foreach($cancel as $key => $val){
                if($val['order_from'] == '产品订单'){
                    $shopnum++;
                    $shopprice += $val['payment'];
                }elseif($val['order_from'] == '店铺开店'){
                    $topdnum++;
                    $topdprice += $val['payment'];
                }elseif($val['order_from'] == '演唱会门票'){
                    $concertnum++;
                    $concertprice += $val['payment'];
                }
                $countnum++;
                $countprice += $val['countprice'];
                $gprice += $val['cost_price'];
                $tprice += $val['topd_price'];
                $gxprice += $val['gszx_price'];
                $xzsprice += $val['szs_price'];
                $xzshiprice += $val['szshi_price'];
                $xzxprice += $val['szx_price'];
                $hdvgprice += $val['hdvg_price'];
                $tem = array(
                    'oid' => $val['oid'],
                    'order_from' => $val['order_from'],
                    'status' => 'succ',
                    'statustime' => $this->day_time,
                );
                app::get('syshdtj')->model('status')->save($tem);
            }
            $day = array(
                'day_time' =>  $this->day_time,                            //日结时间
                'countnum' =>  $countnum,                            //总订单
                'shopnum' =>  $shopnum,                              //产品单
                'topdnum' =>  $topdnum,                              //店铺单
                'concertnum' =>  $concertnum,                       //演唱会
                'countprice' =>  $countprice,                      //总收入
                'shopprice' =>  $shopprice,                        //产品收入
                'topdprice' =>  $topdprice,                        //开店收入
                'concertprice' =>  $concertprice,                   //演唱会收入
                'gprice' =>  $gprice,                              //供应商
                'tprice' =>  $tprice,                              //分销商
                'gxprice' =>  $gxprice,                           //供所在县
                'xzsprice' =>  $xzsprice,                         //行政省
                'xzshiprice' =>  $xzshiprice,                    //行政市
                'xzxprice' =>  $xzxprice,                         //行政县
                'hdvgprice' =>  $hdvgprice,                      //恒大微购
            );
            app::get('syshdtj')->model('day')->save($day);
            $db->commit();
        } catch (Exception $e){
            $db->rollback();
            throw $e;
        }
    }

    /**
     * 每月例行任务
     * @param null
     * @return null
     */
    public function index()
    {
        $qb = app::get('syshdtj')->database()->createQueryBuilder(); //必须重新注册
        $cancel = $qb->select('*')
            ->from('syshdtj_status', 's')
            ->leftJoin('s', 'syshdtj_order', 'o', "o.oid=s.oid and o.order_from=s.order_from")
            ->where("s.statusm = 'cancel' and o.payed_time < {$this->day_time}")
            ->execute()->fetchAll();
        $db = app::get('syshdtj')->database();
        $db->beginTransaction();
        try {
            foreach($cancel as $key => $val){
                //供应商
                $month[0][$val['shop_id']] = array(
                    'month_time' => $this->day_time,
                    'type' => '供应商',
                    'type_id' => $val['shop_id'],
                    'shopnum' => !empty($month[0][$val['shop_id']]['shopnum'])?$month[0][$val['shop_id']]['shopnum']:0,
                    'topdnum' => !empty($month[0][$val['shop_id']]['topdnum'])?$month[0][$val['shop_id']]['topdnum']:0,
                    'concertnum' => !empty($month[0][$val['shop_id']]['concertnum'])?$month[0][$val['shop_id']]['concertnum']:0,
                    'shopprice' => !empty($month[0][$val['shop_id']]['shopprice'])?$month[0][$val['shop_id']]['shopprice']:0,
                    'topdprice' => !empty($month[0][$val['shop_id']]['topdprice'])?$month[0][$val['shop_id']]['topdprice']:0,
                    'concertprice' => !empty($month[0][$val['shop_id']]['concertprice'])?$month[0][$val['shop_id']]['concertprice']:0,
                );
                //公众店铺
                $month[1][$val['topd_id']] = array(
                    'month_time' => $this->day_time,
                    'type' => '公众店铺',
                    'type_id' => $val['topd_id'],
                    'shopnum' => !empty($month[1][$val['shop_id']]['shopnum'])?$month[1][$val['shop_id']]['shopnum']:0,
                    'topdnum' => !empty($month[1][$val['shop_id']]['topdnum'])?$month[1][$val['shop_id']]['topdnum']:0,
                    'concertnum' => !empty($month[1][$val['shop_id']]['concertnum'])?$month[1][$val['shop_id']]['concertnum']:0,
                    'shopprice' => !empty($month[1][$val['shop_id']]['shopprice'])?$month[1][$val['shop_id']]['shopprice']:0,
                    'topdprice' => !empty($month[1][$val['shop_id']]['topdprice'])?$month[1][$val['shop_id']]['topdprice']:0,
                    'concertprice' => !empty($month[1][$val['shop_id']]['concertprice'])?$month[1][$val['shop_id']]['concertprice']:0,
                );
                //供应商所在县
                $month[2][$val['gszx_id']] = array(
                    'month_time' => $this->day_time,
                    'type' => '供应商所在县',
                    'type_id' => $val['gszx_id'],
                    'shopnum' => !empty($month[2][$val['gszx_id']]['shopnum'])?$month[2][$val['gszx_id']]['shopnum']:0,
                    'topdnum' => !empty($month[2][$val['gszx_id']]['topdnum'])?$month[2][$val['gszx_id']]['topdnum']:0,
                    'concertnum' => !empty($month[2][$val['gszx_id']]['concertnum'])?$month[2][$val['gszx_id']]['concertnum']:0,
                    'shopprice' => !empty($month[2][$val['gszx_id']]['shopprice'])?$month[2][$val['gszx_id']]['shopprice']:0,
                    'topdprice' => !empty($month[2][$val['gszx_id']]['topdprice'])?$month[2][$val['gszx_id']]['topdprice']:0,
                    'concertprice' => !empty($month[2][$val['gszx_id']]['concertprice'])?$month[2][$val['gszx_id']]['concertprice']:0,
                );
                //行政省
                $month[3][$val['szs_id']] = array(
                    'month_time' => $this->day_time,
                    'type' => '行政省',
                    'type_id' => $val['szs_id'],
                    'shopnum' => !empty($month[3][$val['szs_id']]['shopnum'])?$month[3][$val['szs_id']]['shopnum']:0,
                    'topdnum' => !empty($month[3][$val['szs_id']]['topdnum'])?$month[3][$val['szs_id']]['topdnum']:0,
                    'concertnum' => !empty($month[3][$val['szs_id']]['concertnum'])?$month[3][$val['szs_id']]['concertnum']:0,
                    'shopprice' => !empty($month[3][$val['szs_id']]['shopprice'])?$month[3][$val['szs_id']]['shopprice']:0,
                    'topdprice' => !empty($month[3][$val['szs_id']]['topdprice'])?$month[3][$val['szs_id']]['topdprice']:0,
                    'concertprice' => !empty($month[3][$val['szs_id']]['concertprice'])?$month[3][$val['szs_id']]['concertprice']:0,
                );
                //行政市
                $month[4][$val['szshi_id']] = array(
                    'month_time' => $this->day_time,
                    'type' => '行政市',
                    'type_id' => $val['szshi_id'],
                    'shopnum' => !empty($month[4][$val['szshi_id']]['shopnum'])?$month[4][$val['szshi_id']]['shopnum']:0,
                    'topdnum' => !empty($month[4][$val['szshi_id']]['topdnum'])?$month[4][$val['szshi_id']]['topdnum']:0,
                    'concertnum' => !empty($month[4][$val['szshi_id']]['concertnum'])?$month[4][$val['szshi_id']]['concertnum']:0,
                    'shopprice' => !empty($month[4][$val['szshi_id']]['shopprice'])?$month[4][$val['szshi_id']]['shopprice']:0,
                    'topdprice' => !empty($month[4][$val['szshi_id']]['topdprice'])?$month[4][$val['szshi_id']]['topdprice']:0,
                    'concertprice' => !empty($month[4][$val['szshi_id']]['concertprice'])?$month[4][$val['szshi_id']]['concertprice']:0,
                );
                //行政县
                $month[5][$val['szx_id']] = array(
                    'month_time' => $this->day_time,
                    'type' => '行政县',
                    'type_id' => $val['szx_id'],
                    'shopnum' => !empty($month[5][$val['szx_id']]['shopnum'])?$month[5][$val['szx_id']]['shopnum']:0,
                    'topdnum' => !empty($month[5][$val['szx_id']]['topdnum'])?$month[5][$val['szx_id']]['topdnum']:0,
                    'concertnum' => !empty($month[5][$val['szx_id']]['concertnum'])?$month[5][$val['szx_id']]['concertnum']:0,
                    'shopprice' => !empty($month[5][$val['szx_id']]['shopprice'])?$month[5][$val['szx_id']]['shopprice']:0,
                    'topdprice' => !empty($month[5][$val['szx_id']]['topdprice'])?$month[5][$val['szx_id']]['topdprice']:0,
                    'concertprice' => !empty($month[5][$val['szx_id']]['concertprice'])?$month[5][$val['szx_id']]['concertprice']:0,
                );
                //恒大微购
                $month[6][0] = array(
                    'month_time' => $this->day_time,
                    'type' => '恒大微购',
                    'type_id' => 0,
                    'shopnum' => !empty($month[6][0]['shopnum'])?$month[6][0]['shopnum']:0,
                    'topdnum' => !empty($month[6][0]['topdnum'])?$month[6][0]['topdnum']:0,
                    'concertnum' => !empty($month[6][0]['concertnum'])?$month[6][0]['concertnum']:0,
                    'shopprice' => !empty($month[6][0]['shopprice'])?$month[6][0]['shopprice']:0,
                    'topdprice' => !empty($month[6][0]['topdprice'])?$month[6][0]['topdprice']:0,
                    'concertprice' => !empty($month[6][0]['concertprice'])?$month[6][0]['concertprice']:0,
                );
                //订单累加
                if($val['order_from'] == '产品订单'){
                    $month[0][$val['shop_id']]['shopnum'] = !empty($month[0][$val['shop_id']]['shopnum'])?($month[0][$val['shop_id']]['shopnum']+1):1;
                    $month[0][$val['shop_id']]['shopprice'] = !empty($month[0][$val['shop_id']]['shopprice'])?($month[0][$val['shop_id']]['shopprice']+$val['cost_price']):$val['cost_price'];
                    $month[1][$val['topd_id']]['shopnum'] = !empty($month[1][$val['topd_id']]['shopnum'])?($month[1][$val['topd_id']]['shopnum']+1):1;
                    $month[1][$val['topd_id']]['shopprice'] = !empty($month[1][$val['topd_id']]['shopprice'])?($month[2][$val['topd_id']]['shopprice']+$val['cost_price']):$val['cost_price'];
                    $month[2][$val['gszx_id']]['shopnum'] = !empty($month[2][$val['gszx_id']]['shopnum'])?($month[2][$val['gszx_id']]['shopnum']+1):1;
                    $month[2][$val['gszx_id']]['shopprice'] = !empty($month[2][$val['gszx_id']]['shopprice'])?($month[2][$val['gszx_id']]['shopprice']+$val['cost_price']):$val['cost_price'];
                    $month[3][$val['szs_id']]['shopnum'] = !empty($month[3][$val['szs_id']]['shopnum'])?($month[3][$val['szs_id']]['shopnum']+1):1;
                    $month[3][$val['szs_id']]['shopprice'] = !empty($month[3][$val['szs_id']]['shopprice'])?($month[3][$val['szs_id']]['shopprice']+$val['cost_price']):$val['cost_price'];
                    $month[4][$val['szshi_id']]['shopnum'] = !empty($month[4][$val['szshi_id']]['shopnum'])?($month[4][$val['szshi_id']]['shopnum']+1):1;
                    $month[4][$val['szshi_id']]['shopprice'] = !empty($month[4][$val['szshi_id']]['shopprice'])?($month[4][$val['szshi_id']]['shopprice']+$val['cost_price']):$val['cost_price'];
                    $month[5][$val['szx_id']]['shopnum'] = !empty($month[5][$val['szx_id']]['shopnum'])?($month[5][$val['szx_id']]['shopnum']+1):1;
                    $month[5][$val['szx_id']]['shopprice'] = !empty($month[5][$val['szx_id']]['shopprice'])?($month[5][$val['szx_id']]['shopprice']+$val['cost_price']):$val['cost_price'];
                    $month[6][0]['shopnum'] = !empty($month[6][0]['shopnum'])?($month[6][0]['shopnum']+1):1;
                    $month[6][0]['shopprice'] = !empty($month[6][0]['shopprice'])?($month[6][0]['shopprice']+$val['cost_price']):$val['cost_price'];
                }elseif($val['order_from'] == '店铺开店'){
                    $month[0][$val['shop_id']]['topdnum'] = !empty($month[0][$val['shop_id']]['topdnum'])?($month[0][$val['shop_id']]['topdnum']+1):1;
                    $month[0][$val['shop_id']]['topdprice'] = !empty($month[0][$val['shop_id']]['topdprice'])?($month[0][$val['shop_id']]['topdprice']+$val['cost_price']):$val['cost_price'];
                    $month[1][$val['topd_id']]['topdnum'] = !empty($month[1][$val['topd_id']]['topdnum'])?($month[1][$val['topd_id']]['topdnum']+1):1;
                    $month[1][$val['topd_id']]['topdprice'] = !empty($month[1][$val['topd_id']]['topdprice'])?($month[1][$val['topd_id']]['topdprice']+$val['cost_price']):$val['cost_price'];
                    $month[2][$val['gszx_id']]['topdnum'] = !empty($month[2][$val['gszx_id']]['topdnum'])?($month[2][$val['gszx_id']]['topdnum']+1):1;
                    $month[2][$val['gszx_id']]['topdprice'] = !empty($month[2][$val['gszx_id']]['topdprice'])?($month[2][$val['gszx_id']]['topdprice']+$val['cost_price']):$val['cost_price'];
                    $month[3][$val['szs_id']]['topdnum'] = !empty($month[3][$val['szs_id']]['topdnum'])?($month[3][$val['szs_id']]['topdnum']+1):1;
                    $month[3][$val['szs_id']]['topdprice'] = !empty($month[3][$val['szs_id']]['topdprice'])?($month[3][$val['szs_id']]['topdprice']+$val['cost_price']):$val['cost_price'];
                    $month[4][$val['szshi_id']]['topdnum'] = !empty($month[4][$val['szshi_id']]['topdnum'])?($month[4][$val['szshi_id']]['topdnum']+1):1;
                    $month[4][$val['szshi_id']]['topdprice'] = !empty($month[4][$val['szshi_id']]['topdprice'])?($month[4][$val['szshi_id']]['topdprice']+$val['cost_price']):$val['cost_price'];
                    $month[5][$val['szx_id']]['topdnum'] = !empty($month[5][$val['szx_id']]['topdnum'])?($month[5][$val['szx_id']]['topdnum']+1):1;
                    $month[5][$val['szx_id']]['topdprice'] = !empty($month[5][$val['szx_id']]['topdprice'])?($month[5][$val['szx_id']]['topdprice']+$val['cost_price']):$val['cost_price'];
                    $month[6][0]['topdnum'] = !empty($month[6][0]['topdnum'])?($month[6][0]['topdnum']+1):1;
                    $month[6][0]['topdprice'] = !empty($month[6][0]['topdprice'])?($month[6][0]['topdprice']+$val['cost_price']):$val['cost_price'];
                }elseif($val['order_from'] == '演唱会门票'){
                    $month[0][$val['shop_id']]['concertnum'] = !empty($month[0][$val['shop_id']]['concertnum'])?($month[0][$val['shop_id']]['concertnum']+1):1;
                    $month[0][$val['shop_id']]['concertprice'] = !empty($month[0][$val['shop_id']]['concertprice'])?($month[0][$val['shop_id']]['concertprice']+$val['cost_price']):$val['cost_price'];
                    $month[1][$val['topd_id']]['concertnum'] = !empty($month[1][$val['topd_id']]['concertnum'])?($month[1][$val['topd_id']]['concertnum']+1):1;
                    $month[1][$val['topd_id']]['concertprice'] = !empty($month[1][$val['topd_id']]['concertprice'])?($month[1][$val['topd_id']]['concertprice']+$val['cost_price']):$val['cost_price'];
                    $month[2][$val['gszx_id']]['concertnum'] = !empty($month[2][$val['gszx_id']]['concertnum'])?($month[2][$val['gszx_id']]['concertnum']+1):1;
                    $month[2][$val['gszx_id']]['concertprice'] = !empty($month[2][$val['gszx_id']]['concertprice'])?($month[2][$val['gszx_id']]['concertprice']+$val['cost_price']):$val['cost_price'];
                    $month[3][$val['szs_id']]['concertnum'] = !empty($month[3][$val['szs_id']]['concertnum'])?($month[3][$val['szs_id']]['concertnum']+1):1;
                    $month[3][$val['szs_id']]['concertprice'] = !empty($month[3][$val['szs_id']]['concertprice'])?($month[3][$val['szs_id']]['concertprice']+$val['cost_price']):$val['cost_price'];
                    $month[4][$val['szshi_id']]['concertnum'] = !empty($month[4][$val['szshi_id']]['concertnum'])?($month[4][$val['szshi_id']]['concertnum']+1):1;
                    $month[4][$val['szshi_id']]['concertprice'] = !empty($month[4][$val['szshi_id']]['concertprice'])?($month[4][$val['szshi_id']]['concertprice']+$val['cost_price']):$val['cost_price'];
                    $month[5][$val['szx_id']]['concertnum'] = !empty($month[5][$val['szx_id']]['concertnum'])?($month[5][$val['szx_id']]['concertnum']+1):1;
                    $month[5][$val['szx_id']]['concertprice'] = !empty($month[5][$val['szx_id']]['concertprice'])?($month[5][$val['szx_id']]['concertprice']+$val['cost_price']):$val['cost_price'];
                    $month[6][0]['concertnum'] = !empty($month[6][0]['concertnum'])?($month[6][0]['concertnum']+1):1;
                    $month[6][0]['concertprice'] = !empty($month[6][0]['concertprice'])?($month[6][0]['concertprice']+$val['cost_price']):$val['cost_price'];
                }
                $tem = array(
                    'oid' => $val['oid'],
                    'order_from' => $val['order_from'],
                    'status' => 'succ',
                    'statustime' => $this->day_time,
                );
                app::get('syshdtj')->model('status')->save($tem);
            }
            $tem = array_merge($month[0], $month[1], $month[2], $month[3], $month[4], $month[5], $month[6]);
            foreach($tem as $key => $val){
                app::get('syshdtj')->model('month')->save($val);
            }
            $db->commit();
        } catch (Exception $e){
            $db->rollback();
            throw $e;
        }
    }
}
