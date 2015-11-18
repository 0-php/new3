<?php
class agent_mdl_account extends dbeav_model
{
	public function modifier_agent_area($row){

		foreach($row as $k=>$list)
		{

			$tem = unserialize($list);
			$area = ""; 
			foreach ($tem['area'] as $key => $value) {
				$area .= $value . ",";
			}
			$row[$k] = $area;
		 	}		 	
		 	/*foreach($row as $k=>$list)
		 	{

		 			$tem = unserialize($list);
		 			

		 			$area = ""; 
			
			 		foreach ($tem as $key => $value) {

			 	  		$area .= substr($value,6) . ",";
					}

		 			//$row[$k] =implode(",",$tem);
					$row[$k] =$area;
				} */
				return $row;
			}
		}
