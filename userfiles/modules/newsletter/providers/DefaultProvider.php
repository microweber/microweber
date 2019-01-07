<?php
/**
 * Default Provider
 * @author Bozhidar Slaveykov (selfworksbg@gmail.com)
 * @namespace Newsletter\Providers
 * @package DefaultProvider
 */

namespace Newsletter\Providers;

class DefaultProvider {
	
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

	
}