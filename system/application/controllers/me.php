<?php

class Me extends Controller {
	
	function __construct() {
		parent::Controller ();
		require_once (APPPATH . 'controllers/default_constructor.php');
	}
	
	function index() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		
		$this->load->vars ( $this->template );
		$layout = CI::view ( 'layout', true, true );
		$primarycontent = CI::view ( 'me/index', true, true );
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		CI::library('output')->set_output ( $layout );
	}
	
	function login() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		
		$this->load->vars ( $this->template );
		$layout = CI::view ( 'layout', true, true );
		$primarycontent = CI::view ( 'me/index', true, true );
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		CI::library('output')->set_output ( $layout );
	}
	
	function register() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		$rules ['username'] = "trim|required|xss_clean|callback_username_check";
		$rules ['password'] = "trim|required|matches[passconf]";
		$rules ['passconf'] = "trim|required";
		$rules ['email'] = "trim|required|valid_email|xss_clean|callback_useremail_check";
		$rules ['accept'] = "callback_acceptterms_check|required|xss_clean";
		$rules ['captcha'] = "callback_captcha_check|required|xss_clean";
		
		$this->validation->set_rules ( $rules );
		
		$fields ['username'] = 'Username';
		$fields ['password'] = 'Password';
		$fields ['passconf'] = 'Password Confirmation';
		$fields ['email'] = 'Email Address';
		//$fields ['accept'] = 'Please accept the Terms and Conditions';
		

		$this->validation->set_fields ( $fields );
		
		$this->validation->set_error_delimiters ( '<div class="error">', '</div>' );
		
		if ($this->validation->run () == FALSE) {
			$primarycontent = CI::view ( 'me/register', true, true );
		} else {
			$register = array ( );
			$register ['username'] = $this->input->post ( 'username' );
			$register ['password'] = $this->input->post ( 'password' );
			$register ['email'] = $this->input->post ( 'email' );
			$register ['is_active'] = 1;
			CI::model('users')->saveUser ( $register );
			
			$primarycontent = CI::view ( 'me/register_done', true, true );
		}
		
		$this->load->vars ( $this->template );
		$layout = CI::view ( 'layout', true, true );
		
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		CI::library('output')->set_output ( $layout );
	}
	
	function username_check($username) {
		$check = CI::model('users')->doUserExist ( 'username', $username );
		if ($check == true) {
			$this->validation->set_message ( 'username_check', "The username $username already exists!" );
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	function useremail_check($username) {
		$check = CI::model('users')->doUserExist ( 'email', $username );
		if ($check == true) {
			$this->validation->set_message ( 'useremail_check', "The email $username is already assigned to another user! Please choose new one!" );
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	function acceptterms_check($str = false) {
		//	var_dump($str);
		//exit;
		if (intval ( $str ) == 1) {
			return true;
		} else {
			$this->validation->set_message ( 'acceptterms_check', "Please accept the Terms and Conditions" );
			return false;
		}
	}
	
	function captcha() {
		if (session_id () == false) {
			session_start ();
		}
		$md5 = md5 ( microtime () * mktime () );
		$string = substr ( $md5, 0, 5 );
		//print BASEPATHSTATIC."captcha.png";
		//exit;
		$captcha = imagecreatefromjpeg ( BASEPATHSTATIC . "captcha.jpg" );
		$black = imagecolorallocate ( $captcha, 0, 0, 0 );
		$line = imagecolorallocate ( $captcha, 233, 239, 239 );
		imageline ( $captcha, 0, 0, 39, 29, $line );
		imageline ( $captcha, 40, 0, 64, 29, $line );
		imagestring ( $captcha, 5, 20, 10, $string, $black );
		$_SESSION ['captcha'] = md5 ( $string );
		header ( "Content-type: image/jpg" );
		imagepng ( $captcha );
	
	}
	function captcha_check($str) {
		if (($str) == $_SESSION ['captcha']) {
		
		}
		return false;
	}

}

?>