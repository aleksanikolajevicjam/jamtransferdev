<? 
/*
	Dependencies: 
		t/booking_new.php
		js/pages/booking_new.php.js
*/
	error_reporting(E_PARSE);
	@session_start();
	require_once $_SERVER['DOCUMENT_ROOT'] . '/f/f.php';

	// LANGUAGES
	if ( isset($_SESSION['CMSLang']) and $_SESSION['CMSLang'] != '') {
		$languageFile = $_SERVER['DOCUMENT_ROOT'].'/lng/' . $_SESSION['CMSLang'] . '_text.php';
		if ( file_exists( $languageFile) ) require_once $languageFile;
		else {
			$_SESSION['CMSLang'] = 'en';
			require_once $_SERVER['DOCUMENT_ROOT'].'/lng/en.php';
		}
	}
	else {
		$_SESSION['CMSLang'] = 'en';
		require_once $_SERVER['DOCUMENT_ROOT'].'/lng/en.php';
	}

	define("SITE_CODE", '2');
	define("B", ' ');
	define("BD", ': ');
	define("NL", '<br>');
	
	
	// priprema podataka o vozilima i vozacima
	require_once $_SERVER['DOCUMENT_ROOT'] . '/cms/t/getCarsAdm.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/m/getContractPrices.php';
	
 	// prikaz Km i Duration route
	$routeDetails = array();
	$detailsDesc = '';
	if(is('FromID') and is('ToID')) {
		$routeDetails = GetRouteDetails(s('FromID'), s('ToID'));
		if($routeDetails['Km'] > 0) $detailsDesc .= $routeDetails['Km'] .' km,';
		if($routeDetails['Duration'] > 0) $detailsDesc .= $routeDetails['Duration'] .' mins';
		if($detailsDesc != '') $detailsDesc = '<span><i class="fa fa-dashboard"></i> '.$detailsDesc.'</span>';
	}

	$_SESSION['FromID']=$FromID; 
	$_SESSION['ToID']=$ToID; 
	$_SESSION['PaxNo']=$PaxNo;
	$_SESSION['transferDate']=$transferDate;

// No Errors found
if (count($carsErrorMessage) == 0) { 

?>


			<div class="row xpad1em white-text">
				<br><br>
				<hr>
					<h4 class="center ucase"><?= AVAILABLE_VEHICLES ?></h4><span id='vehiclename'></span>
				<hr>

			</div>
				
			<? 	

			$AgentID=0;
			$logo='';	
			if ($_REQUEST['AgentID']>0) {
				$AgentID=$_REQUEST['AgentID']; 
				require_once $_SERVER['DOCUMENT_ROOT'] . '/db/v4_AuthUsers.class.php';
				$au = new v4_AuthUsers();
				$au->getRow($AgentID);
				$logo=$au->getImage();	 								
			}	  
			$_SESSION['AgentID']=$_REQUEST['AgentID']; 
			$_SESSION['logo']=$logo;

			
			
			
			// za kasnije, prebaceno iz /api/getCars.php
			if (count($carsErrorMessage) == 0) {
				$cars = subval_sort($cars, 'Rating');
				$drivers = subval_sort($drivers, 'Company');
			}	
			$counter = 0;
			
			foreach($cars as $i => $carData): 

			$contract='(contracted)';
			$AgentID=$_REQUEST['AgentID']; 
			$contractPrice=getContractPrice($carData['VehicleTypeID'],$carData['RouteID'],$AgentID);
			
			$pass=true;
			$klm=array(1629,2829,2857);
			if ($contractPrice==0 && (in_array($_REQUEST['AgentID'], $klm))) $pass=false;

			if ($carData['BasePrice'] > 0 && $pass):
				$counter++;

				
				$rating = ShowRatings($carData['OwnerID']);
				
				if      ($carData['VehicleCapacity'] <= 3) $vehicleImage = '/i/cars/sedan.png';
				else if ($carData['VehicleCapacity'] <= 4) $vehicleImage = '/i/cars/taxi.png';
				else if ($carData['VehicleCapacity'] <= 8) $vehicleImage = '/i/cars/minivan.png';
				else if ($carData['VehicleCapacity'] <= 16) $vehicleImage = '/i/cars/minibus.png';
				else if ($carData['VehicleCapacity'] > 16) $vehicleImage = '/i/cars/bus.png';


				?>

				<!-- CAR PANEL -->
						
				<div class="row z-depth-2 white lighten-5 center">
							
				
					<div class="col-md-5 white">
						<img class="" src="<?= $carData['VehicleImage'] ?>" style="max-height:20%; max-width:20%;" alt="car">
						<span style="text-transform:uppercase; font-weight:100 !important"><?= $carData['VehicleName']?></span>
						<h5><b style='color:blue'><?= $carData['DriverCompany']?></b></h5>
					</div>

					<!-- car image and capacity -->
					
					<!-- driver profile and ratings -->
					
					<div class="col-md-4 row">
						<?
						$addToPrice =   $carData['MonPrice'] +
										$carData['TuePrice'] +
										$carData['WedPrice'] +
										$carData['ThuPrice'] +
										$carData['FriPrice'] +
										$carData['SatPrice'] + 
										$carData['SunPrice'] +
										$carData['S1Price'] +
										$carData['S2Price'] +
										$carData['S3Price'] +
										$carData['S4Price'] +
										$carData['S5Price'] +
										$carData['S6Price'] +
										$carData['S7Price'] +
										$carData['S8Price'] +
										$carData['S9Price'] +
										$carData['S10Price'] ;
						
						$contract=true;
						
						$displayPrice=getContractPrice($carData['VehicleTypeID'],$carData['RouteID'],$AgentID);
						if($_REQUEST['returnTransfer'] == 1) $displayPrice=$displayPrice*2;
						if ($displayPrice>0) $contractprice=true;
						else $contractprice=false;

						if ($displayPrice==0) {
							$displayPrice = $carData['BasePrice'];// + $carData['NightPrice'] + $addToPrice;
							$contract=false;
						}	
						$user = getUserData($AgentID);
						$displayAgentPrice=$displayPrice*($user['Provision'] / 100);

					// agentski id za agencije-sisteme sa kojima se cene ugovaraju
						//$contarced_agents=array(1711,1712,2123,911,1793,1418,1556,2342,2035,439,2171,2118,2015,1479,1344);
						//if (in_array($AgentID,$contarced_agents) && !$contractprice) $displayPrice=0;
						if ($logo<>'' && !$contractprice) $displayPrice=0;

						
						//$ourdrivers=array(556,843,876,887,901,907); //JAM driverID
						$displayDriverPrice=$carData['DriversPrice'];
						$sdp=false;
						/*if (in_array($carData['OwnerID'],$ourdrivers)) {
							$displayDriverPrice=$displayPrice*0.85;	
							$sdp=true;
						}	*/
						if ($carData['ContractFile']=='inter') {
							$displayDriverPrice=$displayPrice*0.85;	
							$ourdriver=1;
							$sdp=true;
						}				
						else $ourdriver=0;
						$cdp=false;
						$displayPriceF=nf(toCurrency($displayPrice));
						// klm ugovori						
						$klm_agents=array(1629,2829,2857);
						// Milano i BCA
						if ($carData['OwnerID']==836 && $carData['RouteID']==3699 && in_array($AgentID,$klm_agents) && $carData['ServiceID']==43066) {
							$displayDriverPrice=68;	
							$ourdriver=1;
							$cdp=true; 
						}
						echo $carData['OwnerID'];
						//	Zagreb i Brzi Hit					
						if ($carData['OwnerID']==1650 && $carData['RouteID']==1953 && in_array($AgentID,$klm_agents) && $carData['ServiceID']==539760) {
							$displayDriverPrice=33;								
							$ourdriver=1;
							$cdp=true; 
							$displayPriceF=number_format($displayPrice,3,'.','');
						}
						// split i jam
						if ($carData['OwnerID']==556 && $carData['RouteID']==1630 && in_array($AgentID,$klm_agents) && $carData['ServiceID']==1242) {
							$displayDriverPrice=22.95;								
							$ourdriver=1;
							$cdp=true; 
							$sdp=false;							
							$displayPriceF=number_format($displayPrice,3,'.','');
						}						
						// beograd i vitosprint
						if ($carData['OwnerID']==2113 && $carData['RouteID']==1525 && in_array($AgentID,$klm_agents) && $carData['ServiceID']==544908) {
							$displayDriverPrice=27.50;								
							$ourdriver=1;
							$cdp=true; 
							$displayPriceF=number_format($displayPrice,3,'.','');
						}				
						// agentski id za agencije-sisteme sa kojima se cene ugovaraju
						//$contarced_agents=array(1711,1712,2123,911,1793,1418,1556,2342,2035,439,2171,2118,2015,1479,1344);
						//if (in_array($AgentID,$contarced_agents)) $dprice=0;
						if ($logo<>'') $dprice=0;						
						else $dprice=nf(toCurrency($displayPrice));
						
						
						?>

							<h6>Prices ( <? echo s('Currency') . ' ' ;?> )</h6>
							<div class="col-md-4">
								<h6>Driver's</h6>
								<input type="text" value="<?= nf(toCurrency($displayDriverPrice)) ?>" 
								name="driversprice<?=$i+1?>" id="driversprice<?=$i+1?>" style="text-align:right; max-width:5em">
								<input type="hidden" value="<?= $ourdriver ?>" 
								name="ourdriver<?=$i+1?>" id="ourdriver<?=$i+1?>">								
							</div>	
							<div class="col-md-4">
								<h6>Agent's</h6>							
								<input type="text" value="<?= nf(toCurrency($displayAgentPrice)) ?>" 
								name="agentprice<?=$i+1?>" id="agentprice<?=$i+1?>" class='agentprice' style="text-align:right; max-width:5em">
							</div>							
							<div class="col-md-4"> 
								<h6>Our</h6>							 
								<input type="text" value="<?= $displayPriceF ?>" 
								name="price<?=$i+1?>" id="price<?=$i+1?>" class='ourprice' style="text-align:right; max-width:5em">

							</div>
					</div>
					<div class="col-md-1">
						<h6><?= VEHICLES_NO ?>:</h6>
						<select class="browser-default" name="VehiclesNo<?=$i+1?>" id="VehiclesNo<?=$i+1?>"
								style="display:inline; max-width:3em;"
								onchange="vehicleNumberAdm('<?= $displayDriverPrice ?>','<?= $displayAgentPrice ?>','<?= $displayPrice ?>','<?=$i+1?>');">
							<option value="1">1</option>
							<option value="2">2</option> 
							<option value="3">3</option>
							<option value="4">4</option>
						</select>
					</div>
					<div class="col-md-2">
						<button class="btn btn-large blue" type='button'
							id="v<?=$i+1?>" 
							data-vehiclecapacity="<?= $carData['VehicleCapacity']?>" 
							data-vehicleimage="<?= $carData['VehicleImage']?>" 
							data-price="<?= $displayPrice ?>" 
							data-agentprice="<?= $displayAgentPrice ?>" 
							data-driversprice="<?= $carData['DriversPrice'] ?>" 
							data-routeid="<?= $carData['RouteID']?>" 
							data-serviceid="<?= $carData['ServiceID']?>" 
							data-driverid="<?= $carData['OwnerID']?>" 
							data-vehicleid="<?= $carData['VehicleID']?>" 
							data-vehiclename="<?= $carData['VehicleName']?>" 
							data-drivername="<?= $carData['DriverCompany']?>" 

							onclick="return carSelectedAdm('<?=$i+1?>');" > 
							<i class="fa fa-send-o right"></i> Select
						</button>
						<?	if ($contract || $logo<>'') echo '<br><br><b> CONTRACTED PRICE </b>' ?>		
						<?	if ($logo<>'') echo "<img src='img/".$au->getImage()."'> ";	 ?>		
						<?	if ($sdp) echo '<br><br><b> DRIVERS PRICE AS 85%</b>' ?>		
						<?	if ($cdp) echo '<br><b> SPECIAL DRIVERS PRICE </b>' ?>								
					</div>
					</div>
					<!-- car price -->					


			 
				</div>
				<!-- main car panel div -->

		<?
			endif; 
			endforeach; 
		?>

</div>


	<? if (SITE_CODE == '1') { ?>
		<div class="col s12  lgray center pad1em">
				<div class="col s12 ucase">
					<hr>
						<h2 class="center"><?= DRIVERS_PROFILES ?></h2>
						<small><?= CLICK_PANEL ?></small> 
					<hr>
				</div>

			<? 

			foreach($drivers as $oid => $driverData): ?>

					<div id="ProfileButton" onclick="ShowDriverProfile('<?= $oid ?>')">
					 	<div class="col s12 white shadow clickPanel pad1em">
							<img class="roundImg carShadow" src="<?= $driverData['ProfileImage'] ?>"
							 style="height:4em;width:4em;xfloat:left;margin-right:1em"><br>
							 <?= $driverData['Company']?> 
						</div>
					</div>
					<div id="DriverProfile<?=$oid?>" class="col s12 pad1em dgray hidden" 
					style="text-align:left !important; margin-bottom:1em">
						<address>
						<?= $driverData['RealName']?><br>
						<?= $driverData['Company']?><br>
						<?= $driverData['Address']?><br>
						</address>
					</div>
			
			<? endforeach; ?>
		</div>
	<? } // end SITE_CODE ?>

<? } else { // Errors found ?>
	<div class="blue">
		<div class="col s12 ">
			<div class="col s12 pad1em">
				<br><br>
				<hr> 
					<h2 class="center"><?= $carsErrorMessage['title']; ?></h2>
					<div class="center"><small class="ucase"><?= $carsErrorMessage['text']; ?></small></div>
				<hr>
			</div
		</div>
	</div>
<? } ?>
<script>
	$(document).ready( function () {
		$("#selectCarAdminBtn").attr('type','button');
	
		$('.ourprice, .agentprice, #VehiclesNo3').on('change', function() {

			
			var idx=$(this).attr('id');
			idx=idx.replace('agentprice','');
			idx=idx.replace('price',''); 
			idL='#ourdriver'+idx;
			console.log(idL);
			var our=$(idL).val();
			if (our==1) {
				id='#driversprice'+idx;
				var dp=$(id).val();
				var agentid=$('#AgentID').val();
					id1='#price'+idx;
					var p=Number($(id1).val());				
					id2='#agentprice'+idx;
					var ap=Number($(id2).val());
				if (agentid==1711) {
					var np =  (p-ap)*0.85; 
				}
				else {	
					var np = p*0.85; 
				}	
				$(id).val(np);
			}
		})
	})
</script>

