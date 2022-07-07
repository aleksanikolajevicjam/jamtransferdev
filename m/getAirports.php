<?
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/f/f.php';
$lang = Lang();

	// reset
	$_SESSION['CountryID'] = '';
	$_SESSION['FromID'] = '';
	$_SESSION['ToID'] = '';
	$_SESSION['fromName'] = '';
	$_SESSION['fromPlaces'] = array();


$filename = $_SERVER['DOCUMENT_ROOT']."/cache/airports".$lang.".json";

if (file_exists($filename)) {

	$handle = fopen($filename, "r");
	$contents = fread($handle, filesize($filename));
	fclose($handle);
	$noBrackets = str_replace('(','',$contents);
	$contents = str_replace(')','',$noBrackets);
	$jsonArray = json_decode($contents, true);

	unset($_SESSION['fromPlaces']);
	foreach ($jsonArray as $key => $value)
	{
		$_SESSION['fromPlaces'][$key] = $value;
	}

}



else {

	require_once $_SERVER['DOCUMENT_ROOT'].'/db/db.class.php';
	$db = new DataBaseMysql();



	$fromPlaces = array();
/* ovo san maka jer izgleda jebe stvar kad se krene od Airports

	# save variables for later usage
	$_SESSION['CountryID'] = array_search(str_replace('_',' ',$_SESSION['lastElement']), $_SESSION['countries']);
	$cID = $_SESSION['CountryID'];
	$_SESSION['countryName'] = $_SESSION['countries'][$cID];
*/


	# Terminals
	$q  = "SELECT * FROM v4_Places ";
	$q .= " WHERE (PlaceType = '1'";
	$q .= " OR PlaceType = '3'";
	$q .= " OR PlaceType = '6'";
	$q .= " OR PlaceType = '8'";
	$q .= " OR PlaceType = '9')";
	$q .= " AND PlaceActive= '1'";	
	$q .= " ORDER BY PlaceType, PlaceName".Lang()." ASC";

	$w = $db->RunQuery($q);

	while($p = $w->fetch_object())
	{
		# Routes for selected place
		$q1 = "SELECT FromID, ToID, RouteID FROM v4_Routes
					WHERE FromID = '{$p->PlaceID}'
					OR    ToID   = '{$p->PlaceID}'
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
			if  ($w2->num_rows > 0)
			{
				if($p->PlaceActive != '0') {
        			$pnLang = 'PlaceName'. Lang();
					if(strlen($pnLang) == 9) $pnLang = 'PlaceNameEN';
        
	        		$placeName = strtolower($p->$pnLang);

					if(empty($placeName)) $pnLang = 'PlaceNameEN';
	     
			        $placeName = strtolower($p->$pnLang);	
					# Add Place to array
					if (!in_array(trim($placeName) . '|' . trim($p->PlaceNameSEO),$fromPlaces)) {
						$fromPlaces[$p->PlaceID] = trim($placeName) . '|' . trim($p->PlaceNameSEO);
					}
				}
			}					
		}
	

	} 

	asort($fromPlaces);
	$_SESSION['fromPlaces'] = $fromPlaces;
	$jsonFromPlaces = json_encode($fromPlaces);
	
	$handle = fopen($filename, "w");
	$contents = fwrite($handle, $jsonFromPlaces);
	fclose($handle);
	unset($fromPlaces);
}

