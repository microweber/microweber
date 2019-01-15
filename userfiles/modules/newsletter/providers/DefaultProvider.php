<?php
/**
 * Default Provider
 * @author Bozhidar Slaveykov (selfworksbg@gmail.com)
 * @namespace Newsletter\Providers
 * @package DefaultProvider
 */

namespace Newsletter\Providers;

use Illuminate\Support\Facades\Mail;

class DefaultProvider {
	
	// Provider settings
	protected $domain;
	protected $secret;
	protected $key;
	protected $region;
	
	// Message settings
	protected $subject;
	protected $body;
	
	// Sender settings
	protected $fromName;
	protected $fromEmail;
	protected $fromReplyEmail;
	
	// Reciver settings
	protected $toEmail;
	protected $toName;
	
	/**
	 * @return mixed
	 */
	protected function getDomain() {
		return $this->domain;
	}

	/**
	 * @param mixed $domain
	 */
	public function setDomain($domain) {
		$this->domain = $domain;
	}

	/**
	 * @return mixed
	 */
	protected function getSecret() {
		return $this->secret;
	}

	/**
	 * @param mixed $secret
	 */
	public function setSecret($secret) {
		$this->secret = $secret;
	}

	/**
	 * @return mixed
	 */
	protected function getKey() {
		return $this->key;
	}

	/**
	 * @param mixed $key
	 */
	public function setKey($key) {
		$this->key = $key;
	}

	/**
	 * @return mixed
	 */
	protected function getRegion() {
		return $this->region;
	}

	/**
	 * @param mixed $region
	 */
	public function setRegion($region) {
		$this->region = $region;
	}

	/**
	 * @return mixed
	 */
	protected function getSubject() {
		return $this->subject;
	}

	/**
	 * @param mixed $subject
	 */
	public function setSubject($subject) {
		$this->subject = $subject;
	}

	/**
	 * @return mixed
	 */
	protected function getBody() {
		return $this->body;
	}
	
	/**
	 * @param mixed $body
	 */
	public function setBody($body) {
		$this->body = $body;
	}
	
	
	/**
	 * @return mixed
	 */
	protected function getFromName() {
		return $this->fromName;
	}

	/**
	 * @param mixed $fromName
	 */
	public function setFromName($fromName) {
		$this->fromName = $fromName;
	}

	/**
	 * @return mixed
	 */
	protected function getFromEmail() {
		return $this->fromEmail;
	}

	/**
	 * @param mixed $fromEmail
	 */
	public function setFromEmail($fromEmail) {
		$this->fromEmail = $fromEmail;
	}

	/**
	 * @return mixed
	 */
	protected function getFromReplyEmail() {
		return $this->fromReplyEmail;
	}

	/**
	 * @param mixed $fromReplyEmail
	 */
	public function setFromReplyEmail($fromReplyEmail) {
		$this->fromReplyEmail = $fromReplyEmail;
	}

	/**
	 * @return mixed
	 */
	protected function getToEmail() {
		return $this->toEmail;
	}

	/**
	 * @param mixed $toEmail
	 */
	public function setToEmail($toEmail) {
		$this->toEmail = $toEmail;
	}

	/**
	 * @return mixed
	 */
	protected function getToName() {
		return $this->toName;
	}

	/**
	 * @param mixed $toName
	 */
	public function setToName($toName) {
		$this->toName = $toName;
	}

	public function send() {
		throw new \Exception('We don\'t support this mail provider.');
	}
	
	protected function sendToEmail() {
		
		Mail::raw($this->getBody(), function ($message) {
			$message->from($this->getFromEmail(), $this->getFromName());
			$message->to($this->getToEmail(), $this->getToName());
			$message->replyTo($this->getFromReplyEmail());
			$message->subject($this->getSubject());
		});
		
	}
}