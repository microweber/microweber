<?php
/**
 * Amazon ses Provider
 * @author Bozhidar Slaveykov (selfworksbg@gmail.com)
 * @namespace Newsletter\Providers
 * @package AmazonSesProvider
 */

namespace Newsletter\Providers;

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