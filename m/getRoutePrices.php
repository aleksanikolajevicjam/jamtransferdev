<?
//require_once '../config.php';
/*
	require_once ROOT . '/db/db.class.php';
	
	$db = new DataBaseMysql();
	
function getRoutePrices($fromID, $toID) {
	global $db;
	$prices = array();

	$places = array();

		# Routes for selected place
		$q1 = "SELECT FromID, ToID, RouteID FROM v4_Routes
					WHERE 
					(FromID = '{$fromID}'
					AND    ToID   = '{$toID}')
					OR
					(FromID = '{$toID}'
					AND    ToID   = '{$fromID}')				
					
					";
		$r1 = $db->RunQuery($q1);
		

		while($r = mysqli_fetch_object($r1))
		{
			
			# DriverRoutes - check if anyone drives from that Place
			$q2 = "SELECT DISTINCT RouteID FROM v4_DriverRoutes
						WHERE RouteID = '{$r->RouteID}' 
						";
			$w2 = $db->RunQuery($q2);
			
			# If does
			if  (mysqli_num_rows($w2) > 0)
			{

			# Services 
			$q3 = "SELECT * FROM v4_Services
						WHERE RouteID = '{$r->RouteID}' AND ServicePrice1 != 0 
						ORDER BY ServicePrice1 ASC";
			$w3 = $db->RunQuery($q3);
			while($s = mysqli_fetch_object($w3)) {
				$q4 = "SELECT * FROM v4_VehicleTypes
							WHERE VehicleTypeID = '{$s->VehicleTypeID}' "; 
				$w4 = $db->RunQuery($q4);
				$v = mysqli_fetch_object($w4);
				
				$type = $v->Max; // bilo VehicleTypeID - promjena 2016-05-25
				
				$sp = nf( calculateBasePrice($s->ServicePrice1, $s->OwnerID));
				
				// proveriti prisustvo route i vehicle type u tabeli ugovorenih cena za agenta, 
				// ukoliko postoji preuzeti ugovoreni cenu i formirati flag da je cena iz ugovora
				/*$AgentID=$_SESSION['AuthUserID']; 
				$VehicleTypeID=$s->VehicleTypeID;
				$RouteID=$r->RouteID;
				$q5 = "SELECT * FROM v4_AgentPrices
						WHERE RouteID = ".$RouteID." AND VehicleTypeID = ".$VehicleTypeID." AND AgentID = ".$AgentID;
				$w5 = $db->RunQuery($q5);
				$cp = mysqli_fetch_object($w5);
				if (count($cp)>0) $prices[$type]=$cp->Price;
				else {		
					if(array_key_exists($type, $prices) ) {
						if($prices[$type] > $sp) {
							$prices[$type] = $sp;
						}
					} else {				
						$prices[$type] = $sp;
					}
				}	*/			
				/*
				
				if(array_key_exists($type, $prices) ) {
					if($prices[$type] > $sp) {
						$prices[$type] = $sp;
					}
				} else {
				
					$prices[$type] = $sp;
				}
			}
			}

		}
	return $prices;
}


function calculateBasePrice($price, $ownerid) {
	global $db;
	
		# Driver
		$q = "SELECT * FROM v4_AuthUsers
					WHERE AuthUserID = '" .$ownerid."' 
					";
		$w = $db->RunQuery($q);
		
		$d = mysqli_fetch_object($w);
		
		if($d->AuthUserID == $ownerid) {

		if ($price >= $d->R1Low and $price <= $d->R1Hi) return $price + ($price*$d->R1Percent / 100);
		else if ($price >= $d->R2Low and $price <= $d->R2Hi) return $price + ($price*$d->R2Percent / 100);
		else if ($price >= $d->R3Low and $price <= $d->R3Hi) return $price + ($price*$d->R3Percent / 100);
		else return $price;
		}
		
		return '0';		
}

