<?php
namespace Microweber\Utils;

use MailerLiteApi\MailerLite;
use Finlet\flexmail\FlexmailAPI\FlexmailAPI;

class MailProvider
{
	protected $listTitle = '';
	protected $email = '';
	protected $firstName = '';
	protected $phone = '';
	protected $companyName = '';
	protected $companyPosition = '';
	protected $countryRegistration = '';
	protected $message = '';

	public function setListTitle($title) {
		$this->listTitle = $title;
	}
		
	public function setEmail($email) {
		$this->email = $email;
	}

	public function setFirstName($name) {
		$this->name = $name;
	}

	public function setPhone($phone) {
		$this->phone = $phone;
	}

	public function setCompanyName($name) {
		$this->companyName = $name;
	}

	public function setCompanyPosition($position) {
		$this->companyPosition = $position;
	}

	public function setCountryRegistration($country) {
		$this->countryRegistration = $country;
	}

	public function setMessage($message) {
		$this->message = $message;
	}

	public function submit() {
		$this->_flexmail();
		$this->_mailerLite();
	}
	
	private function _flexmail() {
		
		$flexmailApiUserId = get_option('flexmail_api_user_id', 'contact_form_default');
		$flexmailApiUserToken = get_option('flexmail_api_user_token', 'contact_form_default');
		
		if (!empty($flexmailApiUserId) && !empty($flexmailApiUserToken)) {
			
			$config = new \Finlet\flexmail\Config\Config();
			$config->set('wsdl', 'http://soap.flexmail.eu/3.0.0/flexmail.wsdl');
			$config->set('service', 'http://soap.flexmail.eu/3.0.0/flexmail.php');
			$config->set('user_id', $flexmailApiUserId);
			$config->set('user_token', $flexmailApiUserToken); 
			$config->set('debug_mode', true);
			
			$flexmail = new \Finlet\flexmail\FlexmailAPI\FlexmailAPI($config);
			
			$categoryNames = array();
			foreach ($flexmail->service('Category')->getAll()->categoryTypeItems as $category){ 
				$categoryNames[] = $category->categoryName;
			}
			
			if (!in_array($this->listTitle, $categoryNames)) {
				$response = $flexmail->service("List")->create(array(
					"mailingListName"    => $this->listTitle,
					"categoryId" => 0,
					"mailingListLanguage" => "FR",
				));
			}
			
			
			var_dump($categoryNames);
			die();
		}
	}
	
	private function _mailerLite() {
		
		$mailerliteApiKey = get_option('mailerlite_api_key', 'contact_form_default');
		
		if (!empty($mailerliteApiKey)) {
			
			$groupsApi = (new MailerLite($mailerliteApiKey))->groups();
			$allGroups = $groupsApi->get();
			
			$groupNames = array();
			foreach($allGroups as $group) {
				$groupNames[] = $group->name;
				$groupId = $group->id;
			}
			
			if (!in_array($this->listTitle, $groupNames)) {
				$newGroup = $groupsApi->create(['name' => $this->listTitle]);
				$groupId = $newGroup->id;
			}
			
			$subscribersApi = (new MailerLite($mailerliteApiKey))->subscribers();
			$allSubscribers = $subscribersApi->get();
			
			$subscriberEmails = array();
			foreach($allSubscribers as $subscriber) {
				$subscriberEmails[] = $subscriber->email;
			}
			
			if (!in_array($this->email, $subscriberEmails)) {
				$subscriber = [
					'email' => $this->email,
					'fields' => [
						'name' => $this->firstName,
						'last_name' => '',
						'phone' => $this->phone,
						'company' => $this->companyName
					]
				];
				$groupsApi->addSubscriber($groupId, $subscriber);
			}
			
		}
	}
}