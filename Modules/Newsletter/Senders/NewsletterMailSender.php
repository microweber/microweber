<?php
/**
 * Newsletter Mail Sender
 * @author Bozhidar Slaveykov (selfworksbg@gmail.com)
 * @namespace Newsletter\Senders
 * @package NewsletterMailSender
 */

namespace Modules\Newsletter\Senders;

use Modules\Newsletter\Support\NewsletterPlaceholderSyntax;
use Modules\Newsletter\EmailProviders\AmazonSesProvider;
use Modules\Newsletter\EmailProviders\MailchimpProvider;
use Modules\Newsletter\EmailProviders\MailgunProvider;
use Modules\Newsletter\EmailProviders\MandrillProvider;
use Modules\Newsletter\EmailProviders\PHPMailProvider;
use Modules\Newsletter\EmailProviders\SMTPProvider;
use Modules\Newsletter\EmailProviders\SparkpostProvider;
use Symfony\Component\Mailer\Transport\Smtp\Auth\CramMd5Authenticator;
use Symfony\Component\Mailer\Transport\Smtp\Auth\LoginAuthenticator;
use Symfony\Component\Mailer\Transport\Smtp\Auth\PlainAuthenticator;
use Symfony\Component\Mailer\Transport\Smtp\Auth\XOAuth2Authenticator;

class NewsletterMailSender
{

    public $campaign;
    public $template = 'This is the test email.';
    public $sender;
    public $subscriber;

    /**
     * @return mixed
     */
    public function getCampaign()
    {
        return $this->campaign;
    }

    /**
     * @param mixed $campaign
     */
    public function setCampaign($campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param mixed $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return mixed
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param mixed $sender
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
    }

    /**
     * @return mixed
     */
    public function getSubscriber()
    {
        return $this->subscriber;
    }

    /**
     * @param mixed $subscriber
     */
    public function setSubscriber($subscriber)
    {
        $this->subscriber = $subscriber;
    }

    public function sendMail()
    {




        try {

            switch ($this->getSender()['account_type']) {


                case "gmail":

//                    $authenticators = [
//                        new  CramMd5Authenticator(),
//                        new  LoginAuthenticator(),
//                        new  PlainAuthenticator(),
//                        new  XOAuth2Authenticator(),
//                    ];



                    $mailProvider = new SMTPProvider();
                    $mailProvider->setSmtpHost('smtp.gmail.com');
                    $mailProvider->setSmtpPort(465);
                    $mailProvider->setEnableTLS(true);
                    //$mailProvider->setAuthenticators($authenticators);
                    $mailProvider->setSmtpUsername($this->sender['smtp_username']);
                    $mailProvider->setSmtpPassword($this->sender['smtp_password']);

                    break;



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

            $template = $this->getParsedTemplate();
            $resolvedSender = $this->getResolvedSender();

            $mailProvider->setSubject($this->campaign['subject']);
            $mailProvider->setBody($template);

            $mailProvider->setFromEmail($resolvedSender['from_email']);
            $mailProvider->setFromName($resolvedSender['from_name']);
            $mailProvider->setFromReplyEmail($resolvedSender['reply_email']);

            $mailProvider->setToEmail($this->subscriber['email']);
            $mailProvider->setToName($this->subscriber['name']);


            $unsubscribeLink = route('modules.newsletter.unsubscribe') . '?email=' . $this->subscriber['email'];

            $mailProvider->setListUnsubscribeLink($unsubscribeLink);





            if (isset($this->campaign['email_attached_files'])
                && !empty($this->campaign['email_attached_files'])) {
                foreach ($this->campaign['email_attached_files'] as $attachedFile) {
                    $attachedFileRealPath = url2dir($attachedFile['fileUrl']);
                    $mailProvider->addAttachment($attachedFileRealPath);
                }
            }

            $result = $mailProvider->send();


            $success = true;

        } catch (\Exception $e) {
            $result = $e->getMessage();

            $success = false;
        }



        return array("success" => $success, "message" => $result);

    }

    public function getParsedTemplate()
    {

        $templateText = NewsletterPlaceholderSyntax::normalizeTwigVariables(
            $this->getTemplate()['text'],
            ['name', 'first_name', 'last_name', 'email', 'site_url', 'unsubscribe', 'unsubscribe_url']
        );

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
                'unsubscribe' => route('modules.newsletter.unsubscribe') . '?email=' . $email,
                'unsubscribe_url' => route('modules.newsletter.unsubscribe') . '?email=' . $email,
            ],
            $twigSettings
        );
        $pixelUrl = route('modules.newsletter.pixel') . '?email=' . $email . '&campaign_id=' . $this->campaign['id'];
        $trackingPixel = '<img src="' . $pixelUrl . '" style="display:none;">';

        // Find </body> and append the tracking pixel
        if (str_contains($parsedEmail, '</body>')) {
            $parsedEmail = str_replace('</body>', $trackingPixel . '</body>', $parsedEmail);
        } else {
            $parsedEmail .= $trackingPixel;
        }

        $parsedEmail = mb_convert_encoding($parsedEmail, 'HTML-ENTITIES', 'UTF-8');

        $dom = new \DOMDocument();
        $dom->loadHTML($parsedEmail, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOENT);
        foreach ($dom->getElementsByTagName('a') as $link) {

            // AI-57 / TICKET-QQ (cycle-64 2026-05-08): HMAC-sign every
            // click-link so the route can verify the (campaign_id, email,
            // redirect_to) tuple was generated by the sender, not crafted
            // by an attacker. Same-host validation (cycle-7 stop-gap)
            // closed the open-redirect leg; HMAC closes the
            // stats-poisoning leg too — anyone who knew the URL pattern
            // could previously POST junk click-records and skew the
            // campaign analytics.
            $originalHref = (string) $link->getAttribute('href');
            $payload = (string) $this->campaign['id']
                . '|' . (string) $email
                . '|' . $originalHref;
            $sig = hash_hmac('sha256', $payload, (string) config('app.key'));

            $redirectLink = route('modules.newsletter.click-link')
                . '?email=' . urlencode($email)
                . '&campaign_id=' . urlencode((string) $this->campaign['id'])
                . '&redirect_to=' . urlencode($originalHref)
                . '&sig=' . urlencode($sig);

            // Prefix the link with the new URL
            $link->setAttribute('href', $redirectLink);

        }
        $parsedEmail = $dom->saveHtml();

        return $parsedEmail;

    }

    public function getResolvedSender(): array
    {
        $campaignName = trim((string) ($this->campaign['name'] ?? ''));

        return [
            'from_name' => $this->resolveSenderValue('from_name', 'from_name') ?: $campaignName,
            'from_email' => $this->resolveSenderValue('from_email', 'from_email'),
            'reply_email' => $this->resolveSenderValue('reply_email', 'reply_email'),
        ];
    }

    protected function resolveSenderValue(string $campaignKey, string $senderKey): ?string
    {
        $campaignValue = trim((string) ($this->campaign[$campaignKey] ?? ''));
        if ($campaignValue !== '') {
            return $campaignValue;
        }

        $senderValue = trim((string) ($this->sender[$senderKey] ?? ''));

        return $senderValue !== '' ? $senderValue : null;
    }
}
