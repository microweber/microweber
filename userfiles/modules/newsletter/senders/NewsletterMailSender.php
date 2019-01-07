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
			
			
			var_dump($this->sender);
			die();
			
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