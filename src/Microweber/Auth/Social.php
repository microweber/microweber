<?php
namespace Microweber\Auth;
use Hybrid_Auth;
use Hybrid_Endpoint;
use Hybrid_Error;
class Social {
	public $config = false;
	private $here = false;

	public $api = false;
	function __construct() {
		$this->here = dirname(__FILE__);

		$class = $this->here . DS . 'hybridauth' . DS . 'Hybrid/Auth.php';

		//$this->config = $config;
		require_once ($class);
		$class = $this->here . DS . 'hybridauth' . DS . 'Hybrid/Error.php';
		require_once ($class);
		$this->api = new Hybrid_Auth();

	}
	public function is_error() {
		
		return Hybrid_Auth::storage()->get( "hauth_session.error.status" );;
	}



	public function isUserConnected() {
		return $this->api -> isUserConnected();
	}


    function object_2_array($result)
    {
        $array = array();
        foreach ($result as $key => $value) {
            if (is_object($value)) {
                $array[$key] = $this->object_2_array($value);
            }
            if (is_array($value)) {
                $array[$key] =  $this->object_2_array($value);
            } else {
                $array[$key] = $value;
            }
        }
        return $array;
    }


	function process() {
		$class = $this->here . DS . 'hybridauth' . DS . 'Hybrid/Auth.php';
		require_once ($class);
		$class = $this->here . DS . 'hybridauth' . DS . 'Hybrid/Endpoint.php';
		require_once ($class);
		try {
			
			
			$res = Hybrid_Endpoint::process();
			
			 
			 
 		} catch( Exception $e ) {
			die("<b>got an error!</b> " . $e -> getMessage());
		}
		// $err = Hybrid_Auth::isUserConnected();
		// var_dump($res);
		//exit();

	}

	function authenticate($provider) {

		try {

			$adapter = $this->api -> authenticate($provider);

			$user_profile = $adapter -> getUserProfile();

			if (!empty($user_profile)) {
				$user_profile = $this->object_2_array($user_profile);
				return $user_profile;
			}
		} catch( Exception $e ) {
			die("<b>got an error!</b> " . $e -> getMessage());
		}

	}

}
