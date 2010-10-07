<?php

include_once('config.php');

###################################################################################################
##############################RECEIVE RESPONSE HERE################################################
###################################################################################################
if($_GET['event'] == 'updateordershipmenttracking'){
            $oid        = ($_GET["OrderId"]);          #sent orderid
            $number     = ($_GET["ShipmentNumber"]);   #received tracking number (same as at label)
            $_date      = $_GET["date"];
            $_service   = $_GET["service"];
            $_name      = $_GET["name"];
            $_error     = $_GET["Error"];

            # -- If no Errors occured than we are fine to update tracking number
		include_once(PATH_TO_CALLBACK_FILE.'/callback.handler.php');

                #######################
		#Process response here#
                #######################

              

exit(); #finish
}
###################################################################################################
#############################SEND REQUEST##########################################################
###################################################################################################

if($userId == '' or $userPassword == ''){die('Please configure UPS userid/password');}
    $states = array(
            "Alabama"       => "AL",
            "Alaska"        => "AK",
            "American Samoa"    => "AS",
            "Arizona"       => "AZ",
            "Arkansas"      => "AR",
            "California"        => "CA",
            "Colorado"      => "CO",
            "Connecticut"       => "CT",
            "Delaware"      => "DE",
            "District of Columbia"  => "DC",
            "Florida"       => "FL",
            "Georgia"       => "GA",
            "Guam"          => "GU",
            "Hawaii"        => "HI",
            "Idaho"         => "ID",
            "Illinois"      => "IL",
            "Indiana"       => "IN",
            "Iowa"          => "IA",
            "Kansas"        => "KS",
            "Kentucky"      => "KY",
            "Louisiana"     => "LA",
            "Maine"         => "ME",
            "Maryland"      => "MD",
            "Massachusetts"     => "MA",
            "Michigan"      => "MI",
            "Minnesota"     => "MN",
            "Mississippi"       => "MS",
            "Missouri"      => "MO",
            "Montana"       => "MT",
            "Nebraska"      => "NE",
            "Nevada"        => "NV",
            "New Hampshire"     => "NH",
            "New Jersey"        => "NJ",
            "New Mexico"        => "NM",
            "New York"      => "NY",
            "North Carolina"    => "NC",
            "North Dakota"      => "ND",
            "Northern Mariana Islands" => "MP",
            "Ohio"          => "OH",
            "Oklahoma"      => "OK",
            "Oregon"        => "OR",
            "Pennsylvania"      => "PA",
            "Puerto Rico"       => "PR",
            "Rhode Island"      => "RI",
            "South Carolina"    => "SC",
            "South Dakota"      => "SD",
            "Tennessee"     => "TN",
            "Texas"         => "TX",
            "Utah"          => "UT",
            "Vermont"       => "VT",
            "Virgin Islands"    => "VI",
            "Virginia"      => "VA",
            "Washington"        => "WA",
            "West Virginia"     => "WV",
            "Wisconsin"     => "WI",
            "Wyoming"       => "WY",
        );


// --- from request ---
if (isset($_GET["OrderId"]))
    $orderId = $_GET["OrderId"];
//--- address
if (isset($_GET["Company_to"]))
    $Company_to = $_GET["Company_to"];
if (isset($_GET["PostalCode_to"]))
    $PostalCode_to = $_GET["PostalCode_to"];
if (isset($_GET["City_to"]))
    $City_to = $_GET["City_to"];
if (isset($_GET["State_to"])) {
    $State_to = $_GET["State_to"];
    if(isset($states[$State_to])) {
        $State_to = $states[$State_to];
    }
}
if (isset($_GET["Address1_to"]))
    $Address1_to = $_GET["Address1_to"];
if (isset($_GET["Country_to"]))
    $Country_to = $_GET["Country_to"];
//--- package
if (isset($_GET["package1DeclaredValue"]))
    $package1DeclaredValue = $_GET["package1DeclaredValue"];
if (isset($_GET["selectedService"]))
    $selectedService = $_GET["selectedService"];
if (isset($_GET["selectedPackaging"]))
    $selectedPackaging = $_GET["selectedPackaging"];
if (isset($_GET["selectedNumberofPackages"]))
    $selectedNumberofPackages = $_GET["selectedNumberofPackages"];
if (isset($_GET["shipperContactName"]))
    $shipperContactName = $_GET["shipperContactName"];
if (isset($_GET["package1Oversize"]))
    $package1Oversize = $_GET["package1Oversize"];
if (isset($_GET["package1Weight"]))
    $package1Weight = $_GET["package1Weight"];

//============================================================================

    global $receivedDataSize;
    global $tempDirPath;
    global $cookies;
    $cookies = Array();
    global $ch;

    function construct($tempDirPathT)
    {
        global $receivedDataSize;
        global $tempDirPath;
        global $cookies;
        global $ch;

        $tempDirPath = $tempDirPathT;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        curl_setopt($ch, CURLOPT_HEADER, 0); 
        #
        
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; SV1; MRA 4.8 (build 01709); .NET CLR 1.1.4322; .NET CLR 2.0.50727)");//$_SERVER['HTTP_USER_AGENT']);
        #curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)");//$_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, "callback_headerfunction");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_MUTE, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30 * 60);

        $cookieFileName = $tempDirPath.'/cookie.txt';
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFileName);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFileName);


        //--- debugging otions ---
        //curl_setopt($ch, CURLOPT_VERBOSE, 1);
        //curl_setopt($ch, CURLOPT_NOPROGRESS, 0);
        //---
    }

    function request($url, $urlParameters, $method, $referer = null, $outFileName = null)
    {
        global $receivedDataSize;
        global $tempDirPath;
        global $cookies;
        global $ch;

        if ($method == "post")
        {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            if ("" != $urlParameters && $urlParameters != null)
                curl_setopt($ch, CURLOPT_POSTFIELDS, $urlParameters);
        }
        else if ($method == "get")
        {
            if ("" != $urlParameters)
                $fullURL = $url."?".$urlParameters;
            else
                $fullURL = $url;

            curl_setopt($ch, CURLOPT_URL, $fullURL);
        }
        else
            return null;

        /*
        //--- prepare cookies
        #debug($cookies);
        if(is_array($cookies) && count($cookies) > 0 ){
        #debug($cookies);
        foreach ($cookies as $cookieName => $cookieValue)
            $strCookies[] = $cookieName."   ".urlencode($cookieValue);
        if (count($strCookies) > 0)
        {
            //--- write cookies to file
            $cookieFileName = $tempDirPath.'/cookie.txt';
            #$cFile = fopen($cookieFileName, "w");
            #foreach ($strCookies as $sc)
            #    fwrite($cFile, ".ups.com    TRUE    /   FALSE   0   ".$sc."\n");
            #fclose($cFile);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFileName);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFileName);
            #$cFile = fopen($cookieFileName, "r");
            #fclose($cFile);
        }
        }
        */

        //--- set referer
        if ($referer != null)
            curl_setopt($ch, CURLOPT_REFERER, $referer);

        //--- make request
        $response = curl_exec($ch);
        $receivedDataSize = curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD);

        if ($response != null)
        {
            if ($outFileName != null)
            {
                $ofileName = $tempDirPath."/$outFileName";
                $oFile = fopen($ofileName, "w");
                fwrite($oFile, $response);
                fclose($oFile);
            }
            return $response;
        }
        else
            return null;
    }

    function getReceivedDataSize()
    {
        global $receivedDataSize;
        global $tempDirPath;
        global $cookies;
        global $ch;

        return $receivedDataSize;
    }

    function finalize()
    {
        global $receivedDataSize;
        global $tempDirPath;
        global $cookies;
        global $ch;

        curl_setopt($ch, CURLOPT_COOKIEJAR, $tempDirPath.'/cookie.txt');
        curl_close ($ch);
    }

    function callback_headerfunction($ch, $header)
    {
        global $receivedDataSize;
        global $tempDirPath;
        global $cookies;
        global $ch;

        preg_match('/^Set-Cookie:\s*(.*)$/i', $header, $results);
        if (count($results) > 1)
        {
            $splitCookies = preg_split("/\s*;\s*/i", $results[1]);
            foreach ($splitCookies as $cookie)
            {
                $ckcv = preg_split("/\s*=\s*/i", $cookie, 2);
                if (
                    $ckcv[0] != "Path" &&
                    $ckcv[0] != "path" &&
                    $ckcv[0] != "Domain" &&
                    $ckcv[0] != "domain"
                )
                    $cookies[$ckcv[0]] = $ckcv[1];
            }
        }
        return strlen($header);
    }

    function prepareStr($strg)
    {
        return str_replace("&nbsp;", "", $strg);
    }

    function findShipmentAttrs($resp)
    {
        $result = Array();

        preg_match_all("/<TD[^>]+>\s*<DIV[^>]+>([^<]+)<\/DIV>\s*<\/TD>/i", $resp, $res_s);
        if (count($res_s) > 1)
        {
            $result["date"] = prepareStr($res_s[1][0]);
            $result["name"] = prepareStr($res_s[1][1]);
            $result["service"] = prepareStr($res_s[1][2]);
//          $result["number"] = prepareStr($res_s[1][3]);
        }
        return $result;
    }

    function exitFatalError($orderIdentif, $logText)
    {
        store_log($orderIdentif, $logText);

        //prepare image with error description
        header("Content-type: " . image_type_to_mime_type(IMAGETYPE_GIF));

        $arrErrDesc = getErrorDescription($logText);
        $strErrDescs = Array("Label could not be received.", "", $arrErrDesc["errAction"], $arrErrDesc["errDesc1"], $arrErrDesc["errYEnt"], $arrErrDesc["errDesc2"]);

        $im = imagecreate(672, 392);
        $background_color = imagecolorallocate($im, 255, 255, 255);
        $text_color = imagecolorallocate($im, 128, 0, 0);
        for ($ii = 50, $iw = 0; $ii < 672 && $iw < count($strErrDescs); $ii += 19, $iw++)
        {
            $strs = split("\n", wordwrap($strErrDescs[$iw], 70, "\n"));
            for ($jw = 0; $ii < 672 && $jw < count($strs); $ii += 16, $jw++)
                imagestring($im, 5, 20, $ii,  $strs[$jw], $text_color);
        }
        imagegif($im);
        imagedestroy($im);

        exit();
    }

    function getErrorDescription($etext)
    {
        //--- find $errAction
        preg_match("/<[^>]*class=\"?appspacepad\"?[^>]*>/i", $etext, $rr1);
        if (count($rr1) > 0)
        {
            $appspacepad_pos = strpos($etext, $rr1[0]) + strlen($rr1[0]) - 1;
            preg_match("/>([^<]+)<\//i", $etext, $rr2, PREG_OFFSET_CAPTURE, $appspacepad_pos);
            if (count($rr2) > 0)
            {
                $errAction = str_replace(Array(chr(13), chr(10)), "", $rr2[1][0]);
            }
        }
        else
            $appspacepad_pos = 0;

        //--- find $errDesc1
        preg_match("/<[^>]+=['\"]?redErrorBold['\"]?[^>]*>/i", $etext, $rr3, PREG_OFFSET_CAPTURE, $appspacepad_pos);
        if (count($rr3) > 0)
        {
            $redErrorBold1_pos = strpos($etext, $rr3[0][0]) + strlen($rr3[0][0]) - 1;
            preg_match("/>([^<]+)</i", $etext, $rr4, PREG_OFFSET_CAPTURE, $redErrorBold1_pos);
            if (count($rr4) > 0)
            {
                $errDesc1 = str_replace(Array(chr(13), chr(10)), "", $rr4[1][0]);
            }
        }
        else
        {
            $redErrorBold1_pos = $appspacepad_pos;
            $errDesc1 = "reg not found";
        }

        //--- find You entered
        preg_match("/<\w+[^>]+=['\"]?redErrorBold['\"]?[^>]*>/i", $etext, $rr5, PREG_OFFSET_CAPTURE, $redErrorBold1_pos);
        if (count($rr5) > 0)
        {
            $redErrorBold2_pos = strpos($etext, $rr5[0][0]) + strlen($rr5[0][0]) - 1;
            preg_match("/>([^<]+)<\//i", $etext, $rr6, PREG_OFFSET_CAPTURE, $redErrorBold2_pos);
            if (count($rr6) > 0)
            {
                $errYEnt = str_replace(Array(chr(13), chr(10)), "", $rr6[1][0]);
            }
        }
        else
            $redErrorBold2_pos = $redErrorBold1_pos;

        //--- find $errDesc2
        preg_match("/(<[^>]+>\s*)+([^<]+)/i", $etext, $rr7, PREG_OFFSET_CAPTURE, $redErrorBold2_pos);
        if (count($rr7) > 0)
        {
            $errDesc2 = str_replace(Array(chr(13), chr(10)), "", $rr7[count($rr7) - 1][0]);
        }

        return Array("errAction" => $errAction, "errDesc1" => $errDesc1, "errYEnt" => $errYEnt, "errDesc2" => $errDesc2);
    }

    function store_log($orderIdentif, $logText)
    {
        global $labelsDir;
        //store log
        $gifLogName = $labelsDir."/ups_label_$orderIdentif.log";
        $glFile = fopen($gifLogName, "w");
        fwrite($glFile, $logText);
        fclose($glFile);
    }

//===============================================================================================

    //--- check if lable already exists in $labelsDir
    if ($orderId > 0 && file_exists($labelsDir."/ups_label_$orderId.gif"))
    {
        // load existing image
        $gifName = $labelsDir."/ups_label_$orderId.gif";
        $resultImage = file_get_contents($gifName);

//      header("Content-type: " . image_type_to_mime_type(IMAGETYPE_GIF));
//      echo $resultImage;
        header("Content-type: " . image_type_to_mime_type(IMAGETYPE_PNG));
        $img = imagecreatefromstring($resultImage);
        $img = imagerotate($img, -90, 0);
        imagegif ($img);
        exit();
    }

    //--- init
    //    $ch = curl_init();
    construct($tempDir4Cookies);//

    //--- make start page for cookies
    $response = request("http://ups.com/content/us/en/index.jsx", null, "get");
    //--- login
    $post = Array(
            "sret" => "http://ups.com/content/us/en/index.jsx",
            "uret" => "http://ups.com/content/us/en/index.jsx",
            "ctxcc" => "en_US",
            "userid" => $userId,
            "password" => $userPassword);
    foreach ($post as $pk => $pv)
        $postItems[] = "$pk=".urlencode($pv);
    $response = request("https://wwwapps.ups.com/cclamp/login", implode("&", $postItems), "post",$ref = 'http://ups.com/content/us/en/index.jsx');
    if (strpos($response, "Welcome,") === false)
    {
        $logText .= "Login page: CAN'T LOGIN:<br>";
        $occuredError = true;
    }
    else
        $logText .= "Login page: LOGGED IN OK.\n<br>\n";

    if ($occuredError)
        exitFatalError($orderId, $logText);

    //--- Create shippment
    $response = request("https://www.ups.com/uis/create", "loc=en_US", "get", "http://ups.com/content/us/en/index.jsx");
    if (strpos($response, "Please enter your shipping information below") === false)
    {
        $logText .= "Create shippment page: CAN'T Create shippment:<br>\n\n$response";
        $occuredError = true;
    }
    else
        $logText .= "Create shippment page: Create shippment OK.\n<br>\n";

    if ($occuredError)
        exitFatalError($orderId, $logText);

    //--- click "Enter New Address"
    $response = request("https://www.ups.com/uis/create", "ActionOriginPair=EditShipToPOPUP___EmptyPage&TC_TIME_STAMP=0&loc=en_US&RedirectHref=POPUP_LEVEL=1&MainFormName=mainPageForm", "get");
    if (strpos($response, "Enter New Address") === false)
    {
        $logText .= "Enter New Address: CAN'T Enter New Address:<br>\n\n$response";
        $occuredError = true;
    }
    else
        $logText .= "Enter New Address: Enter New Address OK.\n<br>\n";

    if ($occuredError)
        exitFatalError($orderId, $logText);

    //--- Fill and click OK on "Enter New Address"
    unset($post);
    unset($postItems);
    $post = Array(
        "Other" => "",
        "Company" => $Company_to,
        "RedirectHref" => "",
        "loc" => "en_US",
        "ActionOriginPair" => "SUBMITSPOPUP___EditShipTo",
        "editAllowed" => "true",
        "OriginalCountry" => "",
        "ContactName" => "",
        "PostalCode" => $PostalCode_to,
        "City" => $City_to,
        "OriginalPostalCode" => "",
        "personalAddressBookName" => "",
        "Phone" => "",
        "State" => $State_to,
        "Address3" => "",
        "POPUP_LEVEL" => "1",
        "PersonalAddrBookOpt" => "NONE",
        "LocationID" => "",
        "Address2" => "",
        "Email" => "",
        "Address1" => $Address1_to,
        "Country" => $Country_to,
        "TC_TIME_STAMP" => "1150794427016"
        );
    foreach ($post as $pk => $pv)
        $postItems[] = "$pk=".urlencode($pv);

    $response = request("https://www.ups.com/uis/create", implode("&", $postItems), "post");
    if (strpos($response, "self.opener.location = \"create?ActionOriginPair=default___StartSession&loc=en_US\";") === false)
    {
        $logText .= "Enter New Address: CAN'T close window:<br>\n\n$response";
        $occuredError = true;
    }
    else
        $logText .= "Enter New Address: Create New Address and close window OK.\n<br>\n";

    if ($occuredError)
        exitFatalError($orderId, $logText);

    //--- Fill and click OK on "Create shippment"
    unset($post);
    unset($postItems);
    $post = Array(
        "FORM_MODIFIED" => "1",
        "addressID" => "",
        "Email" => "",
        "package1DeclaredValue" => $package1DeclaredValue,
        "Ext" => "",
        "ContactName" => "",
        "Reference1_1" => "",
        "selectedService" => $selectedService,
        "selectedPackaging" => $selectedPackaging,
        "Fax" => "",
        "RedirectHref" => "",
        "selectedNumberofPackages" => $selectedNumberofPackages,
        "TC_TIME_STAMP" => "1150794497453",
        "shipperContactName" => $shipperContactName,
        "initialDisplay" => "false",
        "Reference1_2" => "",
        "PostalCode" => "",
        "package1Oversize" => $package1Oversize,
        "package1Weight" => $package1Weight,
        "ActionOriginPair" => "ShipNow___ShipAPackage",
        "package1Height" => "",
        "package1Length" => "",
        "Phone" => "",
        "Other" => "",
        "City" => "",
        "package1Width" => "",
        "Country" => "",
        "loc" => "en_US",
        "State" => "",
        "Address3" => "",
        "Address2" => "",
        "Address1" => "",
        "Company" => "",
        "payerOfTransportation" => 10
        );
    foreach ($post as $pk => $pv)
        $postItems[] = "$pk=".urlencode($pv);
    $response = request("https://www.ups.com/uis/create", implode("&", $postItems), "post");
    if (strpos($response, "Print Label(s) and Receipt") === false)
    {
        $logText .= "Create shippment page: CAN'T Create shippment and close window:<br>\n\n$response";
        $occuredError = true;
    }
    else
        $logText .= "Create shippment page: Create shippment and close window OK.\n<br>\n";

    if ($occuredError)
        exitFatalError($orderId, $logText);

    //--- parse responce and grab image url
    $func_print_pos = strpos($response, "function viewPrint(){");
    if ($func_print_pos !== false)
    {
        $var_saUrl = strpos($response, "var saURL = origSaURL + \"", $func_print_pos);
        if ($var_saUrl !== false)
        {
            preg_match("/var\s+saURL\s+=\s*origSaURL\s*\+\s*\"\?([^\"]+)\"/i", $response, $res_s1, PREG_OFFSET_CAPTURE, $var_saUrl);
            if (count($res_s1) > 0)
            {
                $imageUrlParams = $res_s1[1][0]."0&loc=en_US&labelMask=0-&labelType=GIF&totalReceipts=0&receiptType=GIF&sampleLabel=false&parent=true&1150895430772";
            }
        }
    }

    if ($imageUrlParams == null)
    {
        //--- warning: make hack - use dummy url parameters
        $imageUrlParams = "loc=en_US&labelMask=0-&labelType=GIF&totalReceipts=0&receiptType=GIF&sampleLabel=false&parent=true&1150895430772";
        $logText .= "Picture URL: !Was used hack for imageUrlParams:<br>\n\n$response";
    }

    if ($imageUrlParams != null)
    {
        //--- click "Print View"
        $response = request("https://www.ups.com/uis/Label", $imageUrlParams, "get", null);
        if (strpos($response, "GIF") === 0)
        {
            if ($orderId > 0)
            {
                //store image in file
                $gifName = $labelsDir."/ups_label_$orderId.gif";
                $gFile = fopen($gifName, "w");
                fwrite($gFile, $response);
                fclose($gFile);
            }

//          header("Content-type: " . image_type_to_mime_type(IMAGETYPE_GIF));
//          echo $response;
            header("Content-type: " . image_type_to_mime_type(IMAGETYPE_PNG));
            $img = imagecreatefromstring($response);
            $img = imagerotate($img, -90, 0);
            imagepng ($img);
        }
        else
        {
            $logText .= "Receive picture: it's not gif:<br>\n\n$response";
            $occuredError = true;
        }
    }
    else
        $occuredError = true;

    if ($occuredError)
        exitFatalError($orderId, $logText);

    if ($orderId > 0)
    {
        //--- Click "View History or Void Shipment"
        unset($post);
        unset($postItems);
        $post = Array(
            "Label#1" => "label",
            "loc" => "en_US",
            "RedirectHref" => "",
            "isFirstDisplay2" => "1",
            "ActionOriginPair" => "ShippingHistory___StartSession",
            "TC_TIME_STAMP" => "1151406129288",
            "isFirstDisplay" => "0"
            );
        foreach ($post as $pk => $pv)
            $postItems[] = "$pk=".urlencode($pv);

        $response = request("https://www.ups.com/uis/create", implode("&", $postItems), "post");
        if (strpos($response, "Please select an individual shipment using the checkboxes.") === false)
        {
            $logText .= "Shipment Number page: CAN'T get Shipment Tracking number:<br>\n\n$response";
        }
        else
            $logText .= "Shipment Number page: Shipment Tracking number OK.\n<br>\n";

        //--- parse responce and grab first Shipment Tracking #
        preg_match("/<input[^>]+name=\"selectedShipments\"/i", $response, $res_s3);
        if (count($res_s3) > 0)
        {
            $name_sel_shipment_input = strpos($response, $res_s3[0]);
            if ($name_sel_shipment_input !== false)
            {
                preg_match("/value=\"?([\w\d]+)\"?/i", $response, $value_sel_shipment, PREG_OFFSET_CAPTURE, $name_sel_shipment_input);
                if (count($value_sel_shipment) > 1)
                {
                    $shipmentNumber = $value_sel_shipment[1][0];
                    $shipmentAttrs = findShipmentAttrs(substr($response, $name_sel_shipment_input));
                    if (count($shipmentAttrs) > 0)
                    {
                        foreach ($shipmentAttrs as $pk => $pv)
                            $urlSAItems[] = "$pk=".urlencode($pv);
                        $shipmentAttrsUrl = "&".implode("&", $urlSAItems);
                    }
                }
            }
        }

        if (isset($shipmentNumber))
        {
            $responseNum = request($shipNumberSendUrl, $shipNumberSendUrlParams."&OrderId=".urlencode($orderId)."&ShipmentNumber=".urlencode($shipmentNumber).$shipmentAttrsUrl, "get");
        }
        else
        {
            $logText .= "Shipment Number page: CAN'T find Shipment Tracking number in:<br>\n\n$response";
            $responseNum = request($shipNumberSendUrl, $shipNumberSendUrlParams."&Error=".urlencode($logText), "get");
        }

        if ($responseNum == null)
            $logText .= "Shipment Number was not sent. Response is null.<br>\n\n";
    }
    else
    {
        $logText .= "Shipment Number page: Skipped (orderId='$orderId')<br>\n\n";
    }

    //--- logout
    //echo "\n<b><br>\nhttp://www.ups.com/servlet/logout\n</b><br>\n";
    $response = request("http://www.ups.com/servlet/logout", null, "get");
    //echo $response;

    //--- finalize
    finalize();

    store_log($orderId, $logText);
?>