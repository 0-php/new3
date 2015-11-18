<?php
class syshdtj_api_getStatData{

    public $apiDescription = "获取商家统计的数据";
    public function getParams()
    {
        //个字段注意格式
        $return['params'] = array(
            'inforType' => ['type'=>'string','valid'=>'required', 'default'=>'', 'example'=>'','description'=>'传入的类型 一共有4种（trade,tradecount,item,itemcount）'],
            'timeType' => ['type'=>'string','valid'=>'required', 'default'=>'', 'example'=>'','description'=>'传入的时间类型 一共有6种(yesterday,beforday,week,month,selecttime,select)'],
            'starttime' => ['type'=>'string','valid'=>'', 'default'=>'', 'example'=>'','description'=>'起始时间段。如：2015/05/15-2015/05/15'],
            'endtime' => ['type'=>'string','valid'=>'', 'default'=>'', 'example'=>'','description'=>'结束时间段。如：2015/05/03-2015/05/03'],
            'limit' => ['type'=>'int','valid'=>'', 'default'=>'', 'example'=>'','description'=>'查询限制的条数'],
            'start' => ['type'=>'int','valid'=>'', 'default'=>'', 'example'=>'','description'=>'查询开始的条数'],
            'orderBy' => ['type'=>'string','valid'=>'', 'default'=>'', 'example'=>'','description'=>'查询按什么排序'],
            'dataType' => ['type'=>'string','valid'=>'', 'default'=>'', 'example'=>'','description'=>'获取的数据类型'],
            'tradeType' =>['type'=>'string','valid'=>'', 'default'=>'', 'example'=>'','description'=>'报表ajax请求的数据类型'],
        );
        return $return;
    }
    public function getStatData($params)
    {
        pr($params);
        if($params['oauth'])
        {
            $shopId =$params['oauth']['account_id']; //公众店铺id
        }
        $params['timefile'] = array('starttime'=>$params['starttime'],'endtime'=>$params['endtime']); //时间范围
        $statLibTrade = kernel::single('syshdtj_data_shoptrade');

        if($params['timeType']=='yesterday' || $params['timeType']=='beforday') //昨天或者前天
        {
            $sysTrade = $statLibTrade->getTimeInfo($params['inforType'],$params['timeType'],$shopId,$params['timefile'],$params['limit'],$params['start'],$params['orderBy']);

            $sysTradeData = $sysTrade['commonday'];
            $sysData = $statLibTrade->getTimeInfo($params['inforType'],$params['timeType'],$shopId,$params['timefile']);
            $sysstat = $statLibTrade->getStatInfo($sysData,$params['timeType']);
        } else {
            $sysTradeData = $statLibTrade->getTimeInfo($params['inforType'],$params['timeType'],$shopId,$params['timefile'],$params['limit'],$params['start'],$params['orderBy']);
            pr($sysTradeData);
            $sysData = $statLibTrade->getTimeInfo($params['inforType'],$params['timeType'],$shopId,$params['timefile']);
            $sysstat = $statLibTrade->getStatInfo($sysData,$params['timeType']);
        }

        if($params['dataType']=='graphall')
        {
            $dataInfo = $statLibTrade->getTimeInfo($params['inforType'],$params['timeType'],$shopId,$params['timefile']);
            if($params['timeType'] == 'yesterday' || $params['timeType'] == 'beforday')
            {
                $data = $dataInfo['commonday'];
            }
            else
            {
                $data = $dataInfo;
            }
            $datas = $statLibTrade->tradegraphdata($params['tradeType'],$data,$params['timeType'],$params['timefile']);
            return $datas;
        }
        if($params['dataType']=='itemgraphall')
        {
            $data = $statLibTrade->getTimeInfo($params['inforType'],$params['timeType'],$shopId,$params['timefile'],$params['limit'],null,$params['orderBy']);
            if($params['timeType']=='selecttime')
            {
                $datas = $statLibTrade->itemgraphdata($params['tradeType'],$data,$params['timeType'],$params['timefile']);
            }
            else
            {
                $datas = $statLibTrade->itemgraphdata($params['tradeType'],$data,$params['timeType']);
            }
            return $datas;
        }
        else
        {
            $count =  $statLibTrade->getTimeInfo($params['inforType'],$params['timeType'],$shopId,$params['timefile']);
            $data = array(
                'sysTrade'=>$sysTrade,
                'sysTradeData'=>$sysTradeData,
                'sysstat'=>$sysstat,
                'count'=>$count
            );
            return $data;
        }
    }
}
