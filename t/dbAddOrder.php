<?

function insertOrder($type='temp') {	

	global $OrderKey;
	
	if($type == 'temp') {

		require_once $_SERVER['DOCUMENT_ROOT'] . '/db/v4_OrdersMasterTemp.class.php';
		require_once $_SERVER['DOCUMENT_ROOT'] . '/db/v4_OrderDetailsTemp.class.php';
		require_once $_SERVER['DOCUMENT_ROOT'] . '/db/v4_OrderExtrasTemp.class.php';
		
		$om = new v4_OrdersMasterTemp();
		$od = new v4_OrderDetailsTemp();
		$ox = new v4_OrderExtrasTemp();
		
		$OrderKey = $_SESSION['order_number']; // postavlja se u webtehPayment.php
	}
	else if($type=='final') {

		require_once $_SERVER['DOCUMENT_ROOT'] . '/db/v4_OrdersMaster.class.php';
		require_once $_SERVER['DOCUMENT_ROOT'] . '/db/v4_OrderDetails.class.php';
		require_once $_SERVER['DOCUMENT_ROOT'] . '/db/v4_OrderExtras.class.php';
		
		$om = new v4_OrdersMaster();
		$od = new v4_OrderDetails();
		$ox = new v4_OrderExtras();
		
		$OrderKey = create_order_key(); // pravi OrderKey
	}
	
	// From and To names
	$fromName 	= getPlaceName( s('FromID') );
	$toName 	= getPlaceName( s('ToID') );
	
	
	// Driver Data
	$driver = getUserData($_SESSION['DriverID']);
	$driverName = $driver['AuthUserCompany'];
	$driverTel 	= $driver['AuthUserTel'];
	$driverEmail= $driver['AuthUserMail'];
	
	$vehicleTypeID = getVehicleTypeID(s('VehicleID'));
	
	
	// Payment Statuses
	
	if($_SESSION['PL'] == 0 and $_SESSION['PN'] > 0) $paymentStatus = '99'; // PayLater = 0, sve placeno
	else $paymentStatus = '0'; // PayLater nije nula, znaci ima jos za platiti
	
	$_SESSION['ExchFaktor']=1; // za verziju sa cenama u evrima za domaće agente
	// CURRENCIES
	if($_SESSION['Currency'] != 'EUR') {
		
	}
	
	$UserID = '53';
	
	if(!isset($_SESSION['Customer']['ID']) or empty($_SESSION['Customer']['ID'])) {
		
		$CustomerID = '';
		$UserLevelID = '3';
		
	} else {
		$CustomerID = $_SESSION['Customer']['ID'];
		$UserLevelID = '3';
	}
	
	if(isset($_SESSION['AuthUserID']) and isset($_SESSION['AuthLevelID']) and $_SESSION['UserAuthorized']) {
		$UserID = $_SESSION['AuthUserID'];
		$UserLevelID = $_SESSION['AuthLevelID'];
	}
	
	if($UserLevelID == '2' or $UserLevelID == '4') { // Agent ili Affiliate
		$user = getUserData($UserID);
		// provizija ide samo na cijenu transfera, ne na TotalPrice
		//$AgentCommision = nf($_SESSION['TT'] * $user['Provision'] / 100); // ovo je bilo, ali izgleda da nije dobro!
		
        // oduzimam Extras jer je izgleda provizija isla na sve...
		$samoTransfer = $_SESSION['TT'] - $_SESSION['ET'];
		$AgentCommision = nf($samoTransfer * $user['Provision'] / 100);

		$Invoice = nf($_SESSION['TotalPrice'] -  $AgentCommision);
	}
	
 
    $payNow = $_SESSION['PN'];
    $payLater = $_SESSION['PL'];
    // Pon 27 Stu 2017 20:42:13 NOVO - sve online za agente
    if($_SESSION['PaymentOption'] == '1') { // Sve online - provizija
        
        $payNow   = $Invoice; // prebaci da je placeno online
        $payLater = '0';      // nista ne ostaje za cash
        $InvoiceMaster  = '0.00';   // nema nista ni za invoice
        $paymentStatus = '99';
    
    }
    if($_SESSION['PaymentOption'] == '2') { // Sve cash   
        $payLater   = $_SESSION['TotalPrice'] ; // prebaci da je placeno cash
        $payNow = '0';      // nista ne ostaje za online
        $InvoiceMaster  = '0.00';   // nema nista ni za invoice    
    }
	
	// OrdersMaster
		$om->setMOrderKey( $OrderKey );
		$om->setSiteID('2');
		$om->setMOrderStatus('1');
		$om->setMOrderType( $_SESSION["MOrderType"]);
		$om->setMOrderDate(date("Y-m-d"));
		$om->setMOrderTime(date("H:i:s"));
		$om->setMUserID($UserID);
		$om->setMUserLevelID( $UserLevelID ); 
		$om->setMCustomerID( $CustomerID );
		$om->setMTransferPrice( $_SESSION['TT']/$_SESSION['ExchFaktor']);
		$om->setMExtrasPrice( $_SESSION['ET']/$_SESSION['ExchFaktor']);
		$om->setMOrderPriceEUR( $_SESSION["TotalPrice"]/$_SESSION['ExchFaktor']);
		$om->setMOrderCurrency( $_SESSION["Currency"]);
		$om->setMOrderCurrencyPrice( $_SESSION["TotalPrice"]/$_SESSION['ExchFaktor']);
		$om->setMEurToCurrencyRate( ExchangeRatio());
		$om->setMPaymentMethod( $_SESSION["PaymentOption"]);
		$om->setMPaymentStatus( $paymentStatus);
		$om->setMPayNow( $payNow/$_SESSION['ExchFaktor']);
		$om->setMPayLater( $payLater/$_SESSION['ExchFaktor']);
		$om->setMInvoiceAmount( $InvoiceMaster/$_SESSION['ExchFaktor']);
		$om->setMAgentCommision( $AgentCommision/$_SESSION['ExchFaktor']);
		$om->setMPaxFirstName( $_SESSION["MPaxFirstName"]);
		$om->setMPaxLastName( $_SESSION["MPaxLastName"]);
		$om->setMPaxTel( $_SESSION["MPaxTel"]);
		$om->setMPaxEmail( $_SESSION["MPaxEmail"]);
		$om->setMCardType( $_SESSION["MCardType"]);
		$om->setMCardFirstName( $_SESSION["ch_name"]);
		$om->setMCardLastName( $_SESSION["ch_last_name"]);
		$om->setMCardEmail( $_SESSION["ch_email"]);
		$om->setMCardTel( $_SESSION["ch_phone"]);
		$om->setMCardAddress( $_SESSION["ch_address"]);
		$om->setMCardCity( $_SESSION["ch_city"]);
		$om->setMCardZip( $_SESSION["ch_zip"]);
		$om->setMCardCountry( GetCountryName( $_SESSION["ch_country"]) );
		if (isset($_REQUEST['order_number'])) $om->setMCardNumber( $_REQUEST['order_number']);
		else $om->setMCardNumber( $_SESSION["MCardNumber"]);
		$om->setMCardCVD( $_SESSION["MCardCVD"]);
		$om->setMCardExpDate( $_SESSION["MCardExpDate"]);
		$om->setMConfirmFile( $_SESSION["MConfirmFile"]);
		$om->setMCancelFile( $_SESSION["MCancelFile"]);
		$om->setMChangeFile( $_SESSION["MChangeFile"]);
		$om->setMSubscribe( $_SESSION["MSubscribe"]);
		$om->setMAcceptTerms( '1');
		$om->setMSendEmail( $_SESSION["MSendEmail"]);
		$om->setMEmailSentDate( $_SESSION["MEmailSentDate"]);
		$om->setMCustomerIP( $_SESSION["MCustomerIP"]);
		$om->setMOrderLang( $_SESSION['language']);

		
		$omOrderID = $om->saveAsNew();
		
		// Update OrderKey za printReservation.php
		$OrderKey .= '-'.$omOrderID;

		//**********
		// DETAILS
		//**********
			
		// Kalkulacija cijena za upis
		$InvoiceDetails = $Invoice;
		$AgentCommisionDetails = $AgentCommision;
		$numberOfVehicles = $_SESSION['VehiclesNo'];
		$payNow 		= number_format($_SESSION['PN'], 2, '.', '');
		$payLater 		= number_format($_SESSION['PL'], 2, '.', '');
		$extras 		= number_format($_SESSION['ET'], 2, '.', '');
		$transferPrice	= number_format($_SESSION['Price'], 2, '.', '');
		$driversPrice	= number_format($_SESSION['DriversPrice'] , 2, '.', '');
		$profit         = number_format($transferPrice - $driversPrice - $extras, 2, '.', '');

		if (s('returnTransfer')) {
			$InvoiceDetails = nf($Invoice / 2);
			$AgentCommisionDetails = nf($AgentCommision / 2);
			$payNow 		= number_format($_SESSION['PN'] / 2, 2, '.', '');
			$payLater 		= number_format($_SESSION['PL'] / 2, 2, '.', '');
			$extras 		= number_format($_SESSION['ET'] / 2, 2, '.', '');
			$transferPrice	= number_format($_SESSION['Price'] / 2, 2, '.', '');
			$driversPrice	= number_format($_SESSION['DriversPrice'] / 2, 2, '.', '');
			$profit         = number_format($transferPrice - $driversPrice - $extras, 2, '.', '');
		}
		
 
        // Pon 27 Stu 2017 20:42:03 NOVO - sve online za agente
        if($_SESSION['PaymentOption'] == '1') { // Sve online - provizija
            
            $payNow = $InvoiceDetails;  // prebaci da je placeno online
            $payLater = '0';            // nista ne ostaje za cash
            $InvoiceDetails = '0.00';   // nema nista ni za invoice
            $paymentStatus = '99';
        }
		if($_SESSION['PaymentOption'] == '2') { // Sve cash   
			$payLater   = $InvoiceDetails+$AgentCommisionDetails;// prebaci da je placeno cash
			$payNow = '0';      // nista ne ostaje za online
			$InvoiceDetails = '0.00';   // nema nista ni za invoice
		}


		// Driver Confirmation Status
		// za JT je pitanje sto bi ivdje trebalo biti 
		// 0 = no driver 
		// 1 = Not Confirmed
		// kako sad stvari stoje, driver odmah vidi transfer 
		// mozda bi trebalo isprazniti DriverID, pa da ga operater stavi kasnije
		$driverConfStatus = '1';
		
		$od->setSiteID('2');
		$od->setOrderID($omOrderID);
		$od->setTNo('1');
		$od->setUserID( $UserID);
		$od->setUserLevelID( $UserLevelID);
		if($_SESSION['GroupProfile'] == 'Agent') $od->setAgentID($_SESSION['AuthUserID']);
		$od->setCustomerID( $CustomerID);
		$od->setTransferStatus( '1');
		$od->setOrderDate(date("Y-m-d"));
		$od->setTaxidoComm( $profit/$_SESSION['ExchFaktor']);
		$od->setServiceID( $_SESSION["ServiceID"]);
		$od->setRouteID( $_SESSION["RouteID"]);
		$od->setFlightNo( $_SESSION["FlightNo"]);
		$od->setFlightTime( $_SESSION["FlightTime"]);
		$od->setPaxName( $_SESSION["MPaxFirstName"] . ' ' . $_SESSION['MPaxLastName']);
		$od->setPickupID($_SESSION['FromID']);
		$od->setPickupName( $fromName);
		$od->setPickupPlace( $_SESSION["PickupPlace"]);
		$od->setPickupAddress( $_SESSION["PickupAddress"]);
		$od->setPickupDate($_SESSION['transferDate']);
		$od->setPickupTime($_SESSION['transferTime']);
		$od->setPickupNotes( $_SESSION["PickupNotes"]);
		$od->setDropID($_SESSION['ToID']);
		$od->setDropName( $toName);
		$od->setDropPlace( $_SESSION["DropPlace"]);
		$od->setDropAddress( $_SESSION["DropAddress"]);
		$od->setDropNotes( $_SESSION["DropNotes"]);
		$od->setPriceClassID( $_SESSION["PriceClassID"]);
		$od->setDetailPrice( $transferPrice/$_SESSION['ExchFaktor']);
		$od->setDriversPrice( $driversPrice);
		$od->setDiscount( $_SESSION["Discount"]);
		$od->setExtraCharge( $extras/$_SESSION['ExchFaktor']);
		$od->setPaymentMethod( $_SESSION["PaymentOption"]);
		$od->setPaymentStatus( $paymentStatus);
		$od->setPayNow( $payNow/$_SESSION['ExchFaktor']);
		$od->setPayLater( $payLater/$_SESSION['ExchFaktor']);
		$od->setInvoiceAmount( nf($InvoiceDetails/$_SESSION['ExchFaktor']) );
		$od->setProvisionAmount( nf($AgentCommisionDetails/$_SESSION['ExchFaktor']));
		$od->setPaxNo($_SESSION['PaxNo']);
		$od->setVehiclesNo( $_SESSION["VehiclesNo"]);
		$od->setVehicleType( $vehicleTypeID );
		$od->setVehicleID( $_SESSION["VehicleID"]);
		$od->setVehiclesNo( $numberOfVehicles);
		$od->setDriverID( $_SESSION["DriverID"]);
		$od->setDriverName( $driverName);
		$od->setDriverEmail( $driverEmail);
		$od->setDriverTel( $driverTel);
		$od->setDriverConfStatus( $driverConfStatus);
		$od->setDriverConfDate( $_SESSION["DriverConfDate"]);
		$od->setDriverConfTime( $_SESSION["DriverConfTime"]);
		$od->setDriverNotes( $_SESSION["DriverNotes"]);
		$od->setDriverPayment( $_SESSION["DriverPayment"]);
		$od->setDriverPaymentAmt( $_SESSION["DriverPaymentAmt"]);
		$od->setRated( $_SESSION["Rated"]);
		$od->setSubPickupDate( $_SESSION["DriverPickupDate"]);
		$od->setSubPickupTime( $_SESSION["DriverPickupTime"]);
		$od->setSubDriver( $_SESSION["SubDriver"]);
		$od->setCar( $_SESSION["Car"]);
		$od->setCashIn( $_SESSION["CashIn"]);
		$od->setFinalNote( $_SESSION["FinalNote"]);

		
		$oneWayID = $od->saveAsNew();

		if (s('returnTransfer')) {
			$od->setSiteID('2');
			$od->setOrderID($omOrderID);
			$od->setTNo('2');
			$od->setUserID( $UserID);
			$od->setUserLevelID( $UserLevelID );
			if($_SESSION['GroupProfile'] == 'Agent') $od->setAgentID($_SESSION['AuthUserID']);
			$od->setCustomerID( $CustomerID );
			$od->setTransferStatus( '1');
			$od->setOrderDate(date("Y-m-d"));
			$od->setTaxidoComm( $profit/$_SESSION['ExchFaktor'] );
			$od->setServiceID( $_SESSION["ServiceID"]);
			$od->setRouteID( $_SESSION["RouteID"]);
			$od->setFlightNo( $_SESSION["RFlightNo"]);
			$od->setFlightTime( $_SESSION["RFlightTime"]);
			$od->setPaxName( $_SESSION["MPaxFirstName"] . ' ' . $_SESSION['MPaxLastName']);
			$od->setPickupID($_SESSION['ToID']);
			$od->setPickupName( $toName);
			$od->setPickupPlace( $_SESSION["DropPlace"]);
			$od->setPickupAddress( $_SESSION["RPickupAddress"]);
			$od->setPickupDate($_SESSION['returnDate']);
			$od->setPickupTime($_SESSION['returnTime']);
			$od->setPickupNotes( $_SESSION["PickupNotes"]);
			$od->setDropID($_SESSION['FromID']);
			$od->setDropName( $fromName);
			$od->setDropPlace( $_SESSION["PickupPlace"]);
			$od->setDropAddress( $_SESSION["RDropAddress"]);
			$od->setDropNotes( $_SESSION["DropNotes"]);
			$od->setPriceClassID( $_SESSION["PriceClassID"]);
			$od->setDetailPrice( $transferPrice/$_SESSION['ExchFaktor']);
			$od->setDriversPrice( $driversPrice);
			$od->setDiscount( $_SESSION["Discount"]);
			$od->setExtraCharge( $extras/$_SESSION['ExchFaktor']);
			$od->setPaymentMethod( $_SESSION["PaymentOption"]);
			$od->setPaymentStatus( $paymentStatus);
			$od->setPayNow( $payNow/$_SESSION['ExchFaktor']);
			$od->setPayLater( $payLater/$_SESSION['ExchFaktor']);
			$od->setInvoiceAmount( $InvoiceDetails/$_SESSION['ExchFaktor']); 
			$od->setProvisionAmount( $AgentCommisionDetails/$_SESSION['ExchFaktor']);
			$od->setPaxNo($_SESSION['PaxNo']);
			$od->setVehiclesNo( $_SESSION["VehiclesNo"]);
			$od->setVehicleType( $vehicleTypeID );
			$od->setVehicleID( $_SESSION["VehicleID"]);
			$od->setVehiclesNo( $numberOfVehicles );
			$od->setDriverID( $_SESSION["DriverID"]);
			$od->setDriverName( $driverName);
			$od->setDriverEmail( $driverEmail);
			$od->setDriverTel( $driverTel);
			$od->setDriverConfStatus( $driverConfStatus );
			$od->setDriverConfDate( $_SESSION["DriverConfDate"]);
			$od->setDriverConfTime( $_SESSION["DriverConfTime"]);
			$od->setDriverNotes( $_SESSION["DriverNotes"]);
			$od->setDriverPayment( $_SESSION["DriverPayment"]);
			$od->setDriverPaymentAmt( $_SESSION["DriverPaymentAmt"]);
			$od->setRated( $_SESSION["Rated"]);
			$od->setSubPickupDate( $_SESSION["DriverPickupDate"]);
			$od->setSubPickupTime( $_SESSION["DriverPickupTime"]);
			$od->setSubDriver( $_SESSION["SubDriver"]);
			$od->setCar( $_SESSION["Car"]);
			$od->setCashIn( $_SESSION["CashIn"]);  
			$od->setFinalNote( $_SESSION["FinalNote"]);

		
			$returnID = $od->saveAsNew();		
		}// end if (s('returnTransfer'))

		$ExtraSubtotals	= s('ExtraSubtotals');		
		$ExtraServices 	= s('ExtraServices');
		$ExtraItems		= s('ExtraItems');		
		if( count($ExtraItems) > 0 ) {
			foreach($ExtraItems as $rbr => $value) {
				$Exst=$ExtraSubtotals[$rbr];
				if (s('returnTransfer')) $Exst=$Exst/2;
				if($ExtraItems[$rbr] > 0) {
					
					$ox->setOrderDetailsID($oneWayID);
					$ox->setServiceID($rbr);
					$ox->setServiceName($ExtraServices[$rbr]);
					$ox->setPrice( nf( $Exst/ $ExtraItems[$rbr]) );
					$ox->setQty($ExtraItems[$rbr]);
					$ox->setSum($Exst);
					
					$ox->saveAsNew();

				if (s('returnTransfer')) {//dodano spremanje servicea za return transfer (neznam dali ovo uopce treba, ali agentskim bookinzima se ne prikazuju extre za rt)

					$ox->setOrderDetailsID($returnID);
					$ox->setServiceID($rbr);
					$ox->setServiceName($ExtraServices[$rbr]);
					$ox->setPrice( nf( $Exst / $ExtraItems[$rbr] ) );
					$ox->setQty($ExtraItems[$rbr]);
					$ox->setSum($Exst);
					
					$ox->saveAsNew();
					}
				}

			}
		}

		return $omOrderID;
}

