<?php

$this->template ['error'] = false;
$this->template ['ok'] = false;
$opt = array ();
if ($_POST) {
	$email = trim ( addslashes ( $_POST ['email'] ) );
	$q = "SELECT * FROM $table WHERE email='$email'";
	$res = CI::model('core')->dbQuery ( $q );
	if (empty ( $res )) {
		$this->template ['error'] = "Not found email: '$email'";
	} else {
		$this->template ['ok'] = "Password was send to email '$email'";

	/*	$opt ['email'] = $email;
		$opt ['username'] = $res [0] ['username'];
		$opt ['password'] = $res [0] ['password'];
		$opt ['object'] = 'Forgotten Password';
		$opt ['site'] = site_url ();
		;
*/
		$from = CI::model('core')->optionsGetByKey ( 'forgot_pass_email_from', true );
		$message = "Hello, here are your login details \n\n" .
		"site: ".site_url ()." \n" .
		"username: {$res [0] ['username']} \n" . "password: {$res [0] ['password']} \n" ;



		$sendOptions = array();
		$sendOptions ['from_email'] = $from ['option_value'];
		$sendOptions ['from_name'] = $from ['option_value2'];
		$sendOptions ['to_email'] =  $res [0] ['email'];
		$sendOptions ['subject'] =  'Password reminder';
		$sendOptions ['message'] =  $message;




		CI::model('core')->sendMail2 ( $sendOptions );


		//CI::model('users')->sendMail ( $opt );
	}
}
$content ['content_filename'] = 'users/forgotten_pass.php';

