<?php
/**
 * Mandrill Provider
 * @author Bozhidar Slaveykov (selfworksbg@gmail.com)
 * @namespace Newsletter\Providers
 * @package MandrillProvider
 */

namespace Modules\Newsletter\EmailProviders;

use Illuminate\Support\Facades\Config;
class MandrillProvider extends DefaultProvider {

	public function send() {

		Config::set('mail.driver', 'mandrill');
		Config::set('services.mandrill.secret', $this->getSecret());

		$this->sendToEmail();

		//var_dump(Config::get('services'));
		//die();
	}

}
