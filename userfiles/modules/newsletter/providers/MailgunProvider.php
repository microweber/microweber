<?php
/**
 * Mailgun Provider
 * @author Bozhidar Slaveykov (selfworksbg@gmail.com)
 * @namespace Newsletter\Providers
 * @package MailgunProvider
 */

namespace Newsletter\Providers;

use Illuminate\Mail\Transport\MailgunTransport;

class MailgunProvider extends \Newsletter\Providers\DefaultProvider {
	
	public function send() {
		
		$client = new \GuzzleHttp\Client();
		
		$transport = new MailgunTransport($client, $this->getKey(), $this->getDomain());
		
		// Create a message
		$message = (new \Swift_Message($this->getSubject()))
		->setFrom([$this->getFromEmail() => $this->getFromName()])
		->setTo([$this->getToEmail(), $this->getFromReplyEmail() => $this->getFromName()])
		->setBody($this->getBody());
		
		return $transport->send($message);
	}
}