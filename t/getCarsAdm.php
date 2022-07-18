<?
/*
	napomena za Surcharges:
	0 - nema 
	1 - global + OwnerID
	2 - route + OwnerID + RouteID
	3 - vehicle + OwnerID + VehicleID
	4 - service + OwnerID + ServiceID 
	
	Podatak se nalazi u SurCategory polju, plus ova ostala polja za lookup.
	
	Logika je:
	- ako je u Services SurCategory 0,
	- pogledaj u Vehicles. Ako je i tamo nula,
	- pogledaj u Routes. Ako je i tamo nula,
	- pogledaj u SurGlobal
	
	Ovo bi trebalo pametnije rijesit.
	Kod odabira surcharges bi trebalo u sve upisat kategoriju.
	Npr. ako vozac na profilu stavi Global, onda bi u sva njegova vozila, rute i services
	trebalo odmah upisat 1.
	Ako kasnije na neku rutu stavi Route surcharges, onda bi u sve Services za tu rutu 
	trebalo upisat 2.
	Ako stavi samo za neko vozilo, onda bi u sve Services za to vozilo trebalo stavit 3.
	Ako stavi samo za jednu uslugu, onda tu ide 4.
	
	Tako bi se odmah moglo znat di triba gledat.
	
	Ako nesto kasnije promijeni, postupak bi treba bit isti.
	Npr. ako za neko vozilo stavi da nema surcharges, a prije je bilo,
	onda bi za sve Services od toga vozila trebalo stavit 0, ako je prije bilo 3.
	Ako je bilo 4, onda ne dirat.
	

*/
error_reporting(E_PARSE);
define("DEBUG", 0);

@session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/LoadLanguage.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/f/f.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/db/db.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/db/v4_Services.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/db/v4_Routes.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/db/v4_DriverRoutes.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/db/v4_AuthUsers.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/db/v4_Vehicles.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/db/v4_VehicleTypes.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/db/v4_DriverPrices.class.php';


@Blogit('api/getCarsAdm.php Program start');

//echo '<pre>'; print_r($_POST); echo '</pre>';
$db = new DataBaseMysql();

$s 	= new v4_Services();
$r 	= new v4_Routes();
$dr = new v4_DriverRoutes();
$au = new v4_AuthUsers();
$v	= new v4_Vehicles();
$vt = new v4_VehicleTypes();

$dp = new v4_DriverPrices();

// Request u varijable
// ako se kasnije nesto promijeni, ovako je lakse 

$FromID	= $_REQUEST['FromID'];
$ToID 	= $_REQUEST['ToID'];
$PaxNo	= $_REQUEST['PaxNo'];

$transferDate 	= $_REQUEST['transferDate'];
$transferTime 	= $_REQUEST['transferTime'];

if($_REQUEST['returnTransfer'] == 1) {
	$returnDate		= $_REQUEST['returnDate'];
	$returnTime		= $_REQUEST['returnTime'];
	$returnTransfer = $_REQUEST['returnTransfer'];
}
else {
	$returnTransfer = 0;
	$returnDate = '';
	$returnTime = '';
}

//@Blogit($FromID .'-'.$ToID);

// Izlazni podaci koje koriste skripte za display 
$cars = array(); // podaci o vozilima
$drivers = array(); // podaci o vozacima
$carsErrorMessage = array(); // greske
# check if such route exists
$routesKeys = $r->getKeysBy('RouteID','asc',"WHERE (FromID = {$FromID} AND ToID = {$ToID}) OR (FromID = {$ToID} AND ToID = {$FromID})");

if(count($routesKeys) == 0) {
	$carsErrorMessage['title'] = ROUTE_NOT_FOUND;
	$carsErrorMessage['text'] =  CHECK_FROM_TO;
}
else {


	foreach($routesKeys as $ki => $id) {
		$r->getRow($id);
		$Km = $r->getKm();
		$Duration = $r->getDuration();
		
		$drWhere = "WHERE RouteID = {$id} AND Active = '1'";
		
		// check for drivers for the route
		$driverRouteKeys = $dr->getKeysBy('OwnerID', "ASC", $drWhere);
		if (count($driverRouteKeys) == 0) {
			$carsErrorMessage['title'] = NO_DRIVERS;
			$carsErrorMessage['text'] = NO_DRIVERS_EXT;
		}
		else {
			// ako su pronadjene DriverRoutes, obradi svaku
			foreach($driverRouteKeys as $dri => $rowId) {
                if($dr->getRow($rowId)===false) continue;
                if($dr->getFromID() == $FromID  and $dr->getOneToTwo() == '0') continue;
                if($dr->getFromID() == $ToID  and $dr->getTwoToOne() == '0') continue;
				if ($returnTransfer) {
					if ($dr->getToID() == $FromID and $dr->getOneToTwo() == '0') continue;
					if ($dr->getToID() == $ToID and $dr->getTwoToOne() == '0') continue;
				}				
                $OwnerID = $dr->getOwnerID();
                if($au->getRow($OwnerID)===false) continue;

				// Driver Profiles iz v4_AuthUsers
				//$Driver = $au->getAuthUserName();
				$DriverCompany = $au->getAuthUserCompany();
				$ContractFile = $au->getContractFile();
				$ProfileImage = 'http://team.taxido.net/' . $au->getImage();
				if($au->getImage() == '') $ProfileImage = 'i/noImage.png';
			
				// ovo je sranje, jer se izgleda ne moze vjerovati getRow funkciji
				// ona ne vraca false ako ne nadje pravi slog!
				// zato ova usporedba
				//if($OwnerID !== $au->getAuthUserID()) break;	
				if(DEBUG) {
					$driverPricesKeys = $dp->getKeysBy('ID', "ASC", "WHERE RouteID = '" . $id .
					"' AND DriverID = '" . $OwnerID ."'");
					foreach( $driverPricesKeys as $cnt => $ID) 
					{
						$dp->getRow($ID);
						echo $au->getAuthUserRealName(). ' ' . $dp->getVehicleTypeID() . ' ' . 
						$dp->getSinglePrice() . '<br>';
		
					}
				} // END DEBUG


			

				
				// check for Services
				$serviceKeys = $s->getKeysBy("ServiceID", "ASC", "WHERE RouteID = {$id} AND OwnerID = {$OwnerID} AND Active = '1'");
				if(count($serviceKeys) == 0) {

					$carsErrorMessage['title'] = NO_VEHICLES;
					$carsErrorMessage['text'] =  NO_VEHICLES_EXT;
					//logit('Error '.$OwnerID);
				}
				else {
					foreach($serviceKeys as $si => $sId) {
						$s->getRow($sId);
						$ServiceID = $s->getServiceID();
						//
						if (DEBUG) echo $ServiceID . '<br>';
						if (DEBUG) echo $OwnerID . '<br>';
						//
						$Correction= $s->getCorrection();
						
						$v->getRow($s->getVehicleID());
						
						//$VehicleName 	= $v->getVehicleName();
						$VehicleName	= vehicleTypeName( $v->getVehicleTypeID() );
						$VehicleTypeID 	= $v->getVehicleTypeID();
						$VehicleCapacity= $v->getVehicleCapacity();
						// novo - wifi na nivou vozila
						$hasWiFi = $v->getAirCondition();
						
						$VehicleID 		= $v->getVehicleID();
						$ReturnDiscount = $v->getReturnDiscount();
						$VehicleImageRoot = "https://" . $_SERVER['HTTP_HOST'];
						if ($VehicleCapacity > 15) $vehicleImageFile = '/i/cars/bus.png';
						
						$vt->getRow($VehicleTypeID);
						$VehicleClass   = $vt->getVehicleClass();
												
						$VehicleDescription = $vt->getDescription();
						$VehicleImage=getCarImage($vt->getVehicleClass());

						/*

							Ovdje upada dio sa izracunavanjem cijena ovisno o:
							- return discount
							- danu u tjednu
							- sezoni
							- je li nocna voznja

							Sve te faktore treba prikazati kupcu kao dodatak na osnovnu cijenu.

						*/
							$SurCategory 	= $s->getSurCategory();
							$DRSurCategory 	= $dr->getSurCategory();
							$VSurCategory 	= $v->getSurCategory();
							$sur = array();
							$sur = Surcharges($OwnerID, $SurCategory, $s->getServicePrice1(), 
											  $transferDate, $transferTime, 
											  $returnDate, $returnTime, 
											  $dr->getID(), $VehicleID, $ServiceID,
  											  $VSurCategory, $DRSurCategory
											  );

							$addToPrice =   
											$sur['MonPrice'] +
											$sur['TuePrice'] +
											$sur['WedPrice'] +
											$sur['ThuPrice'] +
											$sur['FriPrice'] +
											$sur['SatPrice'] +
											$sur['SunPrice'] +
											$sur['S1Price'] +
											$sur['S2Price'] +
											$sur['S3Price'] +
											$sur['S4Price'] +
											$sur['S5Price'] +
											$sur['S6Price'] +
											$sur['S7Price'] +
											$sur['S8Price'] +
											$sur['S9Price'] +
											$sur['S10Price'] +
											$sur['NightPrice'];	
							if($returnTransfer) {
								// cijena za jedan smjer
								$DriversPrice = $s->getServicePrice1();
								
								// izracun popusta na Return transfer
								$DiscountPrice = $DriversPrice - ($DriversPrice * $ReturnDiscount / 100);
								
								// finalna cijena vozaca
								$DriversPrice = $DriversPrice + $DiscountPrice + $addToPrice; 
								
                                $specialDatesPrice = calculateSpecialDates($OwnerID,$DriversPrice,$transferDate, $transferTime, $returnDate, $returnTime);
                                $DriversPrice = $DriversPrice + $specialDatesPrice;
							}
						
							else {
								// inace je jedan smjer, pa dodaci idu odmah
								$DriversPrice = $s->getServicePrice1() + $addToPrice;
								//$DriversPrice = $s->getServicePrice1();
								
                                $specialDatesPrice = calculateSpecialDates($OwnerID,$DriversPrice,$transferDate, $transferTime);
                                $DriversPrice = $DriversPrice + $specialDatesPrice;		
								
							}
							// na finalnu cijenu vozaca dodaj proviziju
							$BasePrice = calculateBasePrice($DriversPrice, $s->getOwnerID(), $VehicleClass);
							// zaokruzenje cijena
							//$BasePrice = nf( round($BasePrice,0,PHP_ROUND_HALF_UP) );
							$BasePrice = nf( round($BasePrice,2) );
						
						/*
						** KRAJ OBRADE CIJENA
						*/

							// premjesteno od dole, tako da se upoce ne uzimaju u obzir podaci
							// ako vozac nije aktivan ili ne vozi odredjene datume
							$okToAdd = true;

							# nemoj dodati cijene ako driver nije Active!!!
							if($au->getActive() == 0) $okToAdd = false;
							
							if(isVehicleOffDuty($VehicleID, $transferDate, $transferTime)) $okToAdd = false;
							
							if($returnDate != '') {
								if(isVehicleOffDuty($VehicleID, $returnDate, $returnTime)) $okToAdd = false;
							}

							// ugovor sa KLM, preskacu se servisi koji ne pripadaju 1650.
							$klm=array(1629,2829,2857);
							$contractdrivers=array(836,1650,556,2113);
							if ((in_array($_REQUEST['AgentID'], $klm) && !in_array($OwnerID, $contractdrivers))) $okToAdd = false;			


						
						// sortiranje top drivera ispred ostalih
						// kako mora biti sortirano i po cijeni 
						// onda se cijena mnozi sa 11-rating (tako da ako je rating 10, mnozi se sa 1)
						// znaci ako je rating veci, rating cijena je manja
						// pa vozac izlazi ispred
						$Rating = $BasePrice * (11 - ShowRatings($OwnerID));

						// ako je vozilo dovoljno veliko,
						// spremi podatke i profil

						if($VehicleCapacity >= $PaxNo and $okToAdd == true) {
							//logit('Radim: ' . $OwnerID);

							// Za isti tip vozila prikazi samo najpovoljniju cijenu
							$keyFound = '';
							foreach($cars as $key => $niz) {
								//$logVar = 'niz '.$niz['VehicleTypeID'] . '-' . $niz['BasePrice'];
								
								if($niz['VehicleTypeID'] == $VehicleTypeID and $niz['BasePrice'] > 0) {
									$keyFound = $key;
									
									break;
								}
							}
							

			//@Blogit($BasePrice);				
							//if($BasePrice == 0) $okToAdd = false;
							
							if($okToAdd) {
								$sortHelpClass 		= 1000+$VehicleClass;
								$sortHelpCapacity 	= 1000+$VehicleCapacity;
								$sortBy = $sortHelpCapacity.$sortHelpClass;
								
								
								$cars[] = array(
									'RouteID'			=> $id,
									'OwnerID'			=> $OwnerID,
									'DriverCompany'		=> $DriverCompany,
									'ContractFile'		=> $ContractFile, 
									'ProfileImage'		=> $ProfileImage,
									'ServiceID' 		=> $ServiceID,
									'VehicleID' 		=> $VehicleID,
									'VehicleTypeID' 	=> $VehicleTypeID,
									'VehicleName'		=> $VehicleName,
									'VehicleImage'		=> $VehicleImage,
									'VehicleCapacity'	=> $VehicleCapacity,
									'VehicleClass'		=> $VehicleClass,
									'WiFi'				=> $hasWiFi,
									'VehicleSort'		=> $sortBy,
									'VehicleDescription'=> $VehicleDescription,
									'BasePrice'			=> $BasePrice,		// !!!!!!!!
									'DriversPrice'		=> $DriversPrice,
									'Rating'			=> $Rating,
									'NightPrice'		=> $sur['NightPrice'],
									'MonPrice'			=> $sur['MonPrice'],
									'TuePrice'			=> $sur['TuePrice'],
									'WedPrice'			=> $sur['WedPrice'],
									'ThuPrice'			=> $sur['ThuPrice'],
									'FriPrice'			=> $sur['FriPrice'],
									'SatPrice'			=> $sur['SatPrice'],
									'SunPrice'			=> $sur['SunPrice'],
									'S1Price'			=> $sur['S1Price'],
									'S2Price'			=> $sur['S2Price'],
									'S3Price'			=> $sur['S3Price'],
									'S4Price'			=> $sur['S4Price'],
									'S5Price'			=> $sur['S5Price'],
									'S6Price'			=> $sur['S6Price'],
									'S7Price'			=> $sur['S7Price'],
									'S8Price'			=> $sur['S8Price'],
									'S8Price'			=> $sur['S8Price'],
									'S10Price'			=> $sur['S10Price'],
									'Km'				=> $Km,
									'Duration'			=> $Duration
								);

								// ako Driver ima odgovarajuce vozilo, 
								// popuni podatke o profilu 
								// Driver Profiles iz v4_AuthUsers
								$drivers[$OwnerID] = array(
											'DriverCompany'		=> $DriverCompany,
											'ProfileImage'		=> $ProfileImage,
											'RealName'			=> $au->getAuthUserRealName(),
											'Company'			=> $au->getAuthUserCompany(),
											'Address'			=> $au->getAuthCoAddress()
								);
								
								
							}
						}

					} // end foreach services

				}// end else

			} // end foreach DriverRoutes

		}
	}
}

//@Blogit($cars);

if(count($cars) == 0) {
	$carsErrorMessage['title'] = NO_VEHICLES;
	$carsErrorMessage['text'] =  TOO_SMALL;
} else {
	
	$carsErrorMessage = array(); // reset arraya za greske
	
	//$sort1 = subval_sort($cars,'VehicleSort');
	
	//$cars = $sort1;
	
	# izmjena da bi se najprije prikazalo vozilo sa najnizom cijenom
	# bez obzira na sve ostalo
	# sort1 je normalna lista, sort2 je slozen po cijeni
	# nakon toga se uzme prvi element iz sort2 i dodaju se svi osim njega iz sort1
	
	
	$sort1 = subval_sort($cars,'VehicleSort');
	$sort2 = subval_sort($cars,'BasePrice');
	
	$bestPriceServiceID = $sort2[0]['ServiceID'];
	
	$cars = array();
	
	$cars[] = $sort2[0];
	
	foreach($sort1 as $key => $arr) {
		if($sort1[$key]['ServiceID'] != $bestPriceServiceID) {
			$cars[] = $sort1[$key];
		}
	} 	
	
}



function ShowRatings($userId) {
	require_once $_SERVER['DOCUMENT_ROOT'] . '/db/v4_Ratings.class.php';
	
	$r = new v4_Ratings();
	
	$r->getRow($userId);
	
	if($r->getVotes() > 0)	return $r->getAverage() / $r->getVotes();
	else return '0';
	
	
}


// Dodavanje dogovorene provizije na osnovnu cijenu
function calculateBasePrice($price, $ownerid, $VehicleClass = 1) {
	global $db;
	
		//$priceR = round($price, 0, PHP_ROUND_HALF_DOWN);
		$priceR = round($price, 2);
	
		# Driver
		$q = "SELECT * FROM v4_AuthUsers
					WHERE AuthUserID = '" .$ownerid."' 
					";
		$w = $db->RunQuery($q);
		
		$d = mysqli_fetch_object($w);
		
		if($d->AuthUserID == $ownerid) {
		
			// STANDARD CLASS
			if($VehicleClass < 11) {
				if ($priceR >= $d->R1Low and $priceR <= $d->R1Hi) return $price + ($price*$d->R1Percent / 100);
				else if ($priceR >= $d->R2Low and $priceR <= $d->R2Hi) return $price + ($price*$d->R2Percent / 100);
				else if ($priceR >= $d->R3Low and $priceR <= $d->R3Hi) return $price + ($price*$d->R3Percent / 100);
				else return $price;
			}

			// PREMIUM CLASS
			if($VehicleClass >= 11 and $VehicleClass < 21) {
				if ($price >= $d->PR1Low and $price <= $d->PR1Hi) return $price + ($price*$d->PR1Percent / 100);
				else if ($price >= $d->PR2Low and $price <= $d->PR2Hi) return $price + ($price*$d->PR2Percent / 100);
				else if ($price >= $d->PR3Low and $price <= $d->PR3Hi) return $price + ($price*$d->PR3Percent / 100);
				else return $price;
			}

			// FIRST CLASS
			if($VehicleClass >= 21) {
				if ($price >= $d->FR1Low and $price <= $d->FR1Hi) return $price + ($price*$d->FR1Percent / 100);
				else if ($price >= $d->FR2Low and $price <= $d->FR2Hi) return $price + ($price*$d->FR2Percent / 100);
				else if ($price >= $d->FR3Low and $price <= $d->FR3Hi) return $price + ($price*$d->FR3Percent / 100);
				else return $price;
			}

		}
		
		return '0';

		
}

function vehicleTypeName($vehicleTypeID) {
	require_once $_SERVER['DOCUMENT_ROOT'] . '/db/db.class.php';
	$db = new DataBaseMysql();
	
	$w = $db->RunQuery("SELECT * FROM v4_VehicleTypes WHERE VehicleTypeID = '{$vehicleTypeID}'");
	$v = $w->fetch_object();

			    	$vehicleTypeName = 'VehicleTypeName'. Lang();
			    				    
				    $VehicleTypeName = strtolower($v->$vehicleTypeName);
	
	return $VehicleTypeName;
}

function isVehicleOffDuty($vehicleID, $transferDate, $transferTime) {
	$cnt = 0;
	require_once $_SERVER['DOCUMENT_ROOT'] . '/db/db.class.php';
	$db = new DataBaseMysql();
	
	$r = $db->RunQuery("SELECT * FROM v4_OffDuty WHERE VehicleID = '".$vehicleID."' ORDER BY ID ASC");
	
	while($o = $r->fetch_object()) {

		if( inDateTimeRange($o->StartDate, $o->StartTime, $o->EndDate, $o->EndTime, $transferDate, $transferTime) ) {
			
			$cnt += 1;
		}
		
	}
	
	if($cnt >= 1) return true;
	else return false;
}

function calculateSpecialDates($OwnerID, $amount, $transferDate, $transferTime, $returnDate='', $returnTime='') {

    if( empty($OwnerID) or empty($amount) or empty($transferDate)  or empty($transferTime) ) return 0;

    require_once $_SERVER['DOCUMENT_ROOT'] . '/db/v4_SpecialDates.class.php';
    $sd = new v4_SpecialDates();

    $add1 = 0;
    $add2 = 0;
    
    $keys = $sd->getKeysBy("ID", "ASC", " WHERE OwnerID = '" . $OwnerID ."'");
    if( count($keys) > 0) {
        foreach($keys as $nn => $ID) {
            $sd->getRow($ID);

            if( inDateTimeRange($sd->getSpecialDate(), $sd->getStartTime(), $sd->getSpecialDate(), $sd->getEndTime(), $transferDate, $transferTime) ) {
                $add1 = nf($amount * $sd->getCorrectionPercent() / 100);
            }
            
            if($returnDate != '' and $returnTime != '') {
                if( inDateTimeRange($sd->getSpecialDate(), $sd->getStartTime(), $sd->getSpecialDate(), $sd->getEndTime(), $returnDate, $returnTime) ) {
                    $add2 = nf($amount * $sd->getCorrectionPercent() / 100);
                }
            }
        }
    }
    // zbroji oba transfera
    return $add1 + $add2;
}

function getCarImage ($VehicleClass) {
	if ($VehicleClass == '1') $vehicleImageFile = '/i/cars/sedan.jpg';
	else if ($VehicleClass == '2') $vehicleImageFile = '/i/cars/minivanl.jpg';
	else if ($VehicleClass == '3') $vehicleImageFile = '/i/cars/minibusl.jpg';
	else if ($VehicleClass == '4') $vehicleImageFile = '/i/cars/minibusl.jpg';	
	else if ($VehicleClass == '5' or $VehicleClass == '6') 	$vehicleImageFile = '/i/cars/bus.jpg';	

	else if ($VehicleClass == '11') $vehicleImageFile = '/i/cars/sedan_p.jpg';
	else if ($VehicleClass == '12') $vehicleImageFile = '/i/cars/minivans_p.jpg';
	else if ($VehicleClass == '13') $vehicleImageFile = '/i/cars/minivans_p.jpg';
	else if ($VehicleClass == '14') $vehicleImageFile = '/i/cars/minibusl_p.jpg';	
	else if ($VehicleClass == '15' or $VehicleClass == '16') 	$vehicleImageFile = '/i/cars/bus_p.jpg';							

	else if ($VehicleClass == '21') $vehicleImageFile = '/i/cars/sedan_l.jpg';
	else if ($VehicleClass == '22') $vehicleImageFile = '/i/cars/minivans_l.jpg';
	else if ($VehicleClass == '23') $vehicleImageFile = '/i/cars/minivanl_l.jpg';
	else if ($VehicleClass == '24') $vehicleImageFile = '/i/cars/minibusl_l.jpg';	
	else if ($VehicleClass == '25' or $VehicleClass == '26') 	$vehicleImageFile = '/i/cars/bus_l.jpg';
	
	return$VehicleImageRoot.$vehicleImageFile;
}					