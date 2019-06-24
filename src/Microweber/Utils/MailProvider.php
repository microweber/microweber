<?php
namespace Microweber\Utils;

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
		
		//$groupsApi = (new MailerLite('your-api-key'))->groups();
		//$newGroup = $groupsApi->create(['name' => 'New group']); // creates group and returns it
		//$allGroups = $groupsApi->get(); // returns array of groups
		
		echo get_option('mailerlite', 'contact_form_default'); 
		
		die();
		
	}
}