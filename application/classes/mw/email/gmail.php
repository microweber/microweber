<?
namespace mw\email;

class Smtp  {

	private $email_from = false;
	private $email_from_name = false;
	private $here = false;
	
	private $smtp_host = false;
	private $smtp_port = false;
	private $smtp_username = false;
	private $smtp_password = false;
	private $smtp_auth = false;
	private $smtp_secure = false;	
		
 	function __construct() {

		$email_from = get_option('email_from_name', 'email');
		if ($email_from == false or trim($email_from) == '') {
			$email_from = getenv("USERNAME");
		}
		$this -> email_from_name = $email_from;
		
				
		$this -> smtp_host = trim(get_option('smtp_host', 'email'));
				$this -> smtp_port = intval(get_option('smtp_port', 'email'));

		$this -> smtp_username = trim(get_option('smtp_username', 'email'));
		$this -> smtp_password = trim(get_option('smtp_password', 'email'));
		$this -> smtp_auth = trim(get_option('smtp_auth', 'email'));
		 
		$sec = get_option('smtp_secure', 'email');	
		 
		$this -> smtp_secure = intval($sec);
		 
		$email_from = get_option('email_from', 'email');
		if ($email_from == false or trim($email_from) == '') {
			if ($this -> email_from_name != '') {
				$email_from = url_title($this -> email_from_name)."@" . site_hostname();

			} else {
				$email_from = "noreply@" . site_hostname();

			}
		}
		$this -> email_from = $email_from;

		$this -> here = dirname(__FILE__);

		$class = $this -> here . DS . 'lib' . DS . 'dSendMail2.php';

		require_once $class;

	}

	public function send($to, $subject, $message) {
 
		$from_address = $this -> email_from;
		$from_name = $this -> email_from_name;

		 $m = new \dSendMail2;
		$m -> setTo($to);
		$m -> setFrom($from_address);
		$m -> setSubject($subject);
		$m -> setMessage($message);
		$m -> headers['Reply-To'] = $from_address;
		 $m->sendThroughSMTP($this -> smtp_host, $this -> smtp_port,$this -> smtp_username, $this -> smtp_password, $this -> smtp_secure);
		 
		 
		 $m->debug = true;

		$s = $m -> send();
 
		 
		return true;

	}

}
