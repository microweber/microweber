<?php
/**
 * Mandrill Provider
 * @author Bozhidar Slaveykov (selfworksbg@gmail.com)
 * @namespace Newsletter\Providers
 * @package MandrillProvider
 */

namespace Newsletter\Providers;

use Illuminate\Mail\Transport\MandrillTransport;

class MandrillProvider extends \Newsletter\Providers\DefaultProvider {
	
	public function send() {
		
		$client = new \GuzzleHttp\Client();
		
		$transport = new MandrillTransport($client, $this->getKey());
		
		// Create a message
		$message = (new \Swift_Message($this->getSubject()))
		->setFrom([$this->getFromEmail() => $this->getFromName()])
		->setTo([$this->getToEmail(), $this->getFromReplyEmail() => $this->getFromName()])
		->setBody($this->getBody());
		
		return $transport->send($message);
	}
	
}