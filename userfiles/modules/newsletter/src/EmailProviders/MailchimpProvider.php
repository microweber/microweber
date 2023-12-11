<?php
/**
 * Mailchimp Provider
 * @author Bozhidar Slaveykov (selfworksbg@gmail.com)
 * @namespace Newsletter\Providers
 * @package MailchimpProvider
 */

namespace MicroweberPackages\Modules\Newsletter\EmailProviders;

use Config;

class MailchimpProvider extends DefaultProvider {

	public function send() {

		Config::set('mail.driver', 'mailchimp');
		Config::set('services.mailchimp.secret', $this->getSecret());

		$this->sendToEmail();

		//var_dump(Config::get('services'));
		//die();
	}
}
