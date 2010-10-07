Shipping Rates Calculator Class

Sample usage:

        $MyUPS= new ups();
        $MyUPS->setCurlVerifyCert(false); 			#Do not use SSL Certificates
        $MyUPS->SetAccountInfo($ups_xml_access_key,$ups_userid,$ups_password);
        $MyUPS->SetPickupType(01); 				#Set daily-pickup
        $MyUPS->SetShipper($shipper_city,
                           $shipper_state,
                           $shipper_zip,
                           $shipper_country);
    
        $MyUPS->SetShipFrom(
                           $ship4om_city,
                           $ship4om_state,
                           $ship4om_zip,
                           $ship4om_country);

        $MyUPS->SetShipTo(  addslashes(trim($tocity)),
                            addslashes(trim($tostate)),
                            addslashes(trim($tozip)),
                            addslashes(trim($tocountry)),
                            $residental = true);

	$weight = 1;
	$price  = 100;
	$added_handling_price = 10;
        $MyUPS->AddPackage('02','My Sample Package',$weight,$price,'LBS','USD');
        $MyUPS->ModeRateShop();
        $MyUPS->SetRateListLimit('01','02','03');
        $MyUPS->GetRateListShort($added_handling_price);  # + 10$

        $arr_shippings = array(                           #Result Array
        'US UPS Next Day Air' => $MyUPS->ModeGetRate('01'),
        'US UPS 2nd Day Air'  => $MyUPS->ModeGetRate('02'),
        'US UPS Ground'       => $MyUPS->ModeGetRate('03'));
        $connecterr = 0 ;                     
        $ratesselect = false;
        if($ratesselect == false){$connecterr = 1;}   #ERROR CONNECTING TO UPS SERVICE

        #$errors = $MyUPS->debug();

        #$errcode = $MyUPS->GetErrorCode()+$connecterr;
        #if($errcode > 0){
        #$err = $MyUPS->GetErrorDescription();
