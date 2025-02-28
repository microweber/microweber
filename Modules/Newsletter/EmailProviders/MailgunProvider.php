<?php
/**
 * Mailgun Provider
 * @author Bozhidar Slaveykov (selfworksbg@gmail.com)
 * @namespace Newsletter\Providers
 * @package MailgunProvider
 */

namespace Modules\Newsletter\EmailProviders;

use Illuminate\Support\Facades\Config;
class MailgunProvider extends DefaultProvider {

	public function send() {

		Config::set('mail.driver', 'mailgun');
		Config::set('services.mailgun.secret', $this->getSecret());
		Config::set('services.mailgun.domain', $this->getDomain());

		$this->sendToEmail();

		//var_dump(Config::get('services'));
		//die();
	}

}
