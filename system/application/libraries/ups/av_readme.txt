UPS address validation class

SAMPLE USAGE :

        $av = new c_address_validator();
        $av->setUpsAccount($ups_xml_access_key,$ups_userid,$ups_password);

        $err_txt = '';
        $err = 0;
        
        # CHECK >>>
        $av->setVerifyTarget(addslashes($city),addslashes($state),addslashes($zip));
        $av->commit();
        $result = $av->getIsSucces();
        $q      = $av->getQuality();
        $min_quality = 0.81;

        if($result != 1){
            $err_txt = 'Invalid Address .'.$av->getErrorDescription();
            $err = 1;
        }
        elseif($result == 1 && $q > $min_quality){
            $err_txt = 'Address is valid';
            $err = 0;
        }
        elseif($result == 1 && $q < $min_quality){
            $err_txt = 'Address validation is poor, check your City,State,Zip '.$av->getErrorDescription();
            $err = 1;
        }
        #<<<

	#echo $err_txt;
	#echo $err
