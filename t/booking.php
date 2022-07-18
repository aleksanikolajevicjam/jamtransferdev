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

	// END OF LANGUAGES	
    //require_once 'para_back.html'; 
    
    // Prevent refresh on thankyou.php page
    $_SESSION['REFRESHED'] = false;
    
    //echo '<pre>'; print_r($_REQUEST); echo '</pre>';
    
    //if (isset($_REQUEST)) $_SESSION = array();
    
    if ( (isset($lastElement) and count($lastElement) == 2) or !empty($_REQUEST) ) { // postoje neki parametri
    	// spremi sve u session
    	foreach	($_REQUEST as $key => $value) {
    		$_SESSION[$key] = $value;
    	}
    } 
    
    require_once "scripts.php";
    ?>
    
    <div style="background: transparent url('https://<?= $_SERVER[HTTP_HOST] ?>/i/header/112.jpg') center fixed; background-size: cover;
    margin-top:-20px !important">
        <br>
       <div class="container pad1em" style="background-color: rgba(70,79,96,0.75); border:1px solid #000;border-radius:6px;">
            <div class="row">

				<div class="col s12 xucase center white-text">



                    <h3><?= BOOKING ?></h3>
					<p class="divider clearfix"></p>
					
                    <span class="left">* <?= PLEASE_FILL_IN_ALL_DATA ?><br><?= AGENTS_WARNING ?></span>
                </div>
                <!-- title column -->
                
                <div class="col s12 xgrey xlighten-3">
                    <br>
                    <form id="bookingForm" name="bookingForm" action="index.php?p=final" method="POST" 
                    onsubmit="return validateBookingForm();">
                        <input type="hidden" id="pleaseSelect" value="<?= PLEASE_SELECT?>"/>
                        <input type="hidden" id="loading" value="<?= LOADING ?>"/>
                        
                        <div class="col l6 s12">
                        	<br>
                            <label for="countrySelectorValue"><i class="fa fa-globe"></i> <?= COUNTRY ?></label><br>
                            <div>
                                <input type="hidden" id="countrySelectorValue" value="<?= s('CountryID');?>">
                                <select id="countrySelector" name="CountryID" class="xchosen-select browser-default" 
                                    onchange="selectFrom();">
                                </select>
                            </div>
                        </div>
                        <!-- country --> 
                        
                        <div class="col s12 l6">
                        	<br>
                            <label for="fromSelectorValue"><i class="fa fa-map-marker"></i> <?= FROM ?></label><br>
                            <div>
                                <input type="hidden" id="fromSelectorValue" value="<?= s('FromID');?>">
                                <select id="fromSelector" name="FromID" class="chosen-select browser-default" 
                                    onchange="selectTo();">
                                    <option> --- </option>
                                </select>
                            </div>
                        </div>
                        <!-- from -->

                        <div class="col l6 s12">
                        	<br>
                            <label for="toSelectorValue"><i class="fa fa-map-marker"></i> <?= TO ?></label><br>
                            <div>
                                <input type="hidden" id="toSelectorValue" value="<?= s('ToID');?>">
                                <select id="toSelector" name="ToID" class="chosen-select browser-default">
                                    <option> --- </option>
                                </select>
                            </div>
                        </div>
                        <!-- to -->
                        <div class="col l6 s12">
                        	<br>
                            <label for="paxSelector">
                            <i class="fa fa-user"></i> <?= PASSENGERS_NO ?>
                            </label>
                            <select id="paxSelector" class="browser-default" name="PaxNo">
                                <option value="0"> --- </option>
                                <?
                                    for($i=1; $i<55; $i++) {
                                        echo '<option value="'.$i.'" ';
                                        if (s('PaxNo')==$i) echo 'selected="selected"';
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
                                value="<?= s('transferDate')?>" data-field="date">
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
				            <div class="col s12 l3 pull">
				                <button id="selectCarBtn" type="submit" class="btn blue btn-large"
				                    onclick="return false;">
                                    <i class="fa fa-chevron-down"></i> <?= SELECT_CAR ?>
				                </button>
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

                            </div>
                            <!-- tab_1 -->
                        </div> 


						
                          
                        <div class="col s12">
                            <ul class="collapsible" data-collapsible="accordion">
                                <li>
                                    <div class="collapsible-header"><i class="fa fa-info"></i><?= FAQ ?></div>
                                    <div class="collapsible-body pad1em white">
                                        <br>
                                        <strong><?= FAQQ1 ?></strong>
                                        <p><?= FAQA1 ?></p>
                                        <strong><?= FAQQ2 ?></strong>
                                        <p><?= FAQA2 ?></p>
                                        <strong><?= FAQQ3 ?></strong>
                                        <p><?= FAQA3 ?></p>
                                        <strong><?= FAQQ4 ?></strong>
                                        <p><?= FAQA4 ?></p>
                                        <strong><?= FAQQ5 ?></strong>
                                        <p><?= FAQA5 ?></p>
                                    </div>
                                </li>
                                <li>
                                    <div class="collapsible-header"><i class="fa fa-folder-open-o"></i><?= ABOUT ?></div>
                                    <div class="collapsible-body white">
                                        <p>
                                            <?= fiksniDio() ?>
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        
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
						<input type="hidden" id="DriversPrice" name="DriversPrice" value="0">
						<input type="hidden" id="returnTransfer" name="returnTransfer" value="0">
                       
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
<?
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

