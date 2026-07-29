<?php
/**
 * PHP Mail Provider
 * @author Bozhidar Slaveykov (selfworksbg@gmail.com)
 * @namespace Newsletter\Providers
 * @package PHPMailProvider
 */

namespace Modules\Newsletter\EmailProviders;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use MicroweberPackages\MailSender\Services\MailSenderService;

class PHPMailProvider extends DefaultProvider {

	public function send() {

		$sender = app(MailSenderService::class);

		$status = $sender->execSend(
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
