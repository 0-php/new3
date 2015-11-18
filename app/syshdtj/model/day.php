<?php
class syshdtj_mdl_day extends dbeav_model
{
	public function get_pay_money($filter=null)
	{
        $this->filter = $filter;
        $db = app::get('ectools')->database();
        $qb = $db->createQueryBuilder();
        return $qb->select(
            'sum(P.countnum) as countnum,
            sum(P.shopnum) as shopnum,
            sum(P.topdnum) as topdnum,
            sum(P.concertnum) as concertnum,
            sum(P.countprice) as countprice,
            sum(P.shopprice) as shopprice,
            sum(P.topdprice) as topdprice,
            sum(P.concertprice) as concertprice,
            sum(P.gprice) as gprice,
            sum(P.tprice) as tprice,
            sum(P.gxprice) as gxprice,
            sum(P.xzsprice) as xzsprice,
            sum(P.xzshiprice) as xzshiprice,
            sum(P.xzxprice) as xzxprice,
            sum(P.hdvgprice) as hdvgprice'
            )
           ->from('syshdtj_day', 'P')
           ->where($qb->expr()->andX(
               $qb->expr()->gte('P.day_time', intval($filter['time_from'])),
               $qb->expr()->lte('P.day_time', intval($filter['time_to']))
           ))->execute()->fetch();
	}

	public function count($filter=null)
	{
        $filter=$this->filter;
        $db = app::get('ectools')->database();
        $qb = $db->createQueryBuilder();
        $qb->select('count(*) as _count')
           ->from('syshdtj_day', 'P')
           ->where($qb->expr()->andX(
               $qb->expr()->gte('P.day_time', $db->quote($filter['time_from'], \PDO::PARAM_INT)),
               $qb->expr()->lte('P.day_time', $db->quote($filter['time_to'], \PDO::PARAM_INT))
           ));
        return $qb->execute()->fetchColumn();
	}

	public function getlist($cols='*', $filter=array(), $offset=0, $limit=-1, $orderBy=null)
	{
        $filter=$this->filter;
        $offset = (int)$offset<0 ? 0 : $offset;
        $limit = (int)$limit < 0 ? 100000 : $limit;
        $db = app::get('ectools')->database();
        $qb = $db->createQueryBuilder();
        $qb->select('*')
            ->from('syshdtj_day', 'P')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->where($qb->expr()->andX(
                $qb->expr()->gte('P.day_time', $db->quote($filter['time_from'])),
                $qb->expr()->lte('P.day_time', $db->quote($filter['time_to']))
            ));
        $rows = $qb->execute()->fetchAll();
		return $rows;
	}
}
