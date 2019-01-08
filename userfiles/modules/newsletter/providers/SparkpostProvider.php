<?php
/**
 * Sparkpost Provider
 * @author Bozhidar Slaveykov (selfworksbg@gmail.com)
 * @namespace Newsletter\Providers
 * @package SparkpostProvider
 */

namespace Newsletter\Providers;

use Illuminate\Mail\Transport\SparkPostTransport;

class SparkpostProvider extends \Newsletter\Providers\DefaultProvider {
	
	public function send() {
		
		$client = new \GuzzleHttp\Client();
		
		$transport = new SparkPostTransport($client, $this->getKey());
		
		// Create a message
		$message = (new \Swift_Message($this->getSubject()))
		->setFrom([$this->getFromEmail() => $this->getFromName()])
		->setTo([$this->getToEmail(), $this->getFromReplyEmail() => $this->getFromName()])
		->setBody($this->getBody());
		
		return $transport->send($message);
	}
}