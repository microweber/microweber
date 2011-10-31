<?php class Webservices_model extends Model {
	
	function __construct() {
		parent::Model ();
	
	}
	
	function google_Analytics_listProfiles() {
		
		$email = CI::model('content')->optionsGetByKey ( 'google_analytics_login' );
		$pass = CI::model('content')->optionsGetByKey ( 'google_analytics_pass' );
		require_once ("Zend/Loader.php");
		Zend_Loader::loadClass ( 'Zend_Gdata' );
		Zend_Loader::loadClass ( 'Zend_Gdata_Query' );
		Zend_Loader::loadClass ( 'Zend_Gdata_ClientLogin' );
		$client = Zend_Gdata_ClientLogin::getHttpClient ( $email, $pass, "analytics" );
		$gdClient = new Zend_Gdata ( $client );
		
		try {
			$results = $gdClient->getFeed ( "https://www.google.com/analytics/feeds/accounts/$email" );
			foreach ( $results as $entry ) {
				echo "Profile: " . $entry->title . " - Id: " . $entry->extensionElements [0] . "\n";
			}
		} catch ( Zend_Exception $e ) {
			echo "Caught exception: " . get_class ( $e ) . "\n";
			echo "Message: " . $e->getMessage () . "\n";
		}
	
	}
	
	function google_Analytics_getVisitsAndPageviewsForProfileId($profile_id, $start_date = false, $end_date = false) {
		
		$email = CI::model('content')->optionsGetByKey ( 'google_analytics_login' );
		$pass = CI::model('content')->optionsGetByKey ( 'google_analytics_pass' );
		require_once ("Zend/Loader.php");
		Zend_Loader::loadClass ( 'Zend_Gdata' );
		Zend_Loader::loadClass ( 'Zend_Gdata_Query' );
		Zend_Loader::loadClass ( 'Zend_Gdata_ClientLogin' );
		$client = Zend_Gdata_ClientLogin::getHttpClient ( $email, $pass, "analytics" );
		$gdClient = new Zend_Gdata ( $client );
		
		if ($start_date == false) {
			$start_date = date ( "Y-m-d", mktime ( 0, 0, 0, date ( "m" ) - 1, date ( "d" ), date ( "Y" ) ) );
		}
		
		if ($end_date == false) {
			$end_date = date ( "Y-m-d", mktime ( 0, 0, 0, date ( "m" ) - 0, date ( "d" ), date ( "Y" ) ) );
		}
		
		try {
			$dimensions = array ("ga:region", "ga:city", "ga:latitude", "ga:longitude" );
			$metrics = array ("ga:visits", "ga:pageviews" );
			

			
			$dimensions = array ("ga:week" );
			$metrics = array ("ga:visits", "ga:pageviews" );
			
			
			
			
			//dimensions=ga:country&metrics=ga:visitors
			$reportURL = "https://www.google.com/analytics/feeds/data?ids=$profile_id&" 
			. "dimensions=" . @implode ( ",", $dimensions ) 
			. "&" . "metrics=" . @implode ( ",", $metrics ) 
				."&max-results=5"
			
			. "&" . "start-date=$start_date&" . "end-date=$end_date";
		//	var_dump($reportURL);
			$results = $gdClient->getFeed ( $reportURL );
			$titleRow = 1;
			// To output a row of column labels 
			foreach ( $results as $rep ) {
				if ($titleRow) {
					foreach ( $rep->extensionElements as $elem ) {
						$titles [] = $elem->extensionAttributes ["name"] ["value"];
					}
					echo implode ( ",", $titles ) . "\n";
					echo  "<hr>";
					$titleRow = 0;
				}
				foreach ( $rep->extensionElements as $elem ) {
					$row [] = $elem->extensionAttributes ["value"] ["value"];
				}
				echo implode ( ",", $row ) . "\n";
				echo  "<br>";
			}
		} catch ( Zend_Exception $e ) {
			echo "Caught exception: " . get_class ( $e ) . "\n";
			echo "Message: " . $e->getMessage () . "\n";
		}
	
	}

	
	
function google_Analytics_getKeywordsForProfile($profile_id, $start_date = false, $end_date = false) {
		

		$email = CI::model('content')->optionsGetByKey ( 'google_analytics_login' );
		$pass = CI::model('content')->optionsGetByKey ( 'google_analytics_pass' );
		require_once ("Zend/Loader.php");
		Zend_Loader::loadClass ( 'Zend_Gdata' );
		Zend_Loader::loadClass ( 'Zend_Gdata_Query' );
		Zend_Loader::loadClass ( 'Zend_Gdata_ClientLogin' );
		$client = Zend_Gdata_ClientLogin::getHttpClient ( $email, $pass, "analytics" );
		$gdClient = new Zend_Gdata ( $client );
		
		if ($start_date == false) {
			$start_date = date ( "Y-m-d", mktime ( 0, 0, 0, date ( "m" ) - 1, date ( "d" ), date ( "Y" ) ) );
		}
		
		if ($end_date == false) {
			$end_date = date ( "Y-m-d", mktime ( 0, 0, 0, date ( "m" ) - 0, date ( "d" ), date ( "Y" ) ) );
		}
		
		try {
			$dimensions = array ("ga:region", "ga:city", "ga:latitude", "ga:longitude" );
			$metrics = array ("ga:visits", "ga:pageviews" );
			

			
			$dimensions = array ("ga:keyword" );
			$metrics = array ("ga:pageviews" );
			
			
			
			
			//dimensions=ga:country&metrics=ga:visitors
			$reportURL = "https://www.google.com/analytics/feeds/data?ids=$profile_id&" 
			. "dimensions=" . @implode ( ",", $dimensions ) 
			. "&" . "metrics=" . @implode ( ",", $metrics ) 
				."&max-results=50"
			.'&sort=ga:pageviews'
			. "&" . "start-date=$start_date&" . "end-date=$end_date";
		//	var_dump($reportURL);
			$results = $gdClient->getFeed ( $reportURL );
			$titleRow = 1;
			// To output a row of column labels 
			foreach ( $results as $rep ) {
				if ($titleRow) {
					foreach ( $rep->extensionElements as $elem ) {
						$titles [] = $elem->extensionAttributes ["name"] ["value"];
					}
				//	echo implode ( ",", $titles ) . "\n";
					//echo  "<hr>";
					$titleRow = 0;
				}
				foreach ( $rep->extensionElements as $elem ) {
					//var_dump($elem);
					
					$the_item =  $elem->extensionAttributes ["value"] ["value"];
					var_dump($the_item);
					if(strlen($the_item) > 5){
						print '<pre>';
						$row [] =$the_item;
					print '</pre>';
					}
					
				}
				//echo implode ( ",", $row ) . "\n";
				//echo  "<br>";
			}
		} catch ( Zend_Exception $e ) {
			echo "Caught exception: " . get_class ( $e ) . "\n";
			echo "Message: " . $e->getMessage () . "\n";
		}
	
	}
	
	
	
	
	
	
}
