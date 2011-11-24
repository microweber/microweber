<?php

class webdav extends Controller {
	
	function __construct() {
		parent::Controller ();
		require_once (APPPATH . 'controllers/default_constructor.php');
	}
	
	function index() {
		exit('webdav is not yet finished');
		ob_clean ();
		$this->load->helper ( 'url' );
		$publicDir = USERFILES . 'media/content';
		$tmpDir = CACHEDIR . 'webdav';
		$tmpDir = normalize_path ( $tmpDir );
		if (is_dir ( $tmpDir ) == false) {
			mkdir ( $tmpDir );
		}
		
		$publicDir = normalize_path ( $publicDir );
		if (is_dir ( $publicDir ) == false) {
			mkdir ( $publicDir );
		}
		
		// If you want to run the SabreDAV server in a custom location (using mod_rewrite for instance)
		// You can override the baseUri here.
		

		require_once BASEPATH . 'libraries/webshare/lib/Sabre.autoload.php';
		
		$u = 'admin';
		$p = '1234';
		
		//$auth = new Sabre_HTTP_DigestAuth ();
		//$auth->init ();
		$is_valid = false;
		//$username_test = $auth->getUsername ();
		//$pass_test = $auth->validatePassword ();
		//$auth2 = new Sabre_HTTP_BasicAuth ();
		//$passresult = $auth2->getUserPass ();
		
 
		
		//p($passresult);
		/*if (! $username_test) {
			$is_valid = false;
		} else {
			
			$data = array ();
			$data ['username'] = $username_test;
			//$data ['password'] = $pass_test;
			$data ['is_active'] = 'y';
			$data ['is_admin'] = 'y';
			
			$data = CI::model ( 'users' )->getUsers ( $data );
			$data = $data [0];
			//
			if (! empty ( $data )) {
				//p($data);
				$pass_test = $auth->validatePassword ( $data ['password'] );
				//p($pass_test);
				if ($pass_test == true) {
					$is_valid = true;
				} else {
					$is_valid = false;
				}
			} else {
				$is_valid = false;
			}
			//p($data);
		

		}
		
		if ($is_valid == false) {
			
			//$auth->requireLogin ();
			//echo "Authentication required\n";
			//die ();
		
		}*/
		
		// Create the root node
		$root = new Sabre_DAV_FS_Directory ( $publicDir );
		//..p($_SERVER);
		// The rootnode needs in turn to be passed to the server class
		$server = new Sabre_DAV_Server ( $root );
		//$baseUri = '/cms/webdav/';
		

		$temp1 = site_url ( 'webdav' );
		$temp1 = parse_url ( $temp1 );
		//p($temp1);
		

		$baseUri = $temp1 ['path'];
		if (isset ( $baseUri )) {
			$server->setBaseUri ( $baseUri );
		}
		// Support for LOCK and UNLOCK 
		$lockBackend = new Sabre_DAV_Locks_Backend_FS ( $tmpDir );
		$lockPlugin = new Sabre_DAV_Locks_Plugin ( $lockBackend );
		$server->addPlugin ( $lockPlugin );

		$server->addPlugin(new Sabre_DAV_Mount_Plugin());
		// Support for html frontend
		$browser = new Sabre_DAV_Browser_Plugin ();
		$server->addPlugin ( $browser );
		
		// Temporary file filter
		$tempFF = new Sabre_DAV_TemporaryFileFilterPlugin ( $tmpDir );
		$server->addPlugin ( $tempFF );
		
		// And off we go!
		$server->exec ();
		dav;
	}

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */