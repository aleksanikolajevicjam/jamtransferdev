<?
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/f/f.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/db/db.class.php';

$db = new DataBaseMysql();

//if(empty($_SESSION['countries']) ) 
require_once $_SERVER['DOCUMENT_ROOT'] . '/m/getCountries.php';

$fromPlaces = array();

# ako je $_SESSION['lastElement'] country, onda uzmi ime

//$_SESSION['countryID'] = array_search(str_replace('+',' ',strtolower($_SESSION['lastElement'])), $_SESSION['countries']); bilo prije jezika

foreach($_SESSION['countries'] as $countryID => $countryName) {
	// razbija country name na dva dijela npr albania (|) i Albania
	$name = explode('|', $countryName);
	
	// ako ocisceni lastElement odgovara imenu drzave, uzmi CountryID i country name
	if(strtolower(str_replace('+',' ',strtolower(trim($_SESSION['lastElement'])))) == strtolower($name[1]) ) {
		$_SESSION['CountryID']   = $countryID;
		$_SESSION['countryName'] = $name[1];
	}
	
}

// ovo se dogadja kad je $_SESSION['lastElement'] ime mjesta, a ne country
if (empty($_SESSION['CountryID'])) {
	$_SESSION['CountryID'] = getPlaceCountryFromPlaceName(strtolower($_SESSION['lastElement']));
}	

$cID = $_SESSION['CountryID'];




// Get from Cache
$filename = $_SERVER['DOCUMENT_ROOT'] . '/cache/fromPlaces'.$cID.$lang.'.json';
logit($filename);
$cachetime = 84600;

if (file_exists($filename) && time() - $cachetime < filemtime($filename)) {

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
	
	$rewriteCache = false;
	logit($filename . ' FOUND');
}
//*******************

else {




	//$_SESSION['countryName'] = $_SESSION['countries'][$cID];

	//echo '<pre>'; print_r($_SESSION); echo '</pre>';
	//if(empty($cID)) echo __FILE__.' cID je prazan<br>';

	# Places in the Country
		$q  = " SELECT * FROM v4_Places ";
		$q .= " WHERE PlaceCountry = '".$cID."'";
		$q .= " AND PlaceActive = '1'";
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


					# Add Place to array
	        			$pnLang = 'PlaceName'. Lang();
						if(strlen($pnLang) == 9) $pnLang = 'PlaceNameEN';
	        
		        		$placeName = strtolower($p->$pnLang);

						if(empty($placeName)) $pnLang = 'PlaceNameEN';
						$placeName = strtolower($p->$pnLang);				

					# Add Place to array
					if (!in_array($placeName.'|'.$p->PlaceNameSEO,$fromPlaces)) {
						$fromPlaces[$p->PlaceID] = trim($placeName) . '|' . trim($p->PlaceNameSEO);
					}
				}
			}					
		}
	

	}

# asort($fromPlaces);
# $_SESSION['fromPlaces'] = $fromPlaces;
# unset($fromPlaces);

	# Sort by name
	asort($fromPlaces);
	
	$_SESSION['fromPlaces'] = $fromPlaces;

	unset($fromPlaces);
	$rewriteCache = true;
}


if ($rewriteCache) {
	// Save to Cache	
	$jsonFromPlaces = json_encode($_SESSION['fromPlaces']);
	//$filename = $_SERVER['DOCUMENT_ROOT'] . '/cache/fromPlaces'.$cID.'.json';
	$handle = fopen($filename, "w");
	$contents = fwrite($handle, $jsonFromPlaces);
	fclose($handle);
	// ************
}
