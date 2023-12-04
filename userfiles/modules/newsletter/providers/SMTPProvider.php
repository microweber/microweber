<?php
/**
 * SMTP Provider
 * @author Bozhidar Slaveykov (selfworksbg@gmail.com)
 * @namespace Newsletter\Providers
 * @package SMTPProvider
 */

namespace Newsletter\Providers;

class SMTPProvider extends \Newsletter\Providers\DefaultProvider {

	// SMTP Settings
	protected $smtpHost;
	protected $smtpPort = 587; // 587 or 995, 465, 110, 25
	protected $smtpUsername;
	protected $smtpPassword;

	/**
	 * @return mixed
	 */
	private function getSmtpHost() {
		return $this->smtpHost;
	}

	/**
	 * @param mixed $smtpHost
	 */
	public function setSmtpHost($smtpHost) {
		$this->smtpHost = $smtpHost;
	}

	/**
	 * @return number
	 */
	private function getSmtpPort() {
		return $this->smtpPort;
	}

	/**
	 * @param number $smtpPort
	 */
	public function setSmtpPort($smtpPort) {
		$this->smtpPort = $smtpPort;
	}

	/**
	 * @return mixed
	 */
	private function getSmtpUsername() {
		return $this->smtpUsername;
	}

	/**
	 * @param mixed $smtpUsername
	 */
	public function setSmtpUsername($smtpUsername) {
		$this->smtpUsername = $smtpUsername;
	}

	/**
	 * @return mixed
	 */
	private function getSmtpPassword() {
		return $this->smtpPassword;
	}

	/**
	 * @param mixed $smtpPassword
	 */
	public function setSmtpPassword($smtpPassword) {
		$this->smtpPassword = $smtpPassword;
	}

	public function send() {

		// Create the Transport
		$transport = (new \Swift_SmtpTransport($this->getSmtpHost(), $this->getSmtpPort()))
		->setUsername($this->getSmtpUsername())
		->setPassword($this->getSmtpPassword());

		// Create the Mailer using your created Transport
		$mailer = new \Swift_Mailer($transport);

		// Create a message
		$message = (new \Swift_Message($this->getSubject()))
		->setFrom([$this->getFromEmail() => $this->getFromName()])
		->setTo([$this->getToEmail(), $this->getFromReplyEmail() => $this->getFromName()])
		->setBody($this->getBody(), 'text/html');

		// Send the message
		return $mailer->send($message);

	}

}
