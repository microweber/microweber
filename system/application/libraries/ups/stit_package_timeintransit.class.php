<?php

class c_package_timeintransit
{
    
    var $response;
    var $out;
    var $userid;
    var $licenceN;
    var $ups_pass;
    var $from_city;
    var $from_state;
    var $from_country;
    var $from_zip;
    var $wunits;
    var $wvalue;
    var $wunits_txt;
    
    var $curr_code;
    var $curr_value;
    var $data;
    
    var $transmit_to_country;
    var $transmit_to_zip;
    
    function initialize($licenceN,$ups_userid,$ups_password){
        $this->licenceN = $licenceN;
        $this->userid   = $ups_userid;
        $this->ups_pass = $ups_password; 
        
	}
	
	function setShipFrom($city,$state,$zip,$country = 'US'){
	    $this->from_city    = $city;
	    $this->from_state   = $state;
	    $this->from_zip     = $zip;
	    $this->from_country = $country;
	}
	
	function setShipTo($country,$zip){
	    $this->transmit_to_country = $country;
        $this->transmit_to_zip = $zip;
	    
	}
	
	function setPackageProperty($wunits = 'KGS', $wunits_txt = 'Kilograms', $wvalue, $curr_code = 'USD', $cur_value){
	    $this->wunits = $wunits;
	    $this->wunits_txt = $wunits_txt;
	    $this->wvalue = $wvalue;
	    $this->curr_code = $curr_code;
	    $this->curr_value = $cur_value;
	    
    }
    
    function debug(){
        $r = array(
        'from_city'  => $this->from_city,
        'from_state' => $this->from_state,
        'from_zip'   => $this->from_zip,
        'from_country' => $this->from_country,
        'to_country'  => $this->transmit_to_country,
        'to_zip' => $this->transmit_to_zip,
        'pack_value' => $this->curr_value,
        'pack_weight' => $this->wvalue
        );
        return $r;
    }
    
    function c_package_timeintransit_do()
    {

        
        $upsAccessLicenseNumber = $this->licenceN; 
        $upsUserID      = $this->userid;
        $upsPassWord    = $this->ups_pass;
        $upsXpciVersion = "1.0002";
        $description    = "UPS Time In Transit";
        $req_action     = "TimeInTransit";

 /*     $PoliticalDivision2 = "New York";
        $PoliticalDivision1 = "NY";
        $transit_from_cc    = 'US';
        $transit_from_pc    = '10150';
 */
 
        $PoliticalDivision2 = $this->from_city;
        $PoliticalDivision1 = $this->from_state;
        $transit_from_cc    = $this->from_country;
        $transit_from_pc    = $this->from_zip;
        
        $shipment_units_weight_units = $this->wunits;
        $shipment_units_weight_descript = $this->wunits_txt;
        $shipment_units_weight_value = $this->wvalue;
        
        $invoice_curr_code  = $this->curr_code;
        $invoice_curr_value = $this->curr_value;
        
        $ups_pickupdate = date("Ymd"); //pickupdate is NOW ?
        
        
        $data .= "
            <?xml version=\"1.0\" \?>            <AccessRequest xml:lang=\"en-US\">
                <AccessLicenseNumber>$upsAccessLicenseNumber</AccessLicenseNumber>
                <UserId>".$this->userid."</UserId>
                <Password>".$this->ups_pass."</Password>
            </AccessRequest>

            <?xml version=\"1.0\"\?>            <TimeInTransitRequest xml:lang=\"en-US\">
               <Request>
                  <TransactionReference>
                     <CustomerContext>$description</CustomerContext>
                     <XpciVersion>$upsXpciVersion</XpciVersion>
                  </TransactionReference>
                  <RequestAction>$req_action</RequestAction>
               </Request>
               <TransitFrom>
                  <AddressArtifactFormat>
                    <PoliticalDivision2>".$this->from_city."</PoliticalDivision2>
                    <PoliticalDivision1>".$this->from_state."</PoliticalDivision1>

                     <CountryCode>".$this->from_country."</CountryCode>
                     <PostcodePrimaryLow>".$this->from_zip."</PostcodePrimaryLow>
                  </AddressArtifactFormat>
               </TransitFrom>
               <TransitTo>
                  <AddressArtifactFormat>
                     <CountryCode>".$this->transmit_to_country."</CountryCode>
                     <PostcodePrimaryLow>".$this->transmit_to_zip."</PostcodePrimaryLow>
                  </AddressArtifactFormat>
               </TransitTo>".
/*
               <ShipmentWeight>
                  <UnitOfMeasurement>
                     <Code>$shipment_units_weight_units</Code>
                     <Description>$shipment_units_weight_descript</Description>
                  </UnitOfMeasurement>
                  <Weight>$shipment_units_weight_value</Weight>
               </ShipmentWeight>
               <InvoiceLineTotal>
                  <CurrencyCode>$invoice_curr_code</CurrencyCode>
                  <MonetaryValue>$invoice_curr_value</MonetaryValue>
               </InvoiceLineTotal>
                <DocumentsOnlyIndicator/>
*/
               "<PickupDate>$ups_pickupdate</PickupDate>
            </TimeInTransitRequest>";
            
$this->data = $data;
         $ups_server_url = "https://www.ups.com/ups.app/xml/TimeInTransit";
         $ch = curl_init();                         /// initialize a cURL session
         curl_setopt ($ch, CURLOPT_URL,$ups_server_url);        /// set the post-to url (do not include the ?query+string here!)
         curl_setopt ($ch, CURLOPT_HEADER, 0);              /// Header control
         curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);      /// Use this to prevent PHP from verifying the host (later versions of PHP including 5)
         curl_setopt ($ch, CURLOPT_POST, 1);                /// tell it to make a POST, not a GET
         curl_setopt ($ch, CURLOPT_POSTFIELDS, $data);         /// put the query string here starting with "?" 
         curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);          /// This allows the output to be set into a variable $xyz
         $upsResponse = curl_exec ($ch);                /// execute the curl session and return the output to a variable $xyz
         curl_close ($ch);                      /// close the curl session

         
         $resp = $this->XMLParser($upsResponse);

         $response = array();

        foreach ($resp as $v)
        {
        
            if ($v['type'] = 'complete' and '' != $v['value']){
                $html_resp[$v['tag']] = $v['value'];
            }
        }
        
        $this->response = $html_resp;
  
        $this->out = "";
        $this->out.= "<table>";
        foreach ($html_resp as $k => $v){
            $this->out.= "<tr>
                    <td>$k</td>
                    <td>$v</td>
                  </tr>
               ";
       $this->out.= "</table>";
               
        }           
    }
 
    function get_response() 
    {
        return $this->response;
    }
    function get_out() 
    {
        return $this->out;
    }
    
    function XMLParser($simple) 
    {
        $p = xml_parser_create();
        xml_parser_set_option($p,XML_OPTION_CASE_FOLDING,0);
        xml_parser_set_option($p,XML_OPTION_SKIP_WHITE,1);
        xml_parse_into_struct($p,$simple,$vals,$index);
        xml_parser_free($p);

        return $vals;
    }

}
 
 
 
 
 
 
 

?>