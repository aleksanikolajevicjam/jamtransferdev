<?

	require_once $_SERVER['DOCUMENT_ROOT'] . '/db/db.class.php';
	$db = new DataBaseMysql();
	 
	function getContractPrice($VehicleTypeID, $RouteID, $AgentID) {
		require_once $_SERVER['DOCUMENT_ROOT'] . '/db/db.class.php';
		$db = new DataBaseMysql();	
		global $db;

		$q5 = "SELECT * FROM v4_AgentPrices
				WHERE RouteID = ".$RouteID." AND VehicleTypeID = ".$VehicleTypeID." AND AgentID = ".$AgentID;
		$w5 = $db->RunQuery($q5);
		$cp = mysqli_fetch_object($w5);
		if (count($cp)>0) return $cp->Price;
		else return 0;
	}	

	function getContractExtrasPrice($ExtrasID, $AgentID) {
		require_once $_SERVER['DOCUMENT_ROOT'] . '/db/db.class.php';
		$db = new DataBaseMysql();	
		global $db;

		$q6 = "SELECT * FROM v4_AgentExtras
				WHERE ExtrasID = ".$ExtrasID." AND AgentID = ".$AgentID;
		$w6 = $db->RunQuery($q6);
		$cp = mysqli_fetch_object($w6);
		if (is_array($cp) && count($cp)>0) return $cp->Price;
		else return 0;
	}	