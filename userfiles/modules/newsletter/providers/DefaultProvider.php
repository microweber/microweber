<?php

class DefaultProvider {
	
	// Message settings
	protected $subject;
	
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
	public function getSubject() {
		return $this->subject;
	}

	/**
	 * @return mixed
	 */
	public function getFromName() {
		return $this->fromName;
	}

	/**
	 * @return mixed
	 */
	public function getFromEmail() {
		return $this->fromEmail;
	}

	/**
	 * @return mixed
	 */
	public function getFromReplyEmail() {
		return $this->fromReplyEmail;
	}

	/**
	 * @return mixed
	 */
	public function getToEmail() {
		return $this->toEmail;
	}

	/**
	 * @return mixed
	 */
	public function getToName() {
		return $this->toName;
	}

	/**
	 * @param mixed $subject
	 */
	public function setSubject($subject) {
		$this->subject = $subject;
	}

	/**
	 * @param mixed $fromName
	 */
	public function setFromName($fromName) {
		$this->fromName = $fromName;
	}

	/**
	 * @param mixed $fromEmail
	 */
	public function setFromEmail($fromEmail) {
		$this->fromEmail = $fromEmail;
	}

	/**
	 * @param mixed $fromReplyEmail
	 */
	public function setFromReplyEmail($fromReplyEmail) {
		$this->fromReplyEmail = $fromReplyEmail;
	}

	/**
	 * @param mixed $toEmail
	 */
	public function setToEmail($toEmail) {
		$this->toEmail = $toEmail;
	}

	/**
	 * @param mixed $toName
	 */
	public function setToName($toName) {
		$this->toName = $toName;
	}

}