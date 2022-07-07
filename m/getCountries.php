<?
session_start();
//error_reporting(E_ALL);
require_once $_SERVER['DOCUMENT_ROOT'] . '/f/f.php';

$lang = Lang();

$filename = $_SERVER['DOCUMENT_ROOT']."/cache/countries".$lang.".json";

	// reset
	$_SESSION['CountryID'] = '';
	$_SESSION['FromID'] = '';
	$_SESSION['ToID'] = '';
	$_SESSION['fromName'] = '';
	$_SESSION['fromPlaces'] = array();


if (file_exists($filename)) {

	$handle = fopen($filename, "r");
	$contents = fread($handle, filesize($filename));
	fclose($handle);
	$noBrackets = str_replace('(','',$contents);
	$contents = str_replace(')','',$noBrackets);
	$jsonArray = json_decode($contents, true);

	unset($_SESSION['countries']);
	foreach ($jsonArray as $key => $value)
	{
		$_SESSION['countries'][$key] = $value;
	}
	


}

else {
	require_once $_SERVER['DOCUMENT_ROOT'].'/db/db.class.php';

	$db = new DataBaseMysql();


	$countries = array();

	# Find all routes that have drivers
	$q = "SELECT DISTINCT RouteID FROM v4_DriverRoutes";

	$w = $db->RunQuery($q);



	while($d = $w->fetch_object())
	{

		# find starting and ending points for each Route
		$q1 = "SELECT FromID, ToID FROM v4_Routes
					WHERE RouteID = '{$d->RouteID}'
					";
		$r1 = $db->RunQuery($q1) or die(mysql_error());

		while($r = $r1->fetch_object())
		{

			# Get Place Country id's
			$q2 = "SELECT * FROM v4_Places
						WHERE PlaceID = '{$r->FromID}'
						OR PlaceID = '{$r->ToID}'
						";
			$w2 = $db->RunQuery($q2) or die(mysql_error());

			while ($p = $w2->fetch_object())
			{
				# Get Country Names
				$q3 = "SELECT * FROM v4_Countries
							WHERE CountryID = '{$p->PlaceCountry}'
							";
				$r3 = $db->RunQuery($q3) or die(mysql_error());

				$c = $r3->fetch_object();

		        # Check for duplicates and add to array
		        //$countryName = strtolower($c->CountryName);

	        			$cnLang = 'CountryName'. Lang();
						if(strlen($cnLang) == 11) $cnLang = 'CountryNameEN';
	        
		        		$countryName = strtolower($c->$cnLang);

						if(empty($countryName)) $cnLang = 'CountryNameEN';
		        
		        $countryName = strtolower($c->$cnLang);
		        
		        if (!empty($countryName))
				if (!in_array(trim($countryName) . '|' . trim($c->CountryName),$countries)) $countries[$c->CountryID] = trim($countryName) . '|' . trim($c->CountryName);
			}
		}


	}

	# Sort by name
	asort($countries);
	$_SESSION['countries'] = $countries;
	$jsonCountries = json_encode($countries);

	//$filename = '../cache/countries.json';
	$handle = fopen($filename, "w");
	$contents = fwrite($handle, $jsonCountries);
	fclose($handle);

	unset($countries);
}

