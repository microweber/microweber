<?php
/**
 * Mandrill Provider
 * @author Bozhidar Slaveykov (selfworksbg@gmail.com)
 * @namespace Newsletter\Providers
 * @package MandrillProvider
 */

namespace Newsletter\Providers;

class MandrillProvider extends \Newsletter\Providers\DefaultProvider {
	
	public function send() {
		throw new \Exception('We don\'t support this mail provider.');
	}
}