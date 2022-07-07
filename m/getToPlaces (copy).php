<?
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/f/f.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/db/db.class.php';
$db = new DataBaseMysql();

$fromName = $_SESSION['lastElement'];
//echo $fromName;


$qfid = "SELECT * FROM v4_Places WHERE PlaceNameSEO = '" . $fromName . "'"; 
$wfid = $db->RunQuery($qfid);


if($wfid->num_rows != 0) {
	$fidObj = $wfid->fetch_object();
	$fID = $fidObj->PlaceID;	
} else {
	$_SESSION['toPlaces'] = array();
	echo 'Error';
	die();
}



//$fID = $_SESSION['fromID'];

# save variables for later usage
$_SESSION['fromName'] = $_SESSION['fromPlaces'][$fID];
//echo $_SESSION['fromName'];
//echo '<pre>'; print_r($_SESSION['fromPlaces']); echo '</pre>';

$places = array();

	# Routes for selected place
	$q1 = "SELECT FromID, ToID, RouteID FROM v4_Routes
				WHERE FromID = '{$fID}'
				OR    ToID   = '{$fID}'
				";
	$r1 = $db->RunQuery($q1);
	

	while($r = $r1->fetch_object())
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
			$s = mysqli_fetch_object($w3);
			$price = calculateBasePrice($s->ServicePrice1, $s->OwnerID);

	
			# Places
			$q  = "SELECT * FROM v4_Places ";
			$q .= " WHERE PlaceID  = ".$r->FromID;
			$q .= " OR    PlaceID  = ".$r->ToID;

			$w = $db->RunQuery($q);
			
			while ($p = mysqli_fetch_object($w))
			{
			
				if($p->PlaceActive != '0') {
					# Do not repeat the From name
					if ($p->PlaceID != $fID) {
						# Add Place to array
						//if (!in_array($p->PlaceNameEN,$places)) {
						//	$places[$p->PlaceID] = trim($p->PlaceNameEN.' ' . $price) . '|' . trim($p->PlaceNameSEO);
						//}
	        	$pnLang = 'PlaceName'. Lang();
	        
		        $placeName = strtolower($p->$pnLang);			
				
						$places[$p->PlaceID] = array(
							"name" => trim($placeName),
							"price" => $price,
							"seo"	=> trim($p->PlaceNameSEO),
							"id"	=> $s->ServiceID
							);
					
				
					}
				}
			}
		}					
	}
	



//asort($places);
$places = subval_sort($places,'name');
unset($_SESSION['toPlaces']);
$_SESSION['toPlaces'] = $places;
//echo '<pre>'; print_r($_SESSION); echo '</pre>';
unset($places);


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

