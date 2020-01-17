<?php
namespace Microweber\Utils;

use MailerLiteApi\MailerLite;
use Finlet\flexmail\FlexmailAPI\FlexmailAPI;

class MailSubscriber
{
	protected $listTitle = 'default';
	protected $email = '';
	protected $firstName = '';
	protected $lastName = '';
	protected $phone = '';
	protected $address = '';
	protected $companyName = '';
	protected $companyPosition = '';
	protected $countryRegistration = '';
	protected $message = '';
	protected $subscribeFrom = '';
	protected $subscribeSource = '';
	protected $subscribeSourceId = '';
	protected $customFields = array();
	
	public function setListTitle($title) {
		$this->listTitle = $title;
	}
		
	public function setEmail($email) {
		$this->email = $email;
	}

	public function setFirstName($name) {
		$this->firstName = $name;
	}
	
	public function setLastName($name) {
		$this->lastName = $name;
	}

	public function setPhone($phone) {
		$this->phone = $phone;
	}
	
	public function setAddress($address) {
		$this->address = $address;
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
	
	public function setSubscribeFrom($from) {
		$this->subscribeFrom = $from;
	}
	
	public function setSubscribeSource($source) {
		$this->subscribeSource = $source;
	}
	
	public function setSubscribeSourceId($id) {
		$this->subscribeSourceId = $id;
	} 
	
	public function addCustomField($field) {
		$this->customFields[] = $field;
	}

	public function subscribe() {

		if (!empty($this->subscribeFrom)) {
			
			if (get_option('use_integration_with_flexmail', $this->subscribeFrom) == 'y') {
				$this->_flexmail();
			}
			
			if (get_option('use_integration_with_mailerlite', $this->subscribeFrom) == 'y') {
				$this->_mailerLite();
			}
		}
		
	}
	
	private function _flexmail() {

		$settings = get_mail_provider_settings('flexmail');
		
		if (!empty($settings)) {

		/*	$checkSubscriber = get_mail_subscriber($this->email, $this->subscribeSource, $this->subscribeSourceId, 'flexmail');
			
			if (!empty($checkSubscriber)) {
				echo 'Email '.$this->email.' allready subscribed for flexmail.';
				return;
			}*/
			
			try {
				$config = new \Finlet\flexmail\Config\Config();
				$config->set('wsdl', 'http://soap.flexmail.eu/3.0.0/flexmail.wsdl');
				$config->set('service', 'http://soap.flexmail.eu/3.0.0/flexmail.php');
				$config->set('user_id', $settings['api_user_id']);
				$config->set('user_token', $settings['api_user_token']); 
				$config->set('debug_mode', true);
				
				$flexmail = new \Finlet\flexmail\FlexmailAPI\FlexmailAPI($config);
				
				$customFields = array();
				foreach ($this->customFields as $field) {
					
					$customField = new \stdClass();
					$customField->variableName = $field['key'];
					$customField->value = $field['value'];
					
					array_push($customFields, $customField);
				}
				
				$contact = new \stdClass();
				$contact->emailAddress = $this->email;
				$contact->name = $this->firstName;
				$contact->surname = $this->lastName;
				$contact->phone = $this->phone;
				$contact->country = $this->countryRegistration;
				$contact->company = $this->companyName;
				$contact->address = $this->address;
				
				if (!empty($customFields)) {
					$contact->custom = $customFields;
				}
				
				$response = $flexmail->service("Contact")->create(array(
					"mailingListId"    => $settings['mailing_list_id'],
					"emailAddressType" => $contact
				));

				//var_dump($response);

				save_mail_subscriber($this->email, $this->subscribeSource, $this->subscribeSourceId, 'flexmail');
			
			} catch (\Exception $e) {
				if ($e->getCode() == 225)  {
					save_mail_subscriber($this->email, $this->subscribeSource, $this->subscribeSourceId, 'flexmail');
				}
				// Error
				 //dd($e);
			}
		}
	}
	
	private function _mailerLite() {
		
		$settings = get_mail_provider_settings('mailerlite');
		
		if (!empty($settings)) {
			
			$checkSubscriber = get_mail_subscriber($this->email, $this->subscribeSource, $this->subscribeSourceId, 'mailerlite');
			
			if (!empty($checkSubscriber)) {
				// echo 'Email '.$this->email.' allready subscribed for mailerlite.';
				return;
			}
			
			try {
				$groupsApi = (new MailerLite($settings['api_key']))->groups();
				$allGroups = $groupsApi->get();

                $groupId = false;
				foreach($allGroups as $group) {
                    if ($group->name == $this->listTitle) {
                        $groupId = $group->id;
                        break;
                    }
                }
				
				if (!$groupId) {
					$createNewGroup = $groupsApi->create(['name' => $this->listTitle]);
					$groupId = $createNewGroup->id;
				}
				
				$subscriber = [
					'email' => $this->email,
					'fields' => [
						'name' => $this->firstName,
						'last_name' => $this->lastName,
						'phone' => $this->phone,
						'company' => $this->companyName
					]
				];
				$groupsApi->addSubscriber($groupId, $subscriber);
				
				save_mail_subscriber($this->email, $this->subscribeSource, $this->subscribeSourceId, 'mailerlite');
				
			} catch (\Exception $e) {
				// Error
				// dd($e);
			}
		}
	}
}