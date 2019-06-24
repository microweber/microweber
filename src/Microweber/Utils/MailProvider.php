<?php
namespace Microweber\Utils;

use MailerLiteApi\MailerLite;

class MailProvider
{
	protected $listTitle;
	protected $email;
	protected $firstName;
	protected $phone;
	protected $companyName;
	protected $companyPosition;
	protected $companyRegistration;
	protected $message;

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

	public function setCompanyRegistration($registration) {
		$this->companyRegistration = $registration;
	}

	public function setMessage($message) {
		$this->message = $message;
	}

	public function submit() {
		
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
			
			
			$subscriber = [
				'email' => $this->email,
				'fields' => [
					'name' => $this->firstName,
					'last_name' => '',
					'phone' => $this->phone,
					'company' => $this->companyName
				]
			];
			
			$response = $groupsApi->addSubscriber($groupId, $subscriber);
			
			var_dump($response);
			die();
		}
		
	}
}