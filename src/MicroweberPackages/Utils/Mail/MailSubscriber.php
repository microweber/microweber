<?php
namespace MicroweberPackages\Utils\Mail;

use MailerLiteApi\MailerLite;
use Finlet\flexmail\FlexmailAPI\FlexmailAPI;

class MailSubscriber
{
	protected $listTitle = 'default';
	protected $email = '';
	protected $firstName = '';
	protected $lastName = '';
	protected $phone = '';
	protected $city = '';
	protected $state = '';
	protected $zip = '';
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

	public function setCity($city) {
		$this->city = $city;
	}

	public function setState($state) {
		$this->state = $state;
	}

	public function setZip($zip) {
		$this->zip = $zip;
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

	public function subscribe($force = false) {

	    $log = [];
		if (!empty($this->subscribeFrom) || $force) {

		    // FlexMail
		    $flexMailSubscribe = false;
			if (get_option('use_integration_with_flexmail', $this->subscribeFrom) == 'y') {
                $flexMailSubscribe = true;
            }
            $flexmailSettings = get_mail_provider_settings('flexmail');
            if (isset($flexmailSettings['active']) && $flexmailSettings['active'] == 'y') {
                $flexMailSubscribe = true;
            }
            if (isset($flexmailSettings['active']) && $flexmailSettings['active'] == 'n') {
                $flexMailSubscribe = false;
            }

            if ($flexMailSubscribe) {
			    $response = $this->_flexmail($force);
                $response['name'] = 'Flex Mail';
				$log['providers'][] = $response;
			}

			// Mailerlite
            $mailerLiteSubcribe = false;
			if (get_option('use_integration_with_mailerlite', $this->subscribeFrom) == 'y') {
                $mailerLiteSubcribe = true;
            }
            $mailerliteSettings = get_mail_provider_settings('mailerlite');
            if (isset($mailerliteSettings['active']) && $mailerliteSettings['active'] == 'y') {
                $mailerLiteSubcribe = true;
            }
            if (isset($mailerliteSettings['active']) && $mailerliteSettings['active'] == 'n') {
                $mailerLiteSubcribe = false;
            }
            if ($mailerLiteSubcribe) {
                $response = $this->_mailerLite($force);
                $response['name'] = 'Mailer Lite';
                $log['providers'][] = $response;
			}
		}

		return $log;
	}

	private function _flexmail($force = false) {

		$settings = get_mail_provider_settings('flexmail');

		if (!empty($settings)) {

		    if ($force == false) {
                $checkSubscriber = get_mail_subscriber($this->email, $this->subscribeSource, $this->subscribeSourceId, 'flexmail');
                if (!empty($checkSubscriber)) {
                    return ['success'=>true, 'log'=>'Email ' . $this->email . ' allready subscribed for flexmail.'];
                }
            }

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

                return ['success'=>true, 'log'=>'Email ' . $this->email . ' subscribed for flexmail.'];

			} catch (\Exception $e) {
				if ($e->getCode() == 225)  {
					save_mail_subscriber($this->email, $this->subscribeSource, $this->subscribeSourceId, 'flexmail');
                    return ['success'=>true, 'log'=>'Email ' . $this->email . ' allready subscribed for flexmail.'];
				}

				return ['failed'=>true, 'log'=>$e->getMessage()];
			}
		}
	}

	private function _mailerLite($force = false) {

		$settings = get_mail_provider_settings('mailerlite');

		if (!empty($settings)) {

		    if ($force == false) {
                $checkSubscriber = get_mail_subscriber($this->email, $this->subscribeSource, $this->subscribeSourceId, 'mailerlite');
                if (!empty($checkSubscriber)) {
                    return ['success'=>true, 'log'=>'Email ' . $this->email . ' allready subscribed for mailerlite.'];
                }
            }

			try {
				$groupsApi = (new MailerLite($settings['api_key']))->groups();
			//	$allGroups = $groupsApi->get();
				$allGroups =  $groupsApi->where(['name' => $this->listTitle])->get();




                $groupId = false;
				foreach($allGroups as $group) {
                    if (isset($group->name) && isset($group->id) && $group->name == $this->listTitle) {
                        $groupId = $group->id;
                        break;
                    }
                }

                $allGroupsArray = $allGroups->toArray();
                if (isset($allGroupsArray[0]->error->message)) {
                    return ['failed'=>true, 'log'=>$allGroupsArray[0]->error->message];
                }

				if (!$groupId) {
					$createNewGroup = $groupsApi->create(['name' => $this->listTitle]);
					if (isset($createNewGroup->id)) {
                        $groupId = $createNewGroup->id;
                    }
				}

				$subscriber = [
					'email' => $this->email,
					'fields' => [
						'name' => $this->firstName,
						'last_name' => $this->lastName,
						'country' => $this->countryRegistration,
						'city' => $this->city,
						'state' => $this->state,
						'zip' => $this->zip,
						'phone' => $this->phone,
						'company' => $this->companyName
					]
				];
				$groupsApi->addSubscriber($groupId, $subscriber);

				save_mail_subscriber($this->email, $this->subscribeSource, $this->subscribeSourceId, 'mailerlite');

                return ['success'=>true, 'log'=>'Email ' . $this->email . ' subscribed for mailerlite.'];

			} catch (\Exception $e) {
                return ['failed'=>true, 'log'=>$e->getMessage() .' code:'. $e->getLine()];
			}
		}
	}
}
