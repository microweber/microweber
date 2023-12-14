<?php
/**
 * Newsletter Mail Sender
 * @author Bozhidar Slaveykov (selfworksbg@gmail.com)
 * @namespace Newsletter\Senders
 * @package NewsletterMailSender
 */

namespace MicroweberPackages\Modules\Newsletter\Senders;

use MicroweberPackages\Modules\Newsletter\EmailProviders\AmazonSesProvider;
use MicroweberPackages\Modules\Newsletter\EmailProviders\MailchimpProvider;
use MicroweberPackages\Modules\Newsletter\EmailProviders\MailgunProvider;
use MicroweberPackages\Modules\Newsletter\EmailProviders\MandrillProvider;
use MicroweberPackages\Modules\Newsletter\EmailProviders\PHPMailProvider;
use MicroweberPackages\Modules\Newsletter\EmailProviders\SMTPProvider;
use MicroweberPackages\Modules\Newsletter\EmailProviders\SparkpostProvider;

class NewsletterMailSender {

	public $campaign;
	public $template = 'This is the test email.';
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

					$mailProvider = new SMTPProvider();
					$mailProvider->setSmtpHost($this->sender['smtp_host']);
					$mailProvider->setSmtpPort($this->sender['smtp_port']);
					$mailProvider->setSmtpUsername($this->sender['smtp_username']);
					$mailProvider->setSmtpPassword($this->sender['smtp_password']);

					break;

				case "php_mail":
					$mailProvider = new PHPMailProvider();
					break;

				case "mailchimp":
					$mailProvider = new MailchimpProvider();
					$mailProvider->setSecret($this->sender['mailchimp_secret']);
					break;

				case "mailgun":
					$mailProvider = new MailgunProvider();
					$mailProvider->setDomain($this->sender['mailgun_domain']);
					$mailProvider->setSecret($this->sender['mailgun_secret']);
					break;

				case "mandrill":
					$mailProvider = new MandrillProvider();
					$mailProvider->setSecret($this->sender['mandrill_secret']);
					break;

				case "amazon_ses":
					$mailProvider = new AmazonSesProvider();
					$mailProvider->setKey($this->sender['amazon_ses_key']);
					$mailProvider->setSecret($this->sender['amazon_ses_secret']);
					$mailProvider->setRegion($this->sender['amazon_ses_region']);
					break;

				case "sparkpost":
					$mailProvider = new SparkpostProvider();
					$mailProvider->setSecret($this->sender['sparkpost_secret']);
					break;

				default:
					throw new \Exception('We don\'t support this mail provider.');
					break;
			}

            $template = $this->_getParsedTemplate();

			$mailProvider->setSubject($this->campaign['subject']);
			$mailProvider->setBody($template);

			$mailProvider->setFromEmail($this->sender['from_email']);
			$mailProvider->setFromName($this->campaign['name']);
			$mailProvider->setFromReplyEmail($this->sender['reply_email']);

			$mailProvider->setToEmail($this->subscriber['email']);
			$mailProvider->setToName($this->subscriber['name']);

			$result = $mailProvider->send();

			$success = true;

		} catch (\Exception $e) {
			$result = $e->getMessage();

			$success = false;
		}

		return array("success"=>$success, "message"=>$result);

	}

	private function _getParsedTemplate() {

        $templateText = $this->getTemplate()['text'];

        $firstName = '';
        $lastName = '';
        $name = '';
        $email = '';
        $siteUrl = url('/');

        if (isset($this->subscriber['name'])) {
            $name = $this->subscriber['name'];
        }
        if (isset($this->subscriber['first_name'])) {
            $firstName = $this->subscriber['first_name'];
        }
        if (isset($this->subscriber['last_name'])) {
            $lastName = $this->subscriber['last_name'];
        }
        if (isset($this->subscriber['email'])) {
            $email = $this->subscriber['email'];
        }

        if (empty($firstName)) {
            $firstName = $name;
        }
        if (empty($lastName)) {
            $lastName = $name;
        }

        $twig = new \MicroweberPackages\View\TwigView();

        $twigSettings = [
            'autoescape' => false
        ];
        $parsedEmail = $twig->render($templateText,
            [
                'name' => $name,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'site_url' => $siteUrl,
                'unsubscribe' => $siteUrl . '/web/modules/newsletter/unsubscribe?email=' . $email,
            ],
            $twigSettings
        );

        return $parsedEmail;

	}
}
