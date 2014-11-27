<?php

include(__DIR__.DS.'vendor/webmail-linker-master/src/php/WebmailLinker.php');

event_bind('user_logout', function () {
    if(isset($_COOKIE['mw_remote_hash'])){
        setcookie("mw_remote_hash", "", time()-10, "/", ".microweber.com");
    }
});



event_bind('user_login', function ($data) {
	//var_dump($_SERVER);
  //if ($_SERVER['REMOTE_ADDR'] == '78.90.67.20') {
	 
	 if(isset($data['email'])){
		 
		$ml =  $data['email'];
		$url = 'https://members.microweber.com/_sync/validate_email/?is_valid='.$ml;

 
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $data = curl_exec($ch);

    curl_close($ch);
		 $data = @json_decode($data, true);
	 if(isset($data['client_id']) and isset($data['is_valid'])){
		  if(isset($data['email'])){
            setcookie("mw_is_veririfed_valid_email", $data['is_valid'], false, "/", ".microweber.com");
		  }
		 
	 }
	 }
	 
  //}
});

api_expose('mw_resend_cofirmation_email');
function mw_resend_cofirmation_email()
{
	$user = get_user();
	
	if($user == false){
		
	return;	
	}
	$ml =  $user['email'];
	
	
	$url = 'https://members.microweber.com/_sync/validate_email/?resend_validation='.$ml;

 
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $data = curl_exec($ch);
 
    curl_close($ch);
	
	
	
	
}

event_bind('mw_frontend', function () {
    if (!isset($_SESSION['whm_user_id']) or $_SESSION['whm_user_id'] == false) {
        if (isset($_COOKIE['mw_remote_hash']) and $_COOKIE['mw_remote_hash'] != false) {


            if (!session_id()) {
                session_start();
            }
            $found_user = false;
            if (isset($_COOKIE['mw_remote_hash'])) {
                $enc = $_SERVER['REMOTE_ADDR'];
                if (isset($_SERVER['HTTP_USER_AGENT'])) {
                    $enc .= $_SERVER['HTTP_USER_AGENT'];
                }
                if (isset($_SERVER['HTTP_USER_AGENT'])) {
                    $enc .= $_SERVER['HTTP_USER_AGENT'];
                }
                if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                    $enc .= $_SERVER['HTTP_ACCEPT_LANGUAGE'];
                }

                $mw_remote_hash = $_COOKIE['mw_remote_hash'];

                if (class_exists('Memcache', false)) {
                    $meminstance = new Memcache();
                    $isMemcacheAvailable = @$meminstance->connect('198.199.81.68', 11211);

                    if ($isMemcacheAvailable) {
                        $v = $meminstance->get($mw_remote_hash);
//                        if (isset($_SERVER['REMOTE_ADDR']) and $_SERVER['REMOTE_ADDR'] == '78.90.67.20') {
//                            $d = $meminstance->getextendedstats();
//                            d($mw_remote_hash);
//                            d($d);
//                            d($v);
//                            exit;
//                        }
                        if (is_array($v) and !empty($v)) {
                            if (isset($v['HTTP_ACCEPT_LANGUAGE']) and $v['HTTP_ACCEPT_LANGUAGE'] == $_SERVER['HTTP_ACCEPT_LANGUAGE']) {
                                if (isset($v['REMOTE_ADDR']) and $v['REMOTE_ADDR'] == $_SERVER['REMOTE_ADDR']) {
                                    if (isset($v['HTTP_USER_AGENT']) and $v['HTTP_USER_AGENT'] == $_SERVER['HTTP_USER_AGENT']) {
                                        if (isset($v['userid'])) {
                                            $found_user = $v['userid'];
                                        }
                                    }
                                }
                            }
                        }

                    }
                }
            }
            if ($found_user == false) {
                return;
            }
            $is_user = get_user();
            $whm_user = $found_user;

            if ($is_user == false or ($is_user['oauth_uid']) != $whm_user) {
                $get_local = array();
                $get_local['oauth_uid'] = $whm_user;
                $get_local['oauth_provider'] = 'whmcs';
                $get_local['one'] = true;
                $s = get_users($get_local);
                if (is_array($s) and !empty($s)) {
                    $login = mw()->user_manager->make_logged($s);
                    $_SESSION['whm_user_id'] = $whm_user;
                    mw()->user_manager->session_set('whm_user_id',$whm_user);

                }
            }
        }
    }

});


api_expose('back_to_site');
function back_to_site($params)
{
    if (!is_array($params)) {
        $params = parse_params($params);
    }
    if (isset($params['m']) and isset($params['h'])) {
        $uid = $params['m'];
        $hash = trim($params['h']);


        if ($uid != 0) {
            $postfields = array();
            $postfields["action"] = "get_client_info";
            $postfields["clientid"] = $uid;
            $postfields["cache"] = 10;


            $result = whm_exec_mw_api($postfields);

            if (isset($result[0]) and is_array($result[0])) {
                $user_data = $result[0];
                if (is_array($user_data) and isset($user_data['id']) and $user_data['email']) {
                    $check = whm_mw_obscure($user_data['email']);
                    if ($check != $hash) {
                        if (isset($params['goto'])) {
                            $goto = site_url($params['goto']);
                        } else {
                            $goto = site_url('login');
                        }


                        mw()->url->redirect($goto);
                        return;
                    } else {
                        $check_if_exists = get_users('one=1&email=' . $user_data['email']);

                        $upd = array();
                        if ($check_if_exists == false) {

                        } else {
                            $upd['id'] = $check_if_exists['id'];


                        }
                        if (is_array($check_if_exists) and isset($check_if_exists['is_active'])) {
                            $upd['is_active'] = $check_if_exists['is_active'];
                        } else {
                            $upd['is_active'] = 'y';
                        }


                        $upd['email'] = $user_data['email'];
                        mw_var('force_save_user', 1);
                        mw_var('save_user_no_pass_hash', 1);
                        $table = MW_DB_TABLE_USERS;
                        mw_var('FORCE_SAVE', $table);

                        $upd['oauth_uid'] = $user_data['id'];
                        $upd['oauth_provider'] = 'whmcs';
                        $s = save_user($upd);

                        if (intval($s) > 0) {
                            $login = mw()->user_manager->make_logged($s);
                            if (isset($login['success']) or isset($login['error'])) {
                                mw()->user_manager->session_set('whm_user_id', $user_data['id']);
                                if (isset($params['goto'])) {
                                    $goto = site_url($params['goto']);
                                } else {
                                    $goto = site_url('login');
                                }

                               mw()->url->redirect($goto);
                                return;
                            }
                        }


                    }
                }
            }


        }


    }


    if (isset($params['goto'])) {
        $goto = site_url($params['goto']);
        mw()->url->redirect($goto);
        return;
    }


    //d($params);
}

function whm_mw_obscure($username, $time = 'now')
{

    $ipaddress = $_SERVER['REMOTE_ADDR'];
    $temp = date("Ymd", strtotime($time));


    return md5(md5($username . ':' . $temp . '@' . $ipaddress) . $_SERVER['HTTP_USER_AGENT']);
}

event_bind('site_header', 'whm_site_header');
function whm_site_header()
{
    $mod_url = module_url('whmcs');

    // d($mod_url);
    template_head(site_url('userfiles/modules/whmcs/whm.js'));
}


api_expose('whm_domainwhois');
function is_fqdn($FQDN)
{

    return (!empty($FQDN) && preg_match('/(?=^.{1,254}$)(^(?:(?!\d+\.)[a-zA-Z0-9_\-]{1,63}\.?)+(?:[a-zA-Z]{2,})$)/i', $FQDN) > 0);


    return (!empty($FQDN) && preg_match('/(?=^.{1,254}$)(^(?:(?!\d|-)[a-z0-9\-]{1,63}(?<!-)\.)+(?:[a-z]{2,})$)/i', $FQDN) > 0);
}

function whm_domainwhois($params)
{

    if (!is_array($params)) {
        $params = parse_params($params);
    }
    $domain = false;
    if (isset($params['domain'])) {
        $domain = $params['domain'];
    }

    if (isset($params['tld'])) {

        $domain = $domain . '.' . $params['tld'];
    }
    if ($domain != '') {
        $domain = str_ireplace('www.', '', $domain);
    }
    if (is_fqdn($domain)) {

        $exp = explode('.', $domain);

        if (count($exp) > 2 and !stristr($domain, 'microweber')) {
            return array('error' => 'You have entered a sub-domain. Please enter a valid domain name.');
        } else {
            $testd = substr($domain, 0, 4);
            if (count($exp) > 2 and stristr($testd, 'test')) {
                return array('error' => 'Test is a reserved keyword');
            }
        }


        if (stristr($domain, 'microweber')) {
            $postfields = array();
            $postfields["action"] = "check_if_domain_taken";
            $postfields["domain"] = $domain;


            $res = whm_exec_mw_api($postfields);
            $results = $res;
            return $results;

        }

        $postfields = array();
        $postfields["action"] = "domainwhois";
        $postfields["domain"] = $domain;

        $res = whm_exec($postfields);
        $results = $res;
        return $results;
    }


}

function whm_get_user_products($product_id = false)
{

    $remote_client_id = mw()->user_manager->session_get('whm_user_id');

    if ($remote_client_id == false) {
        return $remote_client_id;
    }


    $remote_client_id = intval($remote_client_id);
    if ($remote_client_id > 0) {

        $postfields = array();
        $postfields["action"] = "get_client_products";
        $postfields["clientid"] = $remote_client_id;
        $postfields["cache"] = 5;


        $result = whm_exec_mw_api($postfields);

        if (isset($result['products']) and isset($result['products']["product"])) {

            if ($product_id != false) {
                foreach ($result['products']["product"] as $prod) {
                    if (isset($prod['id']) and intval($prod['id']) == intval($product_id)) {
                        return $prod;
                    }
                }
            }


            return array_reverse($result['products']["product"]);
        } else {
            return false;
        }
        return $result;
    }


}


api_expose('whm_upgrade_user_product');
function whm_upgrade_user_product($params)
{
    $params = parse_params($params);
    $remote_client_id = mw()->user_manager->session_get('whm_user_id');

    if ($remote_client_id == false) {
        return $remote_client_id;
    }


    if (!isset($params['product_id'])) {
        return false;
    }

    $remote_client_id = intval($remote_client_id);
    if ($remote_client_id > 0) {

        $postfields = array();
        $postfields["clientid"] = $remote_client_id;
        $postfields["action"] = "upgrade_product";
        $postfields["pid"] = $params['service_id'];
        $postfields["newproductid"] = $params['product_id'];
        if (isset($params['confirm_upgrade'])) {
            $postfields["confirm_upgrade"] = $params['confirm_upgrade'];
        }


        $result = whm_exec_mw_api($postfields);


        return $result;
    }


}


api_expose('whm_get_user_info');
function whm_get_user_info()
{
    static $client_data;
    if ($client_data != null) {
        return $client_data;
    }


    global $_mw_whmcs_credentials;


    if (empty($_mw_whmcs_credentials)) {
        $cfg_file = dirname(__FILE__) . DS . 'server_credentials.php';

        if (file_exists($cfg_file)) {
            include_once($cfg_file);
            if (isset($whm_config) and is_array($whm_config)) {
                $_mw_whmcs_credentials = $whm_config;
            }
        }

    }
    $whm_config = $_mw_whmcs_credentials;
    if (!isset($whm_config['url'])) {
        return false;
    }
    if (!isset($whm_config['autoauthkey'])) {
        return false;
    }
    if (!isset($whm_config['autoauthkey_login_url'])) {
        return false;
    }


    $remote_client_id = mw()->user_manager->session_get('whm_user_id');

    if ($remote_client_id == false) {
        return $remote_client_id;
    }


    $remote_client_id = intval($remote_client_id);
    if ($remote_client_id > 0) {

        $postfields = array();
        $postfields["action"] = "get_client_info";
        $postfields["clientid"] = $remote_client_id;
        $postfields["cache"] = 10;


        $result = whm_exec_mw_api($postfields);
        $whmcsurl = $whm_config['autoauthkey_login_url'];
        $autoauthkey = $whm_config['autoauthkey'];

        $return = array();
        if (is_array($result)) {
            foreach ($result as $item) {
                if (isset($item['email'])) {
                    $timestamp = time(); # Get current timestamp
                    $email = $item['email']; # Clients Email Address to Login
                    //  $goto = "clientarea.php?action=products";
                    // $email = str_replace('+', '%20', $email);


                    $email = str_replace('+', '%2B', $email);

                    $hash = sha1($email . $timestamp . $autoauthkey); # Generate Hash

                    # Generate AutoAuth URL & Redirect
                    // $url = $whmcsurl . "?email=$email&timestamp=$timestamp&hash=$hash&goto=" . urlencode($goto);
                    $url = $whmcsurl . "?email=$email&timestamp=$timestamp&hash=$hash";
                    $item['dologin'] = $url;

                    $return = $item;
                    // d($url);
                }
            }
        }

# Define WHMCS URL & AutoAuth Key


        $client_data = $return;
        return $return;
    }


}


api_expose('whm_get_user_last_unpaid_invoice');
function whm_get_user_last_unpaid_invoice()
{
    $inv = whm_get_user_unpaid_invoices();
    if (!empty($inv)) {
        return $inv[0];
    }


}

api_expose('panel_user_link');
function panel_user_link($params)
{
    $params = parse_params($params);
    $user = whm_get_user_info();


    if (isset($params['goto']) and isset($user ['dologin'])) {
        $goto = $params['goto'];
        $goto = $user['dologin'] . '&goto=' . urlencode($goto);

        mw()->url->redirect($goto);

    } elseif (isset($params['goto']) and !isset($user ['dologin'])) {
        $goto = site_url('login');

        mw()->url->redirect($goto);

    }
}

api_expose('whm_get_user_unpaid_invoices');
function whm_get_user_unpaid_invoices()
{
    $inv = whm_get_user_invoices();

    if (!empty($inv)) {
        $res = array();

        $whmuser_info = whm_get_user_info();
        if (isset($whmuser_info['dologin'])) {
            foreach ($inv as $item) {

                if (isset($item['status'])) {
                    $status = strtolower($item['status']);

                    if ($status == 'unpaid') {
                        $goto = "viewinvoice.php?id=" . $item['id'];
                        $goto = $whmuser_info['dologin'] . '&goto=' . urlencode($goto);

                        $item['paylink'] = $goto;


                        $goto = "clientarea.php?action=masspay&all=true";
                        $goto = $whmuser_info['dologin'] . '&goto=' . urlencode($goto);

                        $item['masspaylink'] = $goto;

                        // $item['dologin'] =  $whmuser_info['dologin'];
                        $res[] = $item;
                    }
                }

            }
        }
        if (!empty($res)) {
            return $res;
        }
    }


}

function whm_get_product_invoices($product_id)
{

    $remote_client_id = mw()->user_manager->session_get('whm_user_id');

    if ($remote_client_id == false) {
        return $remote_client_id;
    }


    $remote_client_id = intval($remote_client_id);
    if ($remote_client_id > 0) {

        $postfields = array();
        $postfields["action"] = "get_product_invoices";
        $postfields["clientid"] = $remote_client_id;
        $postfields["product_id"] = intval($product_id);
        $postfields["cache"] = 100;

        $result = whm_exec_mw_api($postfields);


        return $result;
    }


}

function whm_get_user_invoices()
{

    $remote_client_id = mw()->user_manager->session_get('whm_user_id');

    if ($remote_client_id == false) {
        return $remote_client_id;
    }


    $remote_client_id = intval($remote_client_id);
    if ($remote_client_id > 0) {

        $postfields = array();
        $postfields["action"] = "get_client_invoices";
        $postfields["clientid"] = $remote_client_id;
        $postfields["cache"] = 10;


        $result = whm_exec_mw_api($postfields);


        return $result;
    }


}

function whm_get_hosting_products()
{
    $postfields = array();
    $postfields["action"] = "get_hosting_products";
    $postfields["cache"] = 71600;


    $result = whm_exec_mw_api($postfields);


    return $result;

}

function whm_get_products()
{
    $postfields = array();
    $postfields["action"] = "get_user_products";


    $result = whm_exec_mw_api($postfields);


    return $result;

}


event_bind('before_user_register', 'whm_user_register');


api_expose('whm_user_register');
function whm_user_register($params)
{

    $user = array();

    if (!is_array($params)) {
        $params = parse_params($params);
    }
    //  d($params);

    if (isset($params['email'])) {
        $user['email'] = $params['email'];
    }
    if (isset($params['id'])) {
        $user = mw()->user_manager->get_by_id($params['id']);
    }


    if (isset($user['email']) and (filter_var($user['email'], FILTER_VALIDATE_EMAIL))) {
        $postfields = $user;
        if (isset($_POST['password'])) {
            $postfields["password2"] = $_POST['password'];
        }
    }


    $remote_client_id = mw()->user_manager->session_get('whm_user_id');

    if ($remote_client_id == false or intval($remote_client_id) == 0) {
        $postfields["action"] = "sync_user";
        $result = whm_exec_mw_api($postfields);

        if (isset($result["error"])) {

            return false;
        }


        if (isset($postfields["password2"])) {
            $postfields["password"] = $postfields["password2"];
            $log = whm_user_login($postfields);

            if (!isset($_POST['whm_order_domain']) and !isset($log["success"])) {
                //
                return false;
            }

        }


    } else {
        $result = array();
        $result['result'] = 'success';
        $result['success'] = 'success';
        $result['clientid'] = $remote_client_id;
    }


    if (isset($result['result']) and $result['result'] == 'success' and (isset($result['clientid']) or isset($result['userid']))) {


        if (isset($result['clientid'])) {
            $remote_client_id = $result['clientid'];
        } else if (isset($result['userid'])) {
            $remote_client_id = $result['userid'];
        }


        $login_whm_user = false;

        if (isset($remote_client_id)) {
            //	$login_whm_user = whm_user_login($params);

            if (isset($_COOKIE['whmcartinfo'])) {
                unset($_COOKIE['whmcartinfo']);
                setcookie('whmcartinfo', 'members.microweber.com', time() - 3600); // empty value and old timestamp
            }

            mw()->user_manager->session_set('whm_user_id', $remote_client_id);

            if (isset($_POST['whm_order_domain'])) {

                $domain = $_POST['whm_order_domain'];
                if (is_fqdn($domain)) {

                    // add_order
                    $add_order = array();
                    $add_order["action"] = "add_order";
                    $add_order["clientid"] = $remote_client_id;
                    if (isset($params['product_id'])) {
                        $add_order["pid"] = $params['product_id'];
                    }
                    $add_order["clientip"] = MW_USER_IP;
                    $add_order["domain"] = $domain;

                    if (isset($_COOKIE['WHMCSAffiliateID'])) {
                        $add_order["affid"] = $_COOKIE['WHMCSAffiliateID'];
                    }


                    if (MW_USER_IP == '78.90.67.20') {
                        //print_r( $add_order);
                        //exit;
                    }
                    $result = whm_exec_mw_api($add_order);
                    cache_clear('hosting');
                    if (isset($result['result']) and $result['result'] == 'success') {

                        $result['success'] = 'Your website is created!';
                        return $result;
                    }

                }

            } else {

                if (isset($login_whm_user['user_id'])) {
                    return $login_whm_user;
                } elseif (isset($remote_client_id) and intval($remote_client_id) != 0) {
                    $result['success'] = 'You are logged in!';
                }
            }


        }

    } else if (isset($result['result']) and $result['result'] == 'error' and (isset($result['clientid']) or isset($result['userid']))) {

        return array('error' => 'User with this email already exists!');

    }


    return $result;


}


function old_whm_user_register($params)
{
    if (!is_array($params)) {
        $params = parse_params($params);
    }

    if (!isset($params['id'])) {
        return false;
    }


    $user = mw()->user_manager->get_by_id($params['id']);
    if (isset($user['email']) and (filter_var($user['email'], FILTER_VALIDATE_EMAIL))) {
        $postfields = array();
        $postfields['action'] = 'validatelogin';
        $postfields["email"] = $user['email'];
        if (isset($_POST['password'])) {
            //$user['password'] = $_POST['password'];
        }

        $postfields["password"] = $user['password'];


        $result = whm_exec($postfields);

        if (isset($result['result']) and $result['result'] == 'success' and isset($result['passwordhash'])) {

        }
        if (isset($result['error'])) {
            $postfields = array();
            $postfields["action"] = "addclient";
            $postfields["skipvalidation"] = true;
            $postfields["firstname"] = "MW";
            $postfields["lastname"] = "User";

            $postfields["address1"] = "none";
            $postfields["city"] = "none";
            $postfields["state"] = "none";
            $postfields["postcode"] = "none";
            $postfields["country"] = "none";
            $postfields["phonenumber"] = "00123456789";


            $postfields["email"] = $user['email'];

            $postfields["password"] = $user['password'];
            $postfields["customfields"] = base64_encode(serialize(array("1" => "from_site")));
            $postfields["currency"] = "1";
            $result = whm_exec($postfields);
            //d($result);
        } else {


        }


    }

}

event_bind('before_user_login', 'whm_user_login');
function whm_user_login($params = false)
{
    
	
	
	if ($params == false) {
        return;
    }
    if (!is_array($params)) {
        $params = parse_params($params);
    }
    $postfields = array();
    $postfields['action'] = 'validatelogin';
    if (isset($params['email'])) {
        $params['username'] = $params['email'];
    }

    if (!isset($params['username'])) {
        return false;
    }
    if (!isset($params['password'])) {
        return false;
    }
    $postfields["email"] = $params['username'];
    $postfields["password2"] = $params['password'];


    if (isset($postfields['email']) and (!filter_var($postfields['email'], FILTER_VALIDATE_EMAIL))) {
        return false;
    }

    //	d($postfields);

    $result = whm_exec($postfields);

    if (isset($result['result']) and $result['result'] == 'success' and isset($result['passwordhash'])and isset($result['userid'])) {
        $check_if_exists = get_users('one=1&email=' . $params['username']);

        $upd = array();
        if ($check_if_exists == false) {
            // $upd['id'] = 0;
        } else {
            $upd['id'] = $check_if_exists['id'];


        }
        if (is_array($check_if_exists) and isset($check_if_exists['is_active'])) {
            $upd['is_active'] = $check_if_exists['is_active'];
        } else {
            $upd['is_active'] = 'y';
        }


        $upd['email'] = $params['username'];
        // $upd['password'] = $result['passwordhash'];
        mw_var('force_save_user', 1);
        mw_var('save_user_no_pass_hash', 1);
        $table = MW_DB_TABLE_USERS;
        mw_var('FORCE_SAVE', $table);
        $upd['oauth_uid'] = $result['userid'];
        $upd['oauth_provider'] = 'whmcs';


        cache_clear("hosting");
        $s = save_user($upd);
        if (intval($s) > 0) {

            $login = mw()->user_manager->make_logged($s);
            if (isset($login['success']) or isset($login['error'])) {
                mw()->user_manager->session_set('whm_user_id', $result['userid']);
                mw()->user_manager->session_set('uid', $result['userid']);


                return $login;
            }
        }
    } else if (isset($result['error'])) {
        return $result;
    } else {
        // return $params;
    }


}

function whm_exec_mw_api($params)
{
    if (!is_array($params)) {
        $params = parse_params($params);
    }

    global $_mw_whmcs_credentials;


    if (empty($_mw_whmcs_credentials)) {
        $cfg_file = dirname(__FILE__) . DS . 'server_credentials.php';

        if (file_exists($cfg_file)) {
            include_once($cfg_file);
            if (isset($whm_config) and is_array($whm_config)) {
                $_mw_whmcs_credentials = $whm_config;
            }
        }

    }
    $cache_time = false;
    if (isset($params['cache'])) {
        $cache_time = intval($params['cache']);
    }


    if ($cache_time != false) {
        $cache_id = md5(serialize($params));
        $cache_group = 'hosting';
        $expiration_in_seconds = $cache_time;
        $cache = cache_get($cache_id, $cache_group, $expiration_in_seconds);
        if ($cache != false) {
            return $cache;
        }
    }


    $whm_config = $_mw_whmcs_credentials;
    if (!isset($whm_config['url'])) {
        return array('error' => 'URL in server_credentials.php is not set');
    }
    if (!isset($whm_config['username'])) {
        return array('error' => 'username in server_credentials.php is not set');
    }
    if (!isset($whm_config['password'])) {
        return array('error' => 'password in server_credentials.php is not set');
    }


    $url = $whm_config['mw_api_url'];


    $postfields = $params;
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


    $data = curl_exec($ch);


    curl_close($ch);
    $results = array();

    $data = @json_decode($data, true);

    if ($cache_time != false) {
        cache_save($data, $cache_id, $cache_group);
    }

    return $data;

}

$_mw_whmcs_credentials = array();
function whm_exec($params)
{


    if (!is_array($params)) {
        $params = parse_params($params);
    }

    global $_mw_whmcs_credentials;


    if (empty($_mw_whmcs_credentials)) {
        $cfg_file = dirname(__FILE__) . DS . 'server_credentials.php';

        if (file_exists($cfg_file)) {
            include($cfg_file);
            if (isset($whm_config) and is_array($whm_config)) {
                $_mw_whmcs_credentials = $whm_config;
            }
        }

    }
    $whm_config = $_mw_whmcs_credentials;
    if (!isset($whm_config['url'])) {
        return array('error' => 'URL in server_credentials.php is not set');
    }
    if (!isset($whm_config['username'])) {
        return array('error' => 'username in server_credentials.php is not set');
    }
    if (!isset($whm_config['password'])) {
        return array('error' => 'password in server_credentials.php is not set');
    }


    $url = $whm_config['url'];
    $username = $whm_config['username']; # Admin username goes here
    $password = $whm_config['password']; # Admin password goes here
    $postfields = $params;
    $postfields["username"] = $username;
    $postfields["password"] = md5($password);
    // $postfields["responsetype"] = "json";


    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 100);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
    $data = curl_exec($ch);
    curl_close($ch);
    $results = array();
    $data = explode(";", $data);
    foreach ($data as $temp) {
        $temp = explode("=", $temp);
        if (isset($temp[0]) and isset($temp[1])) {
            $results[$temp[0]] = $temp[1];
        }
    }
    // var_dump($results);
    if (isset($results["result"]) and $results["result"] == "success") {
        # Result was OK!

        return $results;

    } else if (isset($results["message"])) {

        return array('error' => $results["message"]);

    } else {
        return $results;
    }


}

function whm_temp_decrypt2($value, $key = '')
{
    $crypttext = base64_decode($value);

    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $crypttext, MCRYPT_MODE_ECB, $iv);
    return trim($decrypttext);
}



