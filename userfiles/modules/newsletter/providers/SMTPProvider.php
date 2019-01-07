<?php
include 'DefaultProvider.php';

class SMTPProvider extends DefaultProvider {

	// SMTP Settings
	protected $smtpHost;
	protected $smtpPort = 587; // 587 or 995, 465, 110, 25
	protected $smtpUsername;
	protected $smtpPassword;
	
	/**
	 * @return mixed
	 */
	public function getSmtpHost() {
		return $this->smtpHost;
	}

	/**
	 * @return number
	 */
	public function getSmtpPort() {
		return $this->smtpPort;
	}

	/**
	 * @return mixed
	 */
	public function getSmtpUsername() {
		return $this->smtpUsername;
	}

	/**
	 * @return mixed
	 */
	public function getSmtpPassword() {
		return $this->smtpPassword;
	}

	/**
	 * @param mixed $smtpHost
	 */
	public function setSmtpHost($smtpHost) {
		$this->smtpHost = $smtpHost;
	}

	/**
	 * @param number $smtpPort
	 */
	public function setSmtpPort($smtpPort) {
		$this->smtpPort = $smtpPort;
	}

	/**
	 * @param mixed $smtpUsername
	 */
	public function setSmtpUsername($smtpUsername) {
		$this->smtpUsername = $smtpUsername;
	}

	/**
	 * @param mixed $smtpPassword
	 */
	public function setSmtpPassword($smtpPassword) {
		$this->smtpPassword = $smtpPassword;
	}

	public function send() {
		
		// Create the Transport
		$transport = (new Swift_SmtpTransport($this->sender['smtp_host'], $this->sender['smtp_port']))
		->setUsername($this->sender['smtp_username'])
		->setPassword($this->sender['smtp_password']);
		
		// Create the Mailer using your created Transport
		$mailer = new Swift_Mailer($transport);
		
		// Create a message
		$message = (new Swift_Message($this->campaign['subject']))
		->setFrom([$this->sender['from_email'] => $this->campaign['name']])
		->setTo([$this->subscriber['email'], $this->sender['reply_email'] => $this->subscriber['name']])
		->setBody($this->_getParsedTemplate());
		
		// Send the message
		return $mailer->send($message);
		
	}
	
}