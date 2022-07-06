<?
	$smarty->assign('page',$md->getName());	
	@session_start();
	if (!$_SESSION['UserAuthorized']) die('Bye, bye');
	?>
	<? 
	
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

    
    // Prevent refresh on thankyou.php page
    $_SESSION['REFRESHED'] = false;
    

    
    if ( (isset($lastElement) and count($lastElement) == 2) or !empty($_REQUEST) ) { // postoje neki parametri
    	// spremi sve u session
    	foreach	($_REQUEST as $key => $value) {
    		$_SESSION[$key] = $value;
    	}
    } 
    
    require_once "scriptsAdm.php";
	require_once  ROOT .'/db/v4_AuthUsers.class.php';
	
	$au = new v4_AuthUsers();
	


	if (s('MPaxFirstName')=='') $PaxFirstName=" ";
	else $PaxFirstName=s('MPaxFirstName');
	$weby_key = file_get_contents('weby_key.inc', FILE_USE_INCLUDE_PATH);

    ?>

    
   
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