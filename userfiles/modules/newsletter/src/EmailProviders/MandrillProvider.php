<?php
/**
 * Mandrill Provider
 * @author Bozhidar Slaveykov (selfworksbg@gmail.com)
 * @namespace Newsletter\Providers
 * @package MandrillProvider
 */

namespace MicroweberPackages\Modules\Newsletter\EmailProviders;

use Config;

class MandrillProvider extends \Newsletter\Providers\DefaultProvider {

	public function send() {

		Config::set('mail.driver', 'mandrill');
		Config::set('services.mandrill.secret', $this->getSecret());

		$this->sendToEmail();

		//var_dump(Config::get('services'));
		//die();
	}

}
