<?php
// Secure encryption/decryption, verification, and storage for cookies
class Email {
	public function send() {

		$pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i";
		if (preg_match($pattern, trim(strip_tags($_POST['req-email'])))) {
			$cleanedFrom = trim(strip_tags($_POST['req-email']));
		} else {
			return "The email address you entered was invalid. Please try again!";
		}

		//   CHANGE THE BELOW VARIABLES TO YOUR NEEDS

		$to = 'boksira@gmail.com';

		$subject = 'Website Change Reqest';

		$headers = "From: " . $cleanedFrom . "\r\n";
		$headers .= "Reply-To: " . strip_tags($_POST['req-email']) . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

		if (mail($to, $subject, $message, $headers)) {
			echo 'Your message has been sent.';
		} else {
			echo 'There was a problem sending the email.';
		}

	}

}

 