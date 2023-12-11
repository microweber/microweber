<?php
/**
 * Sparkpost Provider
 * @author Bozhidar Slaveykov (selfworksbg@gmail.com)
 * @namespace Newsletter\Providers
 * @package SparkpostProvider
 */

namespace MicroweberPackages\Modules\Newsletter\EmailProviders;

use Config;

class SparkpostProvider extends \Newsletter\Providers\DefaultProvider {

	public function send() {

		Config::set('mail.driver', 'sparkpost');
		Config::set('services.sparkpost.secret', $this->getSecret());

		$this->sendToEmail();

		//var_dump(Config::get('services'));
		//die();
	}
}
