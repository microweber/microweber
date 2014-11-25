<?php 
define("MW_WHM_LOCAL_API_USER", 'api');

require ("dbconnect.php");
require ("includes/functions.php");

function mw_db_escape_string($value)
    {

        if (!is_string($value)) {
            return $value;
        }
        

        $search = array("\\", "\x00", "\n", "\r", "'", '"', "\x1a");
        $replace = array("\\\\", "\\0", "\\n", "\\r", "\'", '\"', "\\Z");
        $new = str_replace($search, $replace, $value);
         
        return $new;
    }
$debugmode = false;

 


$the_request = $_REQUEST;
if (isset($the_request['email']) and isset($the_request['password2']) and isset($the_request['domain'])) {
$dom = $the_request['domain'];


$dom = parse_url($dom);
 
 
if(!isset($dom["host"]) and !isset($dom["path"])){
	exit('no host');
	
} if(isset($dom["host"])){
	
	$host = $dom["host"];	
}else {
$host = $dom["path"];	
}

if(isset($the_request["email"]) and $the_request["email"] == 'boksiora@gmail.com'){
	
	$debugmode = 1;
}
if(isset($the_request["email"]) and $the_request["email"] == 'petermi2'){
	
	$debugmode = 1;
}


 



                //$result = $the_request;
                $values = array();
                $values["email"] = $the_request['email'];

                $values["password2"] = $the_request['password2'];
                $validatelogin = localAPI('validatelogin', $values, MW_WHM_LOCAL_API_USER);
				
				
				if (!isset($validatelogin['userid'])) {
					$values = array();
                $values["username"] = $the_request['email'];

                $values["password2"] = $the_request['password2'];
                $validatelogin = localAPI('validatelogin', $values, MW_WHM_LOCAL_API_USER);
				
				}
				
				$us = false;
				if (!isset($validatelogin['userid'])) {
				 	$command = "encryptpassword";
 
				 	$values["password2"] = $the_request['password2'];
				 
					 $hash = localAPI($command,$values,MW_WHM_LOCAL_API_USER);
					 $us = mw_whm_get_user_by_enc_pass($the_request['email'], $hash['password']);
					 
					 if (isset($us['password'])) {
								  $command = "decryptpassword";
								 
								 $values["password2"] = $us['password'];
 								 $unhash = localAPI($command,$values,MW_WHM_LOCAL_API_USER);
									 if($unhash['password'] == $the_request['password2']){
										$validatelogin['userid'] = $us['userid'];
									 }
									  
								 }
					 
				}
						
				
				 if($debugmode == true){
								 
								 
								$user_em =  mw_whm_get_user_by_email($the_request['email']);
								 $hashpass_orig=$the_request['password2'];
								 $hashpass_new=md5($user_em['id']).sha1($user_em['email']);
								 if (isset($user_em['id'])) {
					 							/// var_dump($hashpass_orig);
//var_dump($hashpass_new);
							 //var_dump($validatelogin);
											exit();
 									}
									 
									 
									 
					
							
						}
				 if (!isset($validatelogin['userid'])) {
					 		

					 
				 }
		 
				
		
				
				
                if (isset($validatelogin['userid'])) {
                    $result = $validatelogin;
					
					
					
					
					$command = "getclientsproducts";
					$adminuser = MW_WHM_LOCAL_API_USER;
					$values = array();
					$values["clientid"] = $validatelogin['userid'];
					$values["limitnum"] = 99999;
					  
					$results = localAPI($command,$values,$adminuser);
					
					
					if(!empty($results) and isset($results['products'])){
						$prodsucts = $results['products']['product'];
						if(!empty($prodsucts)){
							foreach($prodsucts as $prodsuct){
								
								if(!empty($prodsuct) and isset($prodsuct['domain'])){
									
									if(strtolower($host) == strtolower($prodsuct['domain'])){
										$values = array();
										$values["result"] = 'success';
										$values["userid"] = $validatelogin['userid'];
										$json=json_encode($values);
					print $json;
					exit();
										
									}
								}
								
								
								
								
								
														
							}
							
							
						}
						
						
						
						 
					}
					
					
								
								
					
					 
					
				 
					exit('not found');
					
					
					
					
					}
}

function mw_whm_get_user_by_email($email)
    {
       
	   $email = mw_db_escape_string($email);
	   $query = "SELECT * FROM `tblclients` WHERE email LIKE '".$email."' limit 1  ";
        $result_q = mysql_query($query) or die(mysql_error());
        $products = array();
        while ($row = @mysql_fetch_array($result_q)) {
            $products  = $row;
			 
        }
        return  $products;
    }
	
	
	
	function mw_whm_get_user_by_enc_pass($username, $pass)
    {
       
	   $username = mw_db_escape_string($username);
	   $query = "SELECT * FROM `tblhosting` WHERE username='".$username."'   ";
	 // var_dump($query);
        $result_q = mysql_query($query) or die(mysql_error());
        $products = array();
        while ($row = @mysql_fetch_array($result_q)) {
            return $row;
			 
        }
        return  $products;
    }