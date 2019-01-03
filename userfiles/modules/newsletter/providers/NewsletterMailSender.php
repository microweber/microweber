<?php

class NewsletterMailSender {
	
	public $campaign;
	public $template;
	public $sender;
	public $subscriber;
	
	public function setCampaign($campaign) {
		$this->campaign = $campaign;
	}
	public function setTemplate($template) {
		$this->template = $template;
	}
	public function setSender($sender) {
		$this->sender = $sender;
	}
	public function setSubscriber($subscriber) {
		$this->subscriber = $subscriber;
	}
	
	public function sendMail() {
		
		try {
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
			$result = $mailer->send($message);
			
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