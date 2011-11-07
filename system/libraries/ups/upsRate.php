<?php

/*
 * 
 * UPS Service Selection Codes
01 – UPS Next Day Air
02 – UPS Second Day Air
03 – UPS Ground
07 – UPS Worldwide Express
08 – UPS Worldwide Expedited
11 – UPS Standard
12 – UPS Three-Day Select
13 Next Day Air Saver
14 – UPS Next Day Air Early AM
54 – UPS Worldwide Express Plus
59 – UPS Second Day Air AM
65 – UPS Saver



*/

class upsRate {
	var $AccessLicenseNumber;
	var $UserId;
	var $Password;
	var $shipperNumber;
	var $credentials;
	
	function setCredentials($access, $user, $pass, $shipper) {
		$this->AccessLicenseNumber = $access;
		$this->UserID = $user;
		$this->Password = $pass;
		$this->shipperNumber = $shipper;
		$this->credentials = 1;
	}
	
	function upsGetRate($PostalCode, $dest_zip, $service, $length, $width, $height, $weight) {
		if ($this->credentials != 1) {
			print 'Please set your credentials with the setCredentials function';
			die ();
		}
		
		// This script was written by Mark Sanborn at http://www.marksanborn.net
		// If this script benefits you are your business please consider a donation
		// You can donate at http://www.marksanborn.net/donate.
		

		// ========== CHANGE THESE VALUES TO MATCH YOUR OWN ===========
		

		$AccessLicenseNumber = $this->AccessLicenseNumber; // Your license number
		$UserId = $this->UserID; // Username
		$Password = $this->Password; // Password
		$PostalCode = $PostalCode; // Zipcode you are shipping FROM
		$ShipperNumber = $this->shipperNumber; // Your UPS shipper number
		

		// =============== DON'T CHANGE BELOW THIS LINE ===============
		

		$data = "<?xml version=\"1.0\"?>
    	<AccessRequest xml:lang=\"en-US\">
    		<AccessLicenseNumber>$AccessLicenseNumber</AccessLicenseNumber>
    		<UserId>$UserId</UserId>
    		<Password>$Password</Password>
    	</AccessRequest>
    	<?xml version=\"1.0\"?>
    	<RatingServiceSelectionRequest xml:lang=\"en-US\">
    		<Request>
    			<TransactionReference>
    				<CustomerContext>Bare Bones Rate Request</CustomerContext>
    				<XpciVersion>1.0001</XpciVersion>
    			</TransactionReference>
    			<RequestAction>Rate</RequestAction>
    			<RequestOption>Rate</RequestOption>
    		</Request>
    	<PickupType>
    		<Code>01</Code>
    	</PickupType>
    	<Shipment>
    		<Shipper>
    			<Address>
    				<PostalCode>$PostalCode</PostalCode>
    				<CountryCode>US</CountryCode>
    			</Address>
			<ShipperNumber>$ShipperNumber</ShipperNumber>
    		</Shipper>
    		<ShipTo>
    			<Address>
    				<PostalCode>$dest_zip</PostalCode>
    				<CountryCode>US</CountryCode>
				<ResidentialAddressIndicator/>
    			</Address>
    		</ShipTo>
    		<ShipFrom>
    			<Address>
    				<PostalCode>$PostalCode</PostalCode>
    				<CountryCode>US</CountryCode>
    			</Address>
    		</ShipFrom>
    		<Service>
    			<Code>$service</Code>
    		</Service>
    		<Package>
    			<PackagingType>
    				<Code>02</Code>
    			</PackagingType>
    			<Dimensions>
    				<UnitOfMeasurement>
    					<Code>IN</Code>
    				</UnitOfMeasurement>
    				<Length>$length</Length>
    				<Width>$width</Width>
    				<Height>$height</Height>
    			</Dimensions>
    			<PackageWeight>
    				<UnitOfMeasurement>
    					<Code>LBS</Code>
    				</UnitOfMeasurement>
    				<Weight>$weight</Weight>
    			</PackageWeight>
    		</Package>
    	</Shipment>
    	</RatingServiceSelectionRequest>";
		$ch = curl_init ( "https://www.ups.com/ups.app/xml/Rate" );
		curl_setopt ( $ch, CURLOPT_HEADER, 1 );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_TIMEOUT, 60 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
		$result = curl_exec ( $ch );
		
	//	var_dump($result);
		
		
		
		
		
		
		
		//echo '<!-- '. $result. ' -->'; // THIS LINE IS FOR DEBUG PURPOSES ONLY-IT WILL SHOW IN HTML COMMENTS
		$data = strstr ( $result, '<?php ' );
		$xml_parser = xml_parser_create ();
		xml_parse_into_struct ( $xml_parser, $data, $vals, $index );
		xml_parser_free ( $xml_parser );
		$params = array ();
		$level = array ();
		foreach ( $vals as $xml_elem ) {
			if ($xml_elem ['type'] == 'open') {
				if (array_key_exists ( 'attributes', $xml_elem )) {
					list ( $level [$xml_elem ['level']], $extra ) = array_values ( $xml_elem ['attributes'] );
				} else {
					$level [$xml_elem ['level']] = $xml_elem ['tag'];
				}
			}
			if ($xml_elem ['type'] == 'complete') {
				$start_level = 1;
				$php_stmt = '$params';
				while ( $start_level < $xml_elem ['level'] ) {
					$php_stmt .= '[$level[' . $start_level . ']]';
					$start_level ++;
				}
				$php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';
				eval ( $php_stmt );
			}
		}
		curl_close ( $ch );
		return $params ['RATINGSERVICESELECTIONRESPONSE'] ['RATEDSHIPMENT'] ['TOTALCHARGES'] ['MONETARYVALUE'];
	}
	
	// Define the function getRate() - no parameters
	function getRate123($PostalCode, $dest_zip, $service, $length, $width, $height, $weight) {
		if ($this->credentials != 1) {
			print 'Please set your credentials with the setCredentials function';
			die ();
		}
		$data = "<?xml version=\"1.0\"?>  
		<AccessRequest xml:lang=\"en-US\">  
		    <AccessLicenseNumber>$this->AccessLicenseNumber</AccessLicenseNumber>  
		    <UserId>$this->UserID</UserId>  
		    <Password>$this->Password</Password>  
		</AccessRequest>  
		<?xml version=\"1.0\"?>  
		<RatingServiceSelectionRequest xml:lang=\"en-US\">  
		    <Request>  
			<TransactionReference>  
			    <CustomerContext>Bare Bones Rate Request</CustomerContext>  
			    <XpciVersion>1.0001</XpciVersion>  
			</TransactionReference>  
			<RequestAction>Rate</RequestAction>  
			<RequestOption>Rate</RequestOption>  
		    </Request>  
		<PickupType>  
		    <Code>01</Code>  
		</PickupType>  
		<Shipment>  
		    <Shipper>  
			<Address>  
			    <PostalCode>$PostalCode</PostalCode>  
			    <CountryCode>US</CountryCode>  
			</Address>  
		    <ShipperNumber>$this->ShipperNumber</ShipperNumber>  
		    </Shipper>  
		    <ShipTo>  
			<Address>  
			    <PostalCode>$dest_zip</PostalCode>  
			    <CountryCode>US</CountryCode>  
			<ResidentialAddressIndicator/>  
			</Address>  
		    </ShipTo>  
		    <ShipFrom>  
			<Address>  
			    <PostalCode>$PostalCode</PostalCode>  
			    <CountryCode>US</CountryCode>  
			</Address>  
		    </ShipFrom>  
		    <Service>  
			<Code>$service</Code>  
		    </Service>  
		    <Package>  
			<PackagingType>  
			    <Code>02</Code>  
			</PackagingType>  
			<Dimensions>  
			    <UnitOfMeasurement>  
				<Code>IN</Code>  
			    </UnitOfMeasurement>  
			    <Length>$length</Length>  
			    <Width>$width</Width>  
			    <Height>$height</Height>  
			</Dimensions>  
			<PackageWeight>  
			    <UnitOfMeasurement>  
				<Code>LBS</Code>  
			    </UnitOfMeasurement>  
			    <Weight>$weight</Weight>  
			</PackageWeight>  
		    </Package>  
		</Shipment>  
		</RatingServiceSelectionRequest>";
		$ch = curl_init ( "https://www.ups.com/ups.app/xml/Rate" );
		curl_setopt ( $ch, CURLOPT_HEADER, 1 );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_TIMEOUT, 60 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
		$result = curl_exec ( $ch );
		
		var_dump ( $result );
		
		// echo '<!-- '. $result. ' -->'; // THIS LINE IS FOR DEBUG PURPOSES ONLY-IT WILL SHOW IN HTML COMMENTS  
		$data = strstr ( $result, '<?php ' );
		$xml_parser = xml_parser_create ();
		xml_parse_into_struct ( $xml_parser, $data, $vals, $index );
		xml_parser_free ( $xml_parser );
		$params = array ();
		$level = array ();
		foreach ( $vals as $xml_elem ) {
			if ($xml_elem ['type'] == 'open') {
				if (array_key_exists ( 'attributes', $xml_elem )) {
					list ( $level [$xml_elem ['level']], $extra ) = array_values ( $xml_elem ['attributes'] );
				} else {
					$level [$xml_elem ['level']] = $xml_elem ['tag'];
				}
			}
			if ($xml_elem ['type'] == 'complete') {
				$start_level = 1;
				$php_stmt = '$params';
				while ( $start_level < $xml_elem ['level'] ) {
					$php_stmt .= '[$level[' . $start_level . ']]';
					$start_level ++;
				}
				$php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';
				eval ( $php_stmt );
			}
		}
		curl_close ( $ch );
		return $params ['RATINGSERVICESELECTIONRESPONSE'] ['RATEDSHIPMENT'] ['TOTALCHARGES'] ['MONETARYVALUE'];
	}
}
?>
