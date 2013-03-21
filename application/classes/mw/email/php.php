<?
namespace mw\email;

class Php implements \mw\email\iMwEmail {

	private $email_from = false;
	private $email_from_name = false;
	private $here = false;
	function __construct() {

		$email_from = get_option('email_from_name', 'email');
		if ($email_from == false or trim($email_from) == '') {
			$email_from = getenv("USERNAME");
		}
		$this -> email_from_name = $email_from;

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
		$s = $m -> send();

		 
		return $s;

	}

}
