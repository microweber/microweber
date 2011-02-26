<?php

define("PATH_TO_THISFILE", "include/ups.com/labels");
define("PATH_TO_CALLBACK_FILE", "include/ups.com/labels");        #path to callback.handler.php
define("UPS_USERID","");				  #Set your ups  User Id here
define("UPS_PASSWORD","");				  #Set your ups Password here

$tempDir4Cookies = $_SERVER['DOCUMENT_ROOT']."/temp";     #Temporary Directory to save cookies files
$labelsDir = $_SERVER['DOCUMENT_ROOT']."/temp/labels";    #Temporary directory to cache ups labels


$shipNumberSendUrl = "http://".$_SERVER['SERVER_NAME']."/";
$shipNumberSendUrlParams = PATH_TO_THISFILE."/getupsimage.php?event=updateordershipmenttracking";
$userId = UPS_USERID;
$userPassword = UPS_PASSWORD;

# --- config package
$package1DeclaredValue      = "1";   		#DEFAULT 1$ default
$selectedService            = "003"; 		#DEFAULT UPS Ground default
$selectedPackaging          = "02";  		#DEFAULT Pack type = 'package'
$selectedNumberofPackages   = "1";   		# 1 pack
$shipperContactName         = "ContactName"; 	#some name
$package1Oversize           = "0";   		#DEFAULT we have not oversized package
$package1Weight             = "1";   		#DEFAULT package weight default 1 lbs

# ======= DEFAULTS =============
$orderId = "";
$Company_to    = "Company";
$PostalCode_to = false;     	#some val
$City_to       = false;    	#some val
$State_to      = false;   	#some val
$Address1_to   = false;         #some val
$Country_to    = false; 	#some val


?>