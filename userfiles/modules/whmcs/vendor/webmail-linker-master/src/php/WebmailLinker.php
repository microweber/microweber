<?php

/**
 * Webmail Linker – Collection of Email Providers' Webmail Sites
 * 
 * @link    https://github.com/thomasbachem/webmail-linker
 * @license http://opensource.org/licenses/MIT
 * @author  Thomas Bachem <mail@thomasbachem.com>
 */

class WebmailLinker {
	
	/**
	 * @var array
	 */
	protected $providers = array();

	
	public function __construct() {
		$path = dirname(__FILE__) . '/../../config/providers.json';
		if(!is_readable($path)) {
			throw new Exception('Unable to read providers config from ' . $path . '.');
		}

		$config = json_decode(file_get_contents($path), true);
		if(!$config) {
			throw new Exception('Providers config is invalid JSON.');
		}
		
		foreach($config as $i => $provider) {
			// Filter out string entries that may be used as comments
			// (faster than is_array())
			if((array)$provider === $provider) {
				if(!isset($provider['name'])) {
					throw new Exception('Provider ' . $i . ' is missing a "name" property.');
				}
				if(!isset($provider['domains'])) {
					throw new Exception('Provider ' . $provider['name'] . ' is missing a "domains" property.');
				}
				if(!isset($provider['url'])) {
					throw new Exception('Provider ' . $provider['name'] . ' is missing a "url" property.');
				}

				$this->providers[] = $provider;
			}
		}
	}

	/**
	 * @return array array(array('name' => ..., 'url' => ..., 'icon' => ...), ...)
	 */
	public function getProviders() {
		return $this->providers;
	}
	
	/**
	 * @param string $emailAddress
	 * @return array|null array('name' => ..., 'url' => ..., 'icon' => ...)
	 */
	public function getProviderByEmailAddress($emailAddress) {
		$emailParts = explode('@', $emailAddress);
		if(count($emailParts) !== 2) {
			throw new Exception('Invalid email address "' . $emailAddress . '" provided.');
		}

		return $this->getProviderByDomain($emailParts[1]);
	}

	/**
	 * @param string $domain
	 * @return array|null array('name' => ..., 'url' => ..., 'icon' => ...)
	 */
	public function getProviderByDomain($domain) {
		foreach($this->providers as $i => $provider) {
			foreach(explode(' ', $provider['domains']) as $providerDomain) {
				if(preg_match('/^' . $providerDomain . '$/i', $domain)) {
					return $provider;
				}
			}
		}

		return null;
	}

}