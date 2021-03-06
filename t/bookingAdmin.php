<? 
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
	
	$_SESSION['language'] = $_SESSION['CMSLang'];
	
	require_once $_SERVER['DOCUMENT_ROOT'].'/db/v4_Places.class.php';	
	$pl = new v4_Places();
	
	if (isset($_SESSION['AgentID']) && $_SESSION['AgentID']>0) $AgentID=$_SESSION['AgentID'];
	else $AgentID=0;	
	if (isset($_SESSION['FromID']) && $_SESSION['FromID']>0) {
		$fromID=$_SESSION['FromID'];
		global $pl;
		$pl->getRow($fromID);
		$fromName=$pl->getPlaceNameEN();	
	}
	if (isset($_SESSION['ToID']) && $_SESSION['ToID']>0) {
		$toID=$_SESSION['ToID'];
		global $pl;
		$pl->getRow($toID);
		$toName=$pl->getPlaceNameEN();	
	}	
	if (isset($_SESSION['PaxNo']) && $_SESSION['PaxNo']>0) $PaxNo=$_SESSION['PaxNo'];
	else $PaxNo=0;	
	
	if (s('MPaxTel')=='') $PaxTel=" ";
	else $PaxTel=s('MPaxTel');	
	
	if (isset($_SESSION['transferDate']))  $transferDate=$_SESSION['transferDate'];

  
	// END OF LANGUAGES	

    
    // Prevent refresh on thankyou.php page
    $_SESSION['REFRESHED'] = false;
    

    
    if ( (isset($lastElement) and count($lastElement) == 2) or !empty($_REQUEST) ) { // postoje neki parametri
    	// spremi sve u session
    	foreach	($_REQUEST as $key => $value) {
    		$_SESSION[$key] = $value;
    	}
    } 
    
    require_once "scriptsAdm.php";
	require_once $_SERVER['DOCUMENT_ROOT'].'/db/v4_AuthUsers.class.php';
	
	$au = new v4_AuthUsers();
	


	if (s('MPaxFirstName')=='') $PaxFirstName=" ";
	else $PaxFirstName=s('MPaxFirstName');
	$weby_key = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/cms/t/weby_key.inc', FILE_USE_INCLUDE_PATH);

    ?>

    
    <div style="background: transparent url('https://<?= $_SERVER[HTTP_HOST] ?>/i/header/112.jpg') center fixed; background-size: cover;
    margin-top:-20px !important">
        <br>
       <div class="container pad1em" style="background-color: rgba(70,79,96,0.75); border:1px solid #000;border-radius:6px;">
            <div class="row">

				<div class="col s12 xucase center white-text">



                    <h3>ADMINISTRATION <?= BOOKING ?>
					</h3>
					<p class="divider clearfix"></p>
					
                </div>
                <!-- title column -->
                
                <div class="col s12 xgrey xlighten-3">
                    <br>
                    <form id="bookingForm" name="bookingForm" action="index.php?p=final" method="POST" enctype="multipart/form-data"
                    onsubmit="return validateBookingForm();">
                        <input type="hidden" id="pleaseSelect" value="<?= PLEASE_SELECT?>"/>
                        <input type="hidden" id="loading" value="<?= LOADING ?>"/>
                        
                        <div class="col l6 s12">
                            <label for="AuthUserIDe"><i class="fa fa-globe"></i> Book as <strong>Agent</strong></label><br>
                            <div>						
								<? $agents = array(); $agents = getAgents(); ?> 
								<select name="AgentID" id="AgentID" class="xchosen-select browser-default" value='<?= s('AgentID') ?>'>
									<option value="0"> --- </option>
									<? 
										foreach($agents as $id => $name) {
											if ($AgentID==$id) $selected='selected';
											else $selected='';
											echo '<option value="'.$id.'"  '. $selected . '>' . $name .'</option>';
										}
										?>
								</select>
							</div>
                        </div>					
                        <div class="col s12 l2" >
                            <label for="ReferenceNo"><i class="fa fa-book"></i> Agent Reference Number</label><br>
                            <input type="text"  id="ReferenceNo" class="browser-default" name="ReferenceNo"  value="">
                        </div>						
                        <div class="col s12 l2" id='webyblock' style="display:none">
                            <label for="wrn"><i class="fa fa-book"></i> Weby Reference Number</label>
							<input type="text" id="weby_key" name="weby_key" value="<?= $weby_key ?>" disabled><br>						
                            <select id="wref" class="browser-default" name="wref" value=''>
                            </select>
                        </div>						

                        <div class="col s12 l2" id='sunblock'  style="display:none">
                            <label for="srn"><i class="fa fa-book"></i> Sun Reference Number</label><br>						
                            <input type="file"  id="srn" class="browser-default" name="SunReferenceNo"  value="">
                        </div>	
                        
                        <div class="col s12 l6">
                        	<br>
                            <label for="fromSelectorValue"><i class="fa fa-map-marker"></i> <?= FROM ?></label><br>
							<input type="hidden" id="FromID" name="FromID" value="<?= $fromID ?>"><i class="pe-7s-car pe-lg  pe-va white-text"></i>
							<?= $STARTING_FROM ?>
							<input type="text" id="FromName" name="FromName" value="<?= $fromName ?>" class="input-lg" style="width:100%"  placeholder="<?= $SEARCH_PLACEHOLDER ?>"
								autocomplete="off">
							<span id="fromLoading" class="small"><?= $TYPE_SEARCH ?></span>
							<div id="selectFrom_options" class="list-group white" style="max-height:15em;overflow:auto"></div>
                        </div>
                        <!-- from -->

                        <div class="col l6 s12">
                        	<br>
                            <label for="toSelectorValue" ><i class="fa fa-map-marker"></i> <?= TO ?></label> <span style='color:white' id='toname2'></span><br>
							<input type="hidden" id="ToID" name="ToID" value="<?= $toID ?>"><i class="pe-7s-map-marker pe-lg  pe-va white-text"></i>
							<?= $GOING_TO ?>
							<input type="text" id="ToName" name="ToName" value="<?= $toName ?>" class="input-lg" style="width:100%"  placeholder="<?= $SEARCH_PLACEHOLDER ?>"
								autocomplete="off">
							<span id="toLoading" class="small"><?= $TYPE_SEARCH ?></span>
							<div id="selectTo_options" class="list-group white"style="max-height:15em;overflow:auto"></div>
                        </div>
                        <!-- to -->
                        <div class="col l6 s12">
                        	<br>
                            <label for="paxSelector">
                            <i class="fa fa-user"></i> <?= PASSENGERS_NO ?>
                            </label>
                            <select id="paxSelector" class="browser-default" name="PaxNo" value='<?= $PaxNo ?>'>
                                <option value="0"> --- </option>
                                <?
                                    for($i=1; $i<55; $i++) {
                                        echo '<option value="'.$i.'" ';
                                        if ($PaxNo==$i) echo 'selected="selected"';
                                        echo '>'.$i.'</option>';
                                    }
                                    
                                    ?>
                            </select>
                        </div>
                        <!-- passengers no. -->
                        
                        <div class="col s12 l3">
                        	<br>
                            <label for="transferDate"><i class="fa fa-calendar-o"></i> <?= PICKUP_DATE ?></label><br>
                            <input type="text"  id="transferDate" class="browser-default" 
                                name="transferDate" readonly 
                                value="<?= $transferDate ?>" data-field="date">
                        </div>
                        <!-- pickup date -->
                        
                        <div class="col s12 l3">
                        	<br>
                            <label for="transferTime"><i class="fa fa-clock-o"></i> <?= PICKUP_TIME ?></label><br>
                            <input type="text" id="transferTime" class="browser-default timepick" 
                            name="transferTime"
                                value="<?= s('transferTime')?>" data-field="time">
                        </div>
                        <!-- pickup time -->
                        
                        <div class="col l6 s12">
                        	<br>
                            <div class="switch">
                                <label for="returnTransferCheck">
                                    <i class="fa fa-undo"></i> <?= RETURN_TRANSFER ?>
                                </label>
                                <br><br>
                                <label class="center">
                                    <?= NO ?>
                                    <input type="checkbox" name="returnTransferCheck" id="returnTransferCheck" 
                                        <? if (s('returnTransfer')=='1') echo 'checked';?>>
                                    <span class="lever"></span>
                                    <?= YES ?>
                                </label>
                                <br><br>
                            </div>
                        </div>
                        <!-- return transfer switch -->
                        
                        <div id="showReturn" style="display:none;margin:-0.75rem !important" class="col s12">
                            <div class="col s12 l3">
                            	<br>
                                <label for="returnDate"><i class="fa fa-calendar-o"></i> <?= RETURN_DATE ?></label><br>
                                <input type="text"  id="returnDate" class="browser-default" 
                                    name="returnDate" readonly 
                                    value="<?= s('returnDate')?>" data-field="date"> 
                            </div>
                            <div class="col s12 l3">
                            	<br>
                                <label for="returnTime"><i class="fa fa-clock-o"></i> <?= PICKUP_TIME ?></label><br>
                                <input type="text" id="returnTime" name="returnTime" 
                                    class="browser-default timepick" data-field="time"
                                    value="<?= s('returnTime')?>">
                                    <br><br>
                            </div>
                        </div>
                        <!-- show return date/time -->
                        <br>
				        <div class="col s12 pad1em white-text" style="padding: 1rem !important; background: rgba(0,0,0,.5)">
				            <div class="col s12 l9">
				                <p><i class="fa fa-info-circle fa-2x red-text"></i> 
				                <?= AVAILABILITY_DEPENDS ?> </p>
				            </div>
				            <div class="col s6 l3 pull">
				                <button id="selectCarAdminBtn" type="submit" class="btn blue btn-large"
				                    onclick="return false;">
                                    <i class="fa fa-chevron-down"></i> <?= SELECT_CAR ?>
				                </button>
				                <button id='empty' type="button" class="btn btn-large">Empty fields</button>
				            </div>							
				        </div>
                        <!-- select car button --> 
                        

                        <div class="col s12">
                            <div class="tab" id="tab_1">
                                <div id="selectCar">
									<div class="col s12 center-align xwhite-text">
										<br>
										 <h4><?= PRICES_STARTING_FROM?>:</h4> 
									</div>
									<? 
									require_once $_SERVER['DOCUMENT_ROOT'] . '/m/getRoutePrices.php';
									$car =   getRoutePrices( s('FromID'), s('ToID') );

									$cells =  count($car);

										switch ($cells) {
											case 1: $box = 'l4'; $offset = 'offset-l2'; break;
											case 2: $box = 'l3'; $offset = 'offset-l3'; break;
											case 3: $box = 'l4'; $offset = ''; break;
											case 4: $box = 'l3'; $offset = ''; break;
											case 5: $box = 'l2'; $offset = 'offset-l1'; break;
											case 6: $box = 'l2'; $offset = ''; break;
											case 7: $box = 'l3'; $offset = ''; break;
											case 8: $box = 'l3'; $offset = ''; break;
											case 9: $box = 'l3'; $offset = ''; break;
											case 10: $box = 'l2'; $offset = ''; break;
											case 11: $box = 'l3'; $offset = ''; break;
											case 12: $box = 'l2'; $offset = ''; break;
										}
									?>
									<div class="col s12 <?= $offset ?>">

										<?
										foreach($car as $VehicleCapacity => $price) {

											$VehicleImageRoot = "https://" . $_SERVER['HTTP_HOST'];

						                    if ($VehicleCapacity <= 3) $vehicleImageFile = '/i/cars/sedan.png';
						                    else if ($VehicleCapacity <= 4) $vehicleImageFile = '/i/cars/sedan.png';
						                    else if ($VehicleCapacity <= 8) $vehicleImageFile = '/i/cars/minivan.png';
						                    else if ($VehicleCapacity <= 15) $vehicleImageFile = '/i/cars/minibusl.png';
						                    else if ($VehicleCapacity > 15) $vehicleImageFile = '/i/cars/bus.png';
									
											$VehicleImage = $VehicleImageRoot.$vehicleImageFile;
											?>
											<div class="col s12  <?= $box ?>  card l">
												<br>
												<i class="fa fa-user"></i> <?= $VehicleCapacity?><br>
												<img src="<?= $VehicleImage ?>" class="responsive-img" alt="taxi">

												<div class="card-action">
												 <i class="fa fa-tags red-text"></i> <?= $price ?> <?= s('Currency') ?>
												 </div>
											</div>
											
											<?
										} ?>
									</div>
									<div class="col s12 ucase s center xwhite-text">
										<?= SERVICES_DESC1 ?> - 
										<?= SERVICES_DESC2 ?> -  
										<?= SERVICES_DESC3 ?> - 
										<?= SERVICES_DESC4 ?> - 
										<?= SERVICES_DESC5 ?>
									</div>
                                </div>
								<div id="final" style='display: none;'>Proba</div>

                            </div>
                            <!-- tab_1 -->
                        </div> 
						
                        <input type="hidden" id="PaxNo2" name="PaxNo2" value="0"> 
                        <input type="hidden" id="AgentName" name="AgentName" value="0"> 
						<input type="hidden" id="DriverID" name="DriverID" value="0">
						<input type="hidden" id="DriverName" name="DriverName" value="0">
						<input type="hidden" id="VehicleID" name="VehicleID" value="0">
						<input type="hidden" id="VehiclesNo" name="VehiclesNo" value="1">
						<input type="hidden" id="VehicleName" name="VehicleName" value="">
						<input type="hidden" id="VehicleCapacity" name="VehicleCapacity" value="0">
						<input type="hidden" id="VehicleImage" name="VehicleImage" value="">
						<input type="hidden" id="ServiceID" name="ServiceID" value="0">
						<input type="hidden" id="RouteID" name="RouteID" value="0">
						<input type="hidden" id="Price" name="Price" value="0">
						<input type="hidden" id="AgentPrice" name="AgentPrice" value="0">						
						<input type="hidden" id="DriversPrice" name="DriversPrice" value="0">
						<input type="hidden" id="returnTransfer" name="returnTransfer" value="0">
						<input type="hidden" id="ToName2" name="ToName2" value="0">
						<input type="hidden" id="VehicleName2" name="VehicleName2" value="">
						<input type="hidden" id="api" name="api" value="">    
						
						<input type="hidden" id="PaxFirstName" name="PaxFirstName" value="<?= $PaxFirstName ?>">                   
						<input type="hidden" id="PaxSurName" name="PaxSurName" value="<?= s('MPaxLastName')?>">      
						<input type="hidden" id="PaxTel" name="PaxTel" value="<?= $PaxTel ?>">  
						
						<input type="hidden" id="FlightNo" name="FlightNo" value="<?=s('FlightNo') ?>">  						
						<input type="hidden" id="FlightCo" name="FlightCo" value="">   
						<input type="hidden" id="FlightTime" name="FlightTime" value="<?= s('FlightTime') ?>">  
						<input type="hidden" id="DFlightNo" name="DFlightNo" value="<?=s('DFlightNo') ?>">  						
						<input type="hidden" id="DFlightCo" name="DFlightCo" value="">   
						<input type="hidden" id="DFlightTime" name="DFlightTime" value="<?= s('DFlightTime') ?>">  						
						
						<input type="hidden" id="RFlightNo" name="RFlightNo" value="<?=s('RFlightNo') ?>">  						
						<input type="hidden" id="RFlightCo" name="RFlightCo" value="">   
						<input type="hidden" id="RFlightTime" name="RFlightTime" value="<?= s('RFlightTime') ?>">  
						<input type="hidden" id="RDFlightNo" name="RDFlightNo" value="<?=s('RDFlightNo') ?>">  						
						<input type="hidden" id="RDFlightCo" name="RDFlightCo" value="">   
						<input type="hidden" id="RDFlightTime" name="RDFlightTime" value="<?= s('RDFlightTime') ?>">  						
						
						<input id='SPAddressHotel' name='SPAddressHotel' type='hidden'/>
						<input id='SDAddressHotel' name='SDAddressHotel' type='hidden'/>
						<input id='RPAddressHotel' name='RPAddressHotel' type='hidden'/>  
						<input id='RDAddressHotel' name='RDAddressHotel' type='hidden'/>				
						<input id='PickupAddress' name='PickupAddress' type='hidden' value="<?= s('PickupAddress') ?>"/>
						<input id='DropAddress' name='DropAddress' type='hidden' value="<?= s('DropAddress') ?>"/>
						<input id='RPickupAddress' name='RPickupAddress' type='hidden' value="<?= s('RPickupAddress') ?>"/> 
						<input id='RDropAddress' name='RDropAddress' type='hidden' value="<?= s('RDropAddress') ?>"/>
						
                    </form>
                    <!-- booking form -->
                    <br>&nbsp;     
 
                </div>
                <!-- booking form col grey -->
                <br><br>
            </div>
            <!-- main row -->
        </div>
        <!-- main container -->
        <br>&nbsp;
    </div>
    <!-- background div -->

<script src="<? $_SERVER['DOCUMENT_ROOT'] ?>/cms/t/ztest1.js"></script>

<script>

	function selectJSON() {
		$('#wref').empty();
		var weby_key=$('#weby_key').val();
		var param = 'weby_key='+weby_key;
		console.log('/cms/t/selectJSON.php?'+param);
		$.ajax({
			type: 'GET',
			url: '/cms/t/selectJSON.php?'+param,
			success: function(data) {
				if (data!=='No') 
					$('#wref').html(data);
				else {
					alert ('No reservation or wrong api key');
					$('#weby_key').prop('disabled', false);
				}	
			}	
		})	
		$('#webyblock').show(500);		
	}	
	$('#empty').on('click', function() {
		$('input').each(function() {
			$(this).attr('value', '');  
		})
		$('#AgentID').val(0);
		$('#paxSelector').val(0);
		$('#PaxFirstName').val(' ');
		$('#PaxTel').val(' ');	
	})	
	$(document).ready( function () {
		$('#webyblock').hide(500);	
		$('#sunblock').hide(500);			
		var aid=$('#AgentID').val();
		if (aid==1711) selectJSON();
		if (aid==1712) selectJSON();
		if (aid==2123) $('#sunblock').show(500);		
	})	
	$('#AgentID').on('change', function() {
		$('#webyblock').hide(500);	
		$('#sunblock').hide(500);			
		var aid=$('#AgentID').val();
		if (aid==1711) selectJSON();
		if (aid==1712) selectJSON();
		if (aid==2123) $('#sunblock').show(500);
	})	
	$('#sun').on('click', function() {
		$('#apies').hide(500);
		$('#sunblock').show(500);	
		$('#api').val('SUN');
	})	
	$('#weby_key').on('change', function() {
		selectJSON();
	})
	$('#wref').on('change', function() {
		var code = $('#wref :selected').val();
		var weby_key=$('#weby_key').val();
		$('#ReferenceNo').val(code);
		if (code != '') {
			var link  = '/cms/t/getJSON.php';
			var param = 'code='+code+'&form='+'booking'+'&weby_key='+weby_key;
			$.ajax({
				type: 'POST',
				url: link,
				data: param,
				async: false,
					success: function(data) {
						if (data=='false') alert ('Wrong reservation reference');
						else {
							var order = JSON.parse(data);
							var keys = Object.keys(order);
							keys.forEach(function(entry) {
									var id_ch = '#'+entry;
									$(id_ch).val(order[entry]);
								})	
							$('#paxSelector option').each(function() {
								if ($(this).val() == $('#PaxNo2').val()) $(this).prop('selected', true);
							})	
							
							// cekiranje povratnog transfera
							var rt = $('#returnDate').val();		
							if (rt != '') $('#returnTransferCheck').trigger('click');
							//opis za povratni transfer
							var toname2 = $('#ToName2').val();
							$('#toname2').html(toname2);
							
							// dodatni opis za vozilo
							var vehicle = $('#VehicleName2').val();
							$('#vehiclename').html(vehicle);
							
						// dodavanje hotela u adrese
						$('#PickupAddress').val(($('#SPAddressHotel').val())+' '+($('#PickupAddress').val()));
						$('#DropAddress').val(($('#SDAddressHotel').val())+' '+($('#DropAddress').val()));
						$('#RPickupAddress').val(($('#RPAddressHotel').val())+' '+($('#RPickupAddress').val()));
						$('#RDropAddress').val(($('#RDAddressHotel').val())+' '+($('#RDropAddress').val()));
						
							$('#api').val('WEBY');
						}
					}
			});	
		}		 
	}) 
	
	
	$('#srn').on('change', function() {
		var data = new FormData();
		data.append('ufile', $('#srn').prop('files')[0]);
		$.ajax({
			type: 'POST',
			url: '/cms/p/modules/getXML.php',
			data: data,
			async: false,
			processData: false, // Using FormData, no need to process data.
			contentType: false,
				success: function(data) {
					var order = JSON.parse(data);
					var keys = Object.keys(order);
					keys.forEach(function(entry) {
							var id_ch = '#'+entry;
							$(id_ch).val(order[entry]);
						})	
					$('#paxSelector option').each(function() {
						if ($(this).val() == $('#PaxNo2').val()) $(this).prop('selected', true);
					})			
					// cekiranje povratnog transfera
					var rt = $('#returnDate').val();		
					if (rt != '') $('#returnTransferCheck').trigger('click');				
					var toname2 = $('#ToName2').val();
					$('#toname2').html(toname2);				
					// dodatni opis za vozilo
					var vehicle = $('#VehicleName2').val();
					$('#vehiclename').html(vehicle);	
					$('#api').val('SUN');
					$('#PickupAddress').val(($('#SPAddressHotel').val())+' '+($('#PickupAddress').val()));
					$('#DropAddress').val(($('#SDAddressHotel').val())+' '+($('#DropAddress').val()));
					$('#RPickupAddress').val(($('#RPAddressHotel').val())+' '+($('#RPickupAddress').val()));
					$('#RDropAddress').val(($('#RDAddressHotel').val())+' '+($('#RDropAddress').val()));
					
					$('#FlightNo').val(($('#FlightCo').val())+' '+($('#FlightNo').val()));
					if ($('#FlightNo').val()==' ') $('#FlightNo').val(($('#DFlightCo').val())+' '+($('#DFlightNo').val()));					
					$('#RFlightNo').val(($('#RFlightCo').val())+' '+($('#RFlightNo').val()));
					if ($('#RFlightNo').val()==' ') $('#RFlightNo').val(($('#RDFlightCo').val())+' '+($('#RDFlightNo').val()));
					if ($('#FlightTime').val()=='') $('#FlightTime').val($('#DFlightTime').val());					
					if ($('#RFlightTime').val()=='') $('#RFlightTime').val($('#RDFlightTime').val());										
				}
		});
	});
</script>

<?
require_once($_SERVER['DOCUMENT_ROOT']."/cms/t/bookingAdmin.js.php");
require_once($_SERVER['DOCUMENT_ROOT']."/cms/t/booking.js.php");

/*
jScript in: js/pages/booking_new.js.php
*/
function fiksniDio() {
	$term_name = GetPlaceName(s('FromID')); 
	$dest_name = GetPlaceName(s('ToID'));

	if ($term_name == '') $term_name = YOUR_TERM;
	if ($dest_name == '') $dest_name = YOUR_DEST;

	if ($_SESSION['language'] == 'en') {
		$fiksni_dio = 	BOOKING_ABOUT_1 . $term_name . BOOKING_ABOUT_2 . $dest_name .
						BOOKING_ABOUT_3 . $term_name . BOOKING_ABOUT_4 . $dest_name .
						BOOKING_ABOUT_5 . $term_name . BOOKING_ABOUT_6 . $dest_name .
						BOOKING_ABOUT_7 . $dest_name . BOOKING_ABOUT_8 . $term_name .
						BOOKING_ABOUT_9 . $dest_name . BOOKING_ABOUT_10;
	}

	return $fiksni_dio;
}

function getAgents()
{
	global $au;
	$retArr = array();
	
	$where = " WHERE AuthLevelID = '2' AND Active=1";
	$k = $au->getKeysBy("AuthUserCompany", "asc", $where);
	
	if(count($k) > 0 ) {
		foreach($k as $nn => $key) {
			$au->getRow($key);
		 	# Stavi TaxiSite-ove u array za kasnije
			$retArr[$au->AuthUserID] = $au->AuthUserCompany;
			
		}
	}
	$where = " WHERE AuthLevelID = '12' AND Active=1";
	$k = $au->getKeysBy("AuthUserCompany", "asc", $where);
	
	if(count($k) > 0 ) {
		foreach($k as $nn => $key) {
			$au->getRow($key);
		 	# Stavi TaxiSite-ove u array za kasnije
			$retArr[$au->AuthUserID] = $au->AuthUserCompany;
			
		}
	}
	return $retArr;
}