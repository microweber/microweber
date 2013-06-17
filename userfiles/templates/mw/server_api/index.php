<?php
 
 $url = "http://microweber.com/members/includes/api.php"; # URL to WHMCS API file
 $username = "api"; # Admin username goes here
 $password = "mwapi123z"; # Admin password goes here
$postfields = array();
 $postfields["username"] = $username;
 $postfields["password"] = md5($password);
 
 
 $action = $_REQUEST['action'];
 
 
 
 switch($action ){
     case '':

         break;


     default:
         break;
 }
 
 
 
 
 
 
 
 
 
 
 
 $postfields["action"] = "addinvoicepayment"; #action performed by the [[API:Functions]]
 $postfields["invoiceid"] = "1";
 $postfields["transid"] = "TEST";
 $postfields["gateway"] = "mailin";
 
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, $url);
 curl_setopt($ch, CURLOPT_POST, 1);
 curl_setopt($ch, CURLOPT_TIMEOUT, 100);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
 $data = curl_exec($ch);
 curl_close($ch);
 $results = array();
 $data = explode(";",$data);
 foreach ($data as $temp) { 
   $temp = explode("=",$temp);
   if(isset($temp[0]) and isset($temp[1])){
   $results[$temp[0]] = $temp[1];
   }
 }
 var_dump($results);
 if ($results["result"]=="success") {
   # Result was OK!
 } else {
   # An error occured
   echo "The following error occured: ".$results["message"];
 }


 