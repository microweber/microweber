<?php
/**
 * SMTP Provider
 * @author Bozhidar Slaveykov (selfworksbg@gmail.com)
 * @namespace Newsletter\Providers
 * @package SMTPProvider
 */

namespace MicroweberPackages\Modules\Newsletter\EmailProviders;

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

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


        $transport = new Transport\Smtp\EsmtpTransport(
            host: $this->getSmtpHost(),
            port: $this->getSmtpPort(),
        );
        $transport->setUsername($this->getSmtpUsername());
        $transport->setPassword($this->getSmtpPassword());

        $mailer = new Mailer($transport);

        $email = (new Email())
            ->from($this->getFromEmail())
            ->to($this->getToEmail())
            ->replyTo($this->getFromReplyEmail())
            ->subject($this->getSubject())
            ->html($this->getBody());

        return $mailer->send($email);

	}

}
