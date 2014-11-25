<?php
$dbg = 0;
event_bind('mw.admin.dashboard.main', 'mw_print_hostind_data');
function mw_print_hostind_data($params =false){

    $is_data = mw()->user_manager->session_get('mw_hosting_data');

    if($is_data and is_array($is_data)){
        print '<module type="users/mw_login/hosting" />';
    }



}

event_bind('on_lffffoad', 'mw_remote_stats');
function mw_remote_stats($params =false)
{   
	template_head('<!-- Piwik -->
<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(["setDocumentTitle", document.domain + "/" + document.title]);
  _paq.push(["setCookieDomain", "*.microweber.com"]);
  _paq.push(["trackPageView"]);
  _paq.push(["enableLinkTracking"]);

  (function() {
    var u=(("https:" == document.location.protocol) ? "https" : "http") + "://api.microweber.com/service/stats/";
    _paq.push(["setTrackerUrl", u+"piwik.php"]);
    _paq.push(["setSiteId", "1"]);
    var pd=document, g=pd.createElement("script"), s=pd.getElementsByTagName("script")[0]; g.type="text/javascript";
    g.defer=true; g.async=true; g.src=u+"piwik.js"; s.parentNode.insertBefore(g,s);
  })();
</script>
<!-- End Piwik Code -->');
	
}
 event_bind('before_user_login', 'mw_remote_user_login');
function mw_remote_user_login($params =false)
{
	if($params == false){
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
	$postfields= $params;
    $postfields["email"] = $params['username'];
    $postfields["password2"] = $params['password'];
 	$postfields["domain"] = site_url();
 
 
    $result = mw_remote_user_login_exec($postfields);

    if(isset($result['hosting_data'])){
        mw()->user_manager->session_set('mw_hosting_data', $result['hosting_data']);
    }
	
	
 
    if (isset($result['result']) and $result['result'] == 'success' and isset($result['userid'])) {
		
		cache_clear('users');
		
        $check_if_exists = get_users('no_cache=1&one=1&email=' . $params['username']);
       
	    $check_if_exists = get_users('no_cache=1&one=1&oauth_provider=mw_login&oauth_uid=' . intval($result['userid']));
	   
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
		 $upd['password'] = $params['password'];
          $upd['is_admin'] = 'y';
		   
        mw_var('force_save_user', 1);
       // mw_var('save_user_no_pass_hash', 1);
        $table = MW_DB_TABLE_USERS;
        mw_var('FORCE_SAVE', $table);
        
		
		//print_r($result);
		
        $upd['oauth_uid'] = $result['userid'];
        $upd['oauth_provider'] = 'mw_login';
		 if ($_SERVER['REMOTE_ADDR'] == '78.90.67.20') {

// $upd['debug'] = 'mw_login';

		}
        $s = save_user($upd);
		 
	  
		
			
        if (intval($s) > 0) {
			$login = mw()->user_manager->make_logged($s);
			if (defined('MW_DBG_NOW')) {
			 $redirect_after = isset($params['redirect']) ? $params['redirect'] : false;
       		$u = is_admin();




		//  mw()->url->redirect($redirect_after);
		//  exit();
			}
			
    
            if (isset($login['success']) or isset($login['error'])) {
               // mw()->user_manager->session_set('mw_remote_user_id', $result['userid']);

                return $login;
            }
        }
        
    } else if (isset($result['error'])) {
         return $result;
    }  


}

function mw_remote_user_login_exec($params)
{
    if (!is_array($params)) {
        $params = parse_params($params);
    }

    global $_mw_whmcs_credentials;

 
    $cache_time = false;
    if (isset($params['cache'])) {
        $cache_time = intval($params['cache']);
    }

  
 

    $url = 'http://members.microweber.com/login_to_my_website.php';
  

    $postfields = $params;
	//  d($postfields);
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
    $data = curl_exec($ch);
	
		if ($_SERVER['REMOTE_ADDR'] == '78.90.67.20') {
//d($postfields);
//d($data);
//d($url);
		}
 
    curl_close($ch);
    $results = array();
 
    $data = @json_decode($data, true);

    if ($cache_time != false) {
       // cache_save($data, $cache_id, $cache_group);
    }

    return $data;

}
 

