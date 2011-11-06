UPS Time In Transit Calculator


Sample Usage:

            $tit = new c_package_timeintransit();
            $tit->initialize($licenceN,$userid,$pass);
            $tit->setPackageProperty('KGS','Kilograms',$wvalue_total,'USD',$curr_value);
            $tit->setShipFrom($from_city,$from_state,$from_zip,$from_country);
            $tit->setShipTo($toCountry,$toZip);
            $tit->c_package_timeintransit_do();
            #debug($tit->debug());
            $response=$tit->get_response();
