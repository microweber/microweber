<?php
/**
 * Sparkpost Provider
 * @author Bozhidar Slaveykov (selfworksbg@gmail.com)
 * @namespace Newsletter\Providers
 * @package SparkpostProvider
 */

namespace Modules\Newsletter\EmailProviders;

use Illuminate\Support\Facades\Config;

class SparkpostProvider extends DefaultProvider {

	public function send() {

		Config::set('mail.driver', 'sparkpost');
		Config::set('services.sparkpost.secret', $this->getSecret());

		$this->sendToEmail();

	}
}
