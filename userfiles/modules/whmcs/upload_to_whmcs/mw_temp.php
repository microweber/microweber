<?php



















function site_url($add_string = false)
{
    static $u1;
    if ($u1 == false) {
        $pageURL = 'http';
        if (isset($_SERVER["HTTPS"]) and ($_SERVER["HTTPS"] == "on")) {
            $pageURL .= "s";
        }

        $subdir_append = false;
        if (isset($_SERVER['PATH_INFO'])) {
            // $subdir_append = $_SERVER ['PATH_INFO'];
        } else {
            $subdir_append = $_SERVER['REQUEST_URI'];
        }

        //  var_dump($_SERVER);
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"];
        }
        $pageURL_host = $pageURL;
        $pageURL .= $subdir_append;
        if (isset($_SERVER['SCRIPT_NAME'])) {
            $d = dirname($_SERVER['SCRIPT_NAME']);
            $d = trim($d, '/');
        }

        if (isset($_SERVER['QUERY_STRING'])) {
            $pageURL = str_replace($_SERVER['QUERY_STRING'], '', $pageURL);
        }

        //$url_segs1 = str_replace($pageURL_host, '',$pageURL);
        $url_segs = explode('/', $pageURL);
        $i = 0;
        $unset = false;
        foreach ($url_segs as $v) {
            if ($unset == true) {
                unset($url_segs[$i]);
            }
            if ($v == $d) {

                $unset = true;
            }

            $i++;
        }
        $url_segs[] = '';
        $u1 = implode('/', $url_segs);
    }
    return $u1 . $add_string;
}


$cache_time = 3600;
// Time in seconds to keep a page cached
$cache_folder = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR;
if (!is_dir($cache_folder)) {
    mkdir($cache_folder);
}
$the_request = $_REQUEST;
if (isset($the_request['valid_domain'])) {
    $valid_domain1 = parse_url($the_request['valid_domain']);
    // var_dump($valid_domain);
    $result_cache = $valid_domain1["host"];
    $cache_folder .= $result_cache . DIRECTORY_SEPARATOR;
    if (!is_dir($cache_folder)) {
        mkdir($cache_folder);
    }

}

// Folder to store cached files (no trailing slash)
$cache_filename = $cache_file_name = $cache_folder . md5($_SERVER['REQUEST_URI']);
// Location to lookup or store cached file
//Check to see if this file has already been cached
// If it has get and store the file creation time

if (file_exists($cache_file_name)) {
    // readfile($cache_filename);
    // The cached copy is still valid, read it into the output buffer
    // die();
}


define("MW_WHM_LOCAL_API_USER", 'admin');

require ("dbconnect.php");
require ("includes/functions.php");


class MwWHM
{

   function get_client_products($the_request){
	   if(isset($the_request['clientid'])){
			   $clientid = intval($the_request['clientid']);
					
					if($clientid != 0){
				$command = "getclientsproducts";
		 $adminuser = MW_WHM_LOCAL_API_USER;
		 $values["clientid"] = $clientid;
		  $values["limitnum"] = 99999;
		  
		 $results = localAPI($command,$values,$adminuser);
		 
		 
		 
			   return $results;
			   }
			   
		   }
   }
   
   
   
     function get_client_info($the_request)
    {
        
		 if(isset($the_request['clientid'])){
			   $clientid = intval($the_request['clientid']);
					
					if($clientid != 0){
		
		
		$query = "SELECT id,firstname,lastname,companyname,email,lastlogin FROM `tblclients` WHERE id='$clientid'   ";
		 
        $result_q = mysql_query($query) or die(mysql_error());
        $products = array();
        while ($row = @mysql_fetch_array($result_q)) {
            $products[] = $row;
        }
        if (!empty($products)) {
            $result = $products;
            return $result;
        }
		
		
		
					}
		 }
		
		
    }
	
	
	
	   function get_product_invoices($the_request)
    {
        
		 if(isset($the_request['product_id'])){
			   $clientid = intval($the_request['product_id']);
					
					if($clientid != 0){
						
						$query = "SELECT id,orderid  FROM `tblhosting` WHERE id='$clientid'   ";
        $result_q = mysql_query($query) or die(mysql_error());
        $ordids = array();
        while ($row = @mysql_fetch_array($result_q)) {
            $ordids[] = $row['orderid'];
        }
		 
		if (!empty($ordids)) {
			$ordids_i = implode(',',$ordids);
			
				$query = "SELECT invoiceid FROM `tblorders` WHERE id in ($ordids_i)  ";
				 
        $result_q = mysql_query($query) or die(mysql_error());
        $orders = array(); 
        while ($row = @mysql_fetch_array($result_q)) {
            $orders[] = $row['invoiceid'];
        }
			
		}
     
		$ordids_q= '';
		if (!empty($orders)) {
			$ordids_i = implode(',',$orders);
			
			$ordids_q= " or invoiceid in ($ordids_i) ";
			
			
		}
		$query = "SELECT * FROM `tblinvoiceitems` WHERE relid='$clientid'   " .$ordids_q;
		//return $query;
        $result_q = mysql_query($query) or die(mysql_error());
        $products = array();
        while ($row = @mysql_fetch_array($result_q)) {
            $products[] = $row;
        }
        if (!empty($products)) {
            $result = $products;
			 
            return $result;
        }
		
		
		
					}
		 }
		
		
    }
   
   
    function get_client_invoices($the_request)
    {
        
		 if(isset($the_request['clientid'])){
			   $clientid = intval($the_request['clientid']);
					
					if($clientid != 0){
		
		
		$query = "SELECT * FROM `tblinvoices` WHERE userid='$clientid'   ";
        $result_q = mysql_query($query) or die(mysql_error());
        $products = array();
        while ($row = @mysql_fetch_array($result_q)) {
            $products[] = $row;
        }
        if (!empty($products)) {
            $result = $products;
            return $result;
        }
		
		
		
					}
		 }
		
		
    }
   
   
   
   
   function upgrade_product($the_request){

	if (isset($the_request['clientid']) and isset($the_request['newproductid'])) {

            $result = false;
            //$result = $the_request;
			
			$clientid = intval($the_request['clientid']);
			$newproductid = intval($the_request['newproductid']);
			if($clientid != 0 and $newproductid != 0){
	


 
 
 						$command = "upgradeproduct";
						 $adminuser = MW_WHM_LOCAL_API_USER;
						  $values = array();
						 $values["clientid"] = $clientid;
						  //$values["pid"] = intval($the_request['pid']);
 
  
 
  $values["serviceid"] = intval($the_request['pid']);
 $values["type"] = "product";
 $values["newproductid"] = $newproductid;
 $values["newproductbillingcycle"] = "monthly";
 $values["paymentmethod"] = "paypal";
 
 
  if (!isset($the_request['confirm_upgrade'])) {
  $values["calconly"] = "true";
 
 }
 
  

 $results = localAPI($command,$values,$adminuser);
 exit(json_encode($results));
  return $result;
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
			}
			
	}
	   
   }
   
   
   
   
    function add_order($the_request)
    {
      
	  
	
	  
	    if (isset($the_request['clientid']) and isset($the_request['domain'])) {

            $result = false;
            //$result = $the_request;
			
			$clientid = intval($the_request['clientid']);
			
			if($clientid != 0){
				
				
				
						$command = "addorder";
						 $adminuser = MW_WHM_LOCAL_API_USER;
						 $values["clientid"] = $clientid;
						 if (isset($the_request['pid'])) {
							 $values["pid"] = intval($the_request['pid']);
						 } else {
							 
							$hostings = $this->get_hosting_products(); 
							if(!empty($hostings)){
								//$pid = 
							 foreach($hostings as $hosting){
								 if(isset($hosting['id'])){
									  $last_pid = $hosting['id'];
									  
									  if(isset($hosting['paytype']) and $hosting['paytype'] == 'free'){
										  $pid = $hosting['id'];;
									  }
							
								 }
													
							 }
							 
							  if (isset($pid)) {
								   $values["pid"] = intval($pid);
							  } else if (isset($last_pid)) {
								   $values["pid"] = intval($last_pid);
							  }
							 
							 
							}
							
							
							 
						 }
						  if (isset($the_request['clientip'])) {
							  
							   $values["clientip"] = trim($the_request['clientip']);
						  }
						 
						 
						 
						 
						 
						 
						 
						 $check_if_domain_taken = $the_request['domain'];
						 
						 $check_domain = $this->check_domain_taken($check_if_domain_taken);
						 
						 if(isset( $check_domain['status']) and  $check_domain['status'] == 'unavailable'){
							 $result = array('result' =>'error', 'message'=>'This domain is already taken');
							 return $result;
    					 }
						 
						 
						// 
						 $values["domain"] = $the_request['domain'];
						 $values["billingcycle"] = "monthly";
						 $autosetup = 1;
						// $values["addons"] = "1,3,9";
						// $values["customfields"] = base64_encode(serialize(array("1"=>"Google")));
						if(!stristr($the_request['domain'], 'microweber.com')){
							$values["domaintype"] = "register";
						 	$values["regperiod"] = "1";
							 $autosetup = false;
						}
						 
						  $values["paymentmethod"] = "paypalexpress";
						  $values["paymentmethod"] = "paypal";
						// $results = localAPI($command,$values,$adminuser);
						  
								 
							if($autosetup == true and isset( $results['result'] ) and	 $results['result']  == 'success' and isset( $results['orderid'] )){
								$order_id  = $results['orderid'];
							 	 $command = "acceptorder";
								 $values = array();
								 $values["orderid"] =$results['orderid'];
								 $values["autosetup"] = true;
								 $values["sendemail"] = false;
								 
							//	 $results = localAPI($command,$values,$adminuser);
								 
								 
								 $hosting_data = $this->get_hosting_data_by_order_id($order_id);
								 
								 if(isset($hosting_data['id'])){
									  $values = array();
								  $command = "modulecreate";
								 $values["accountid"] =$hosting_data['id'];
								// $results2 = localAPI($command,$values,$adminuser);
																 
								 }
								 
																 
								 
								 
															
								
							}
									
									
									
									
									
									
										
								return $results;		
				
				
				
				
				
				
				
			}
			
	
			
			
			
            //$values = array();
//            $values["email"] = $the_request['email'];
//
//            $values["password"] = $the_request['password'];
//            $validatelogin = localAPI('validatelogin', $values, MW_WHM_LOCAL_API_USER);
//			
//			return $validatelogin;
//            if (isset($validatelogin['userid'])) {
//                $result = $validatelogin;
//
//            }  

            return $result;

        }
    }


    function sync_user($the_request)
    {
       
	  
	    if (isset($the_request['email']) and isset($the_request['password2'])) {

            $result = false;
            //$result = $the_request;
            $values = array();
            $values["email"] = $the_request['email'];

            $values["password2"] = $the_request['password2'];
			 
            $validatelogin = localAPI('validatelogin', $values, MW_WHM_LOCAL_API_USER);
		 
			 
			if (isset($validatelogin['result']) and $validatelogin['result'] == 'error') {
			  $check_user_by_email = $this->get_user_by_email($values["email"]);
			   
			  if(!empty($check_user_by_email) and isset($check_user_by_email['id'])){
				  $result = array('result' =>'error', 'userid'=> $check_user_by_email['id']);
				  
				  return $result;
				  
				 // $result = $check_user_by_email;
				  
			  }
			}
			
            if (isset($validatelogin['userid'])) {
                $result = $validatelogin;

            } else {
                $command = "addclient";
                $adminuser = MW_WHM_LOCAL_API_USER;

                $values = array();
                $values["email"] = $the_request['email'];
                $values["password2"] = $the_request['password2'];
                $values["skipvalidation"] = true;

                $result = localAPI($command, $values, $adminuser);
            }

            return $result;

        }
    }
	
	
	function check_domain_taken($domain){
		
	 $result = false;
                $skip = false;
                $name = ($domain);
                //	$name = $name . ' \' ; SELECT id, \\* FROM tblproducts where id is not NULL ; #';
                $name = mw_safe_string_escape($name);
                $name = str_replace('\\\\', '/', $name);
                $name = str_replace('///', '/', $name);
                $name = str_replace('//', '/', $name);
                $name = str_replace('\\', '/', $name);
                $name = htmlspecialchars($name);
                $domain = $name;

                $qzq = "SELECT  count(domain) as qty FROM tblhosting where domain='$domain' ";
                $resultz = mysql_query($qzq);
                //$result[] = $qzq;
                $resultz = mysql_fetch_array($resultz);

                //$result = $resultz;

                $result['result'] = "success";
                //$result[] = $resultz;
                if (intval($resultz['qty']) > 0) {


                    $result['status'] = 'unavailable';
                    $result['message'] = 'This domain is already taken';
                } else {

                    $valid_domain = parse_url($domain);
                    $valid_domain_check = parse_url(site_url());

                    if (isset($valid_domain["host"]) and isset($valid_domain_check["host"]) and $valid_domain["host"] == $valid_domain_check["host"]) {
                        //$result['status'] = array('unavailable' => 'This domain is reserved');
                        $result['status'] = 'unavailable';
                        $result['message'] = 'This domain is reserved';

                    } else if (isset($valid_domain["path"]) and isset($valid_domain_check["host"]) and $valid_domain["path"] == $valid_domain_check["host"]) {
                        //$result['status'] = array('unavailable' => 'This domain is reserved');
                        $result['status'] = 'unavailable';
                        $result['message'] = 'This domain is reserved';
                    } else {
                        $result['status'] = 'available';
                        $result['message'] = 'This domain is free';
                        //$result['status'] = array('available' => 'This domain is free');

                    }


                }
return $result;
       
		
	}
	
	
	

    function test2($params)
    {
        print 'test2' . print_r($params);
    }

    function get_hosting_products()
    {
        $query = "SELECT * FROM `tblproducts` WHERE type='hostingaccount' order by tblproducts.order desc  ";
        $result_q = mysql_query($query) or die(mysql_error());
        $products = array();
        while ($row = @mysql_fetch_array($result_q)) {
            $products[] = $row;
        }
        if (!empty($products)) {
            $result = $products;
            return $result;
        }
    }
	
	
	function get_hosting_data_by_order_id($ord_id)
    {
       
	   $ord_id = intval($ord_id);
	   $query = "SELECT * FROM `tblhosting` WHERE orderid=".$ord_id." limit 1  ";
        $result_q = mysql_query($query) or die(mysql_error());
        $products = array();
        while ($row = @mysql_fetch_array($result_q)) {
            $products  = $row;
			 
        }
        return  $products;
    }
	
	
	
	
	function get_user_by_email($email)
    {
       
	   $email = mw_safe_string_escape($email);
	   $query = "SELECT * FROM `tblclients` WHERE email LIKE '".$email."' limit 1  ";
        $result_q = mysql_query($query) or die(mysql_error());
        $products = array();
        while ($row = @mysql_fetch_array($result_q)) {
            $products  = $row;
			 
        }
        return  $products;
    }


}


$whm_ex = new MwWHM();


$actions_all = array();

if (isset($_REQUEST['postfields_all']) and is_array($_REQUEST['postfields_all']) and !empty($_REQUEST['postfields_all'])) {
    $actions_all = $_REQUEST['postfields_all'];

} else {
    $actions_all[] = $the_request;
}
 

$result = array();
foreach ($actions_all as $the_request) {

    $what_to_do_master = false;
    if (isset($the_request['action']) and $the_request['action'] == 'validate_license') {
        $what_to_do_master = 'validate_license';
    }
    if (isset($the_request['action']) and $the_request['action'] == 'handshake') {
        $what_to_do_master = 'handshake';
    }

    if (isset($the_request['action']) and $what_to_do_master == false) {
        $what_to_do_master = $the_request['action'];
    }



    switch ($what_to_do_master) {


        case 'test':

            # Set Vars
            $command = 'SendEmail';
            $values = array('messagename' => 'Test Template', 'id' => '1',);
            $adminuser = 'admin';

            # Call API
            $results = localAPI($command, $values, MW_WHM_LOCAL_API_USER);
            var_dump($results);

            break;


        case 'old_sync_user':


            if (isset($the_request['email']) and isset($the_request['password2'])) {

                //$result = $the_request;
                $values = array();
                $values["email"] = $the_request['email'];

                $values["password2"] = $the_request['password2'];
                $validatelogin = localAPI('validatelogin', $values, MW_WHM_LOCAL_API_USER);
                if (isset($validatelogin['userid'])) {
                    $result = $validatelogin;

                } else {
                    $command = "addclient";
                    $adminuser = MW_WHM_LOCAL_API_USER;

                    $values = array();
                    $values["email"] = $the_request['email'];
                    $values["password2"] = $the_request['password2'];
                    $values["skipvalidation"] = true;

                    $result = localAPI($command, $values, $adminuser);
                }


            }


            break;


        case 'check_if_domain_taken' :
            $cache_time = 30;
            //$result = $the_request;


            if (isset($the_request['domain'])) {
                $skip = false;
                $name = ($the_request['domain']);
                //	$name = $name . ' \' ; SELECT id, \\* FROM tblproducts where id is not NULL ; #';
                $name = mw_safe_string_escape($name);
                $name = str_replace('\\\\', '/', $name);
                $name = str_replace('///', '/', $name);
                $name = str_replace('//', '/', $name);
                $name = str_replace('\\', '/', $name);
                $name = htmlspecialchars($name);
                $domain = $name;

                $qzq = "SELECT  count(domain) as qty FROM tblhosting where domain='$domain' ";
                $resultz = mysql_query($qzq);
                //$result[] = $qzq;
                $resultz = mysql_fetch_array($resultz);

                //$result = $resultz;

                $result['result'] = "success";
                //$result[] = $resultz;
                if (intval($resultz['qty']) > 0) {


                    $result['status'] = 'unavailable';
                    $result['message'] = 'This domain is already taken';
                } else {

                    $valid_domain = parse_url($domain);
                    $valid_domain_check = parse_url(site_url());

                    if (isset($valid_domain["host"]) and isset($valid_domain_check["host"]) and $valid_domain["host"] == $valid_domain_check["host"]) {
                        //$result['status'] = array('unavailable' => 'This domain is reserved');
                        $result['status'] = 'unavailable';
                        $result['message'] = 'This domain is reserved';

                    } else if (isset($valid_domain["path"]) and isset($valid_domain_check["host"]) and $valid_domain["path"] == $valid_domain_check["host"]) {
                        //$result['status'] = array('unavailable' => 'This domain is reserved');
                        $result['status'] = 'unavailable';
                        $result['message'] = 'This domain is reserved';
                    } else {
                        $result['status'] = 'available';
                        $result['message'] = 'This domain is free';
                        //$result['status'] = array('available' => 'This domain is free');

                    }


                }

            }


            break;

        case 'old_get_hosting_products' :

            $query = "SELECT * FROM `tblproducts` WHERE type='hostingaccount' order by tblproducts.order desc  ";
            $result_q = mysql_query($query) or die(mysql_error());
            $products = array();
            while ($row = @mysql_fetch_array($result_q)) {
                $products[] = $row;
            }
            if (!empty($products)) {
                $result = $products;
            }

            break;
        case 'domain_suggest' :

//Pull ENOM login info from the database - *thanks MACscr*
            $query = "SELECT setting,value FROM `tblregistrars` WHERE registrar='enom' AND (setting='Username' OR setting='Password')";
            $result = mysql_query($query) or die(mysql_error());
            while ($row = @mysql_fetch_array($result)) {
                $setting = $row['setting'];
                $enom[$setting] = $row['value'];
            }
            $enomid = decrypt($enom['Username']);
            $enompw = decrypt($enom['Password']);
            $maxspins = 10;

            if (isset($the_request['domain'])) {
                $tld = $the_request['domain'];
            }

            //Do not edit this. We're setting up the URL to retrieve the spins
            $namespinnerurl = "https://reseller.enom.com/interface.asp?command=namespinner&uid=" . $enomid . "&pw=" . $enompw . "&TLD=" . $tld . "&SensitiveContent=true" . "&MaxResults=" . $maxspins . "&ResponseType=XML";
            var_dump($namespinnerurl);
            // Use cURL to get the XML response
            $ch = curl_init($namespinnerurl);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            $xml = curl_exec($ch);
            $curlerror = "ErrNo: " . curl_errno($ch) . " ErrMsg: " . curl_error($ch);
            curl_close($ch);

            if ($xml) {
                $spinnerresults = new SimpleXmlElement($xml, LIBXML_NOCDATA);

                if ($spinnerresults->ErrCount == 0) {
                    for ($i = 0; $i < $maxspins; $i++) {
                        if ($showdotcom && (string)$spinnerresults->namespin->domains->domain[$i]['com'] == "y")
                            $spinner[] = array(
                                'domain' => (string)$spinnerresults->namespin->domains->domain[$i]['name'] . ".com",
                                'netscore' => (int)$spinnerresults->namespin->domains->domain[$i]['comscore'],
                                'tld' => '.com');
                        if ($showdotnet && (string)$spinnerresults->namespin->domains->domain[$i]['net'] == "y")
                            $spinner[] = array(
                                'domain' => (string)$spinnerresults->namespin->domains->domain[$i]['name'] . ".net",
                                'netscore' => (int)$spinnerresults->namespin->domains->domain[$i]['netscore'],
                                'tld' => '.net');
                        if ($showdotcc && (string)$spinnerresults->namespin->domains->domain[$i]['cc'] == "y")
                            $spinner[] = array(
                                'domain' => (string)$spinnerresults->namespin->domains->domain[$i]['name'] . ".cc",
                                'netscore' => (int)$spinnerresults->namespin->domains->domain[$i]['ccscore'],
                                'tld' => '.cc');
                        if ($showdottv && (string)$spinnerresults->namespin->domains->domain[$i]['tv'] == "y")
                            $spinner[] = array(
                                'domain' => (string)$spinnerresults->namespin->domains->domain[$i]['name'] . ".tv",
                                'netscore' => (int)$spinnerresults->namespin->domains->domain[$i]['tvscore'],
                                'tld' => '.tv');
                    }
                    $gotnamespinner = true;
                } else {
                    if ($debug) echo $spinnerresults->errors->Err1;
                    $gotnamespinner = false;
                }
            }

            break;

        case 'validate_license' :
            if (isset($the_request['name'])) {
                $skip = false;
                $name = ($the_request['name']);
                //	$name = $name . ' \' ; SELECT id, \\* FROM tblproducts where id is not NULL ; #';
                $name = mw_safe_string_escape($name);
                $name = str_replace('\\\\', '/', $name);
                $name = str_replace('///', '/', $name);
                $name = str_replace('//', '/', $name);
                $name = str_replace('\\', '/', $name);
                $name = htmlspecialchars($name);
                $mname = 'module-' . $name . '-';

                $qzq = "SELECT id, count(id) as qty FROM tblproducts where configoption2='$mname' limit 2";
                $resultz = mysql_query($qzq);
                //$result[] = $qzq;
                $resultz = mysql_fetch_array($resultz);
                //$result[] = $resultz;
                if (intval($resultz['qty']) > 0) {
                    $skip = false;
                    $the_curent_product_id = $resultz['id'];
                } else {
                    $skip = true;
                    $the_curent_product_id = false;
                }

            }

            if ($skip == false) {
                if (isset($the_request['valid_domain'])) {
                    $valid_domain = parse_url($the_request['valid_domain']);
                    //var_dump($valid_domain);
                    $result1 = $valid_domain["host"];
                    $valid_domain = mysql_real_escape_string($result1);
                }

                if (isset($name) and isset($valid_domain)) {

                    $resultz_q = mysql_query("SELECT id FROM tblcustomfields where fieldname='valid_domain' and relid={$the_curent_product_id} ");
                    $resultz = array();
                    //$resultz = mysql_fetch_array($resultz);
                    while ($resultz[] = mysql_fetch_array($resultz_q)) ;

                    if (!empty($resultz)) {
                        foreach ($resultz as $value) {
                            if (isset($value['id'])) {

                                $q = "SELECT * FROM tblcustomfieldsvalues where fieldid='{$value['id']}' and value='{$valid_domain}' ";
                                //$result[] = $q;
                                $resultz1_q = mysql_query($q);

                                $resultz1 = array();
                                //$resultz = mysql_fetch_array($resultz);
                                while ($resultz1[] = mysql_fetch_array($resultz1_q)) ;

                                //$resultz1 = mysql_fetch_array($resultz1);
                                if ($resultz1 != false and !empty($resultz1)) {
                                    //	$result = array('error' => 'no_license_found');
                                    $fffound = false;
                                    foreach ($resultz1 as $valuez) {
                                        if (isset($valuez['relid']) and intval($valuez['relid']) > 0) {
                                            $q1 = "SELECT id,domain,domainstatus,nextduedate,nextinvoicedate FROM tblhosting where id={$valuez['relid']} and domainstatus='Active' and domain REGEXP '{$mname}*'   limit 1 ";
                                            //	var_dump($q);
                                            $resultz1z = mysql_query($q1);
                                            $resultz1z = mysql_fetch_array($resultz1z);

                                            if ($resultz1z != false and !empty($resultz1z)) {
                                                $resultz1z['module'] = $name;
                                                $resultz1z['host'] = $valid_domain;

                                                $result[] = ($resultz1z);
                                                $fffound = true;
                                            }
                                            //$result

                                        } else {
                                            if ($fffound == false) {
                                                $resultz = mysql_query("SELECT id FROM tblproducts where configoption2='$mname' limit 1");
                                                $resultz = mysql_fetch_array($resultz);
                                                if ($resultz != false and isset($resultz['id'])) {
                                                    $result[] = array('error' => 'no_license_found', 'module' => $name, 'host' => $valid_domain, 'product_id' => $resultz['id'], 'product_activate_link' => site_url('cart.php?a=add&customfield[' . $value['id'] . ']=' . $valid_domain . '&pid=' . $resultz['id']));
                                                }
                                            }
                                        }
                                    }

                                }
                            }
                        }
                    }
                }
            }
            break;

        default :


            if (method_exists($whm_ex, $what_to_do_master)) {

                $result = $whm_ex->$what_to_do_master($the_request);
  
            }


            //	$result = mysql_query("SELECT * FROM tblclients");
            //$result = mysql_fetch_array($result);
            // $result[] = $the_request;
            break;
    }

}


function mw_safe_string_escape($str)
{
    $len = strlen($str);
    $escapeCount = 0;
    $targetString = '';
    for ($offset = 0; $offset < $len; $offset++) {
        switch ($c = $str{$offset}) {
            case "'" :
                // Escapes this quote only if its not preceded by an unescaped backslash
                if ($escapeCount % 2 == 0)
                    $targetString .= "\\";
                $escapeCount = 0;
                $targetString .= $c;
                break;
            case '"' :
                // Escapes this quote only if its not preceded by an unescaped backslash
                if ($escapeCount % 2 == 0)
                    $targetString .= "\\";
                $escapeCount = 0;
                $targetString .= $c;
                break;
            case '\\' :
                $escapeCount++;
                $targetString .= $c;
                break;
            case '*' :
            case '#' :
            case '/*' :
            case '<' :
            case '>' :
            case ';' :
            case ',' :
                $targetString .= "\\";
                $escapeCount++;
                $targetString .= $c;
                break;

            default :
                $escapeCount = 0;
                $targetString .= $c;
        }
    }
    return $targetString;
}


//$result['request'] = $the_request;
$cont = json_encode($result);

$result_c = $result;
$result_c['cache_time'] = strtotime('now');
$cont2 = json_encode($result_c);
file_put_contents($cache_filename, $cont2);
$i_file = $cache_folder . 'index.php';
if (!is_file($i_file)) {
    touch($i_file);
}

print $cont;
exit();
