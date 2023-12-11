<?php
/**
 * Amazon ses Provider
 * @author Bozhidar Slaveykov (selfworksbg@gmail.com)
 * @namespace Newsletter\Providers
 * @package AmazonSesProvider
 */

namespace MicroweberPackages\Modules\Newsletter\EmailProviders;

use Config;

class AmazonSesProvider extends \Newsletter\Providers\DefaultProvider {

	public function send() {

		Config::set('mail.driver', 'ses');
		Config::set('services.ses.key', $this->getKey());
		Config::set('services.ses.secret', $this->getSecret());
		Config::set('services.ses.region', $this->getRegion());

		$this->sendToEmail();

		//var_dump(Config::get('services'));
		//die();
	}

}
