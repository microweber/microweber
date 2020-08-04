<?php
/**
 * PHP Mail Provider
 * @author Bozhidar Slaveykov (selfworksbg@gmail.com)
 * @namespace Newsletter\Providers
 * @package PHPMailProvider
 */

namespace Newsletter\Providers;

use Microweber\App\Providers\Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use MicroweberPackages\Utils\Mail\MailSender;

class PHPMailProvider extends \Newsletter\Providers\DefaultProvider {
	
	public function send() {
		
		$sender = new MailSender();
		$sender->transport = 'php';
		
		$status = $sender->exec_send(
			$this->toEmail, $this->subject,
			$this->body,
			$this->fromEmail, $this->fromName, $this->fromReplyEmail
		);
		
		if ($status) {
			return 'Email is sent successfuly.';
		} else {
			return 'Email is not sent';
		}
	}
	
}