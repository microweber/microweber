<?php
/**
 * Mailgun Provider
 * @author Bozhidar Slaveykov (selfworksbg@gmail.com)
 * @namespace Newsletter\Providers
 * @package MailgunProvider
 */

namespace MicroweberPackages\Modules\Newsletter\EmailProviders;

use Config;

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
