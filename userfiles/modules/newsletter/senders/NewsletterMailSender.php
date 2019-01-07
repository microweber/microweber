<?php

class NewsletterMailSender {
	
	public $campaign;
	public $template;
	public $sender;
	public $subscriber;
	
	/**
	 * @return mixed
	 */
	public function getCampaign() {
		return $this->campaign;
	}

	/**
	 * @param mixed $campaign
	 */
	public function setCampaign($campaign) {
		$this->campaign = $campaign;
	}

	/**
	 * @return mixed
	 */
	public function getTemplate() {
		return $this->template;
	}

	/**
	 * @param mixed $template
	 */
	public function setTemplate($template) {
		$this->template = $template;
	}

	/**
	 * @return mixed
	 */
	public function getSender() {
		return $this->sender;
	}

	/**
	 * @param mixed $sender
	 */
	public function setSender($sender) {
		$this->sender = $sender;
	}

	/**
	 * @return mixed
	 */
	public function getSubscriber() {
		return $this->subscriber;
	}

	/**
	 * @param mixed $subscriber
	 */
	public function setSubscriber($subscriber) {
		$this->subscriber = $subscriber;
	}

	public function sendMail() {
		
		try {
			
			switch ($this->getSender()['account_type']) {
				
				case "smtp":
					
					$smtpProvider = new \Newsletter\Providers\SMTPProvider();
					
					$smtpProvider->setSubject($this->campaign['subject']);
					$smtpProvider->setBody($this->_getParsedTemplate());
					
					$smtpProvider->setFromEmail($this->sender['from_email']);
					$smtpProvider->setFromName($this->campaign['name']);
					$smtpProvider->setFromReplyEmail($this->sender['reply_email']);
					
					$smtpProvider->setToEmail($this->subscriber['email']);
					$smtpProvider->setToName($this->subscriber['name']);
					
					$smtpProvider->setSmtpHost($this->sender['smtp_host']);
					$smtpProvider->setSmtpPort($this->sender['smtp_port']);
					$smtpProvider->setSmtpUsername($this->sender['smtp_username']);
					$smtpProvider->setSmtpPassword($this->sender['smtp_password']);
					
					$result = $smtpProvider->send();
					
					break;
				
				default:
					throw new Exception('We don\'t support this mail provider.');
					break;
			}
			
		} catch (Exception $e) {
			$result = $e->getMessage();
		}
		
		return $result;
		
	}
	
	private function _getParsedTemplate() {
		
		$template = str_replace('{first_name}', $this->subscriber['name'], $this->template);
		$template = str_replace('{last_name}', $this->subscriber['name'], $template);
		$template = str_replace('{email}', $this->subscriber['email'], $template);
		$template = str_replace('{site_url}', "SITE-URL.COM", $template);
		
		return $template;
	}
}