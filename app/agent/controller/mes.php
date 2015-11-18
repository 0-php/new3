<?php
	class agent_ctl_mes extends agent_controller
	{
		public function index()
		{
			$agent_id =pamAccount::getAccountId();
			$where['agent_id'] = $agent_id;
			$data['oaccount'] =  app::get('agent')->model('account')->getList('*', $where);
			$data['oagentmsg'] = app::get('agent')->model('agentmsg')->getList('*', $where);
			$arr=unserialize($data['oaccount'][0]['agent_area']);

		
			//$data['oaccount'][0]['agent_area'] = unserialize($data['oaccount'][0]['agent_area']);

			// pr(implode(',',$data['oaccount'][0]['agent_area']));
			 $area = ""; 
			
			 foreach ($arr["area"] as $key => $value) {
			 	  $area .= $value. ",";
			}
			$data['oaccount'][0]['agent_area'] = $area;
			//pr($data['oaccount'][0]['agent_area'][1]);
			$data["oagentmsg"][0]["agentmsg_register_time"]=date("Y-m-d",$data["oagentmsg"][0]["agentmsg_register_time"]);
			return $this->page('agent/agentMes.html', $data);
		}
	}
?>
