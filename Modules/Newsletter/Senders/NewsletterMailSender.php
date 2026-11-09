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


            // Carry the ownership token so unsubscribe can't be driven by email alone.
            $unsubscribeParams = ['email' => $this->subscriber['email']];
            if (!empty($this->subscriber['confirmation_code'])) {
                $unsubscribeParams['code'] = $this->subscriber['confirmation_code'];
            }
            $unsubscribeLink = route('modules.newsletter.unsubscribe') . '?' . http_build_query($unsubscribeParams);

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

        $rawTemplateText = (string) ($this->getTemplate()['text'] ?? '');
        $templateText = NewsletterPlaceholderSyntax::normalizeTwigVariables(
            $rawTemplateText,
            ['name', 'first_name', 'last_name', 'email', 'site_url', 'unsubscribe', 'unsubscribe_url']
        );
        // Never let normalisation blank a non-empty template (defensive — keeps
        // the body if the placeholder pass returns empty in some environment).
        if (trim($templateText) === '' && trim($rawTemplateText) !== '') {
            $templateText = $rawTemplateText;
        }

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

        $unsubscribeUrl = route('modules.newsletter.unsubscribe') . '?email=' . $email;
        $vars = [
            'name' => $name,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'site_url' => $siteUrl,
            'unsubscribe' => $unsubscribeUrl,
            'unsubscribe_url' => $unsubscribeUrl,
        ];

        $twig = new \MicroweberPackages\View\TwigView();
        $twigSettings = [
            'autoescape' => false
        ];
        try {
            $parsedEmail = $twig->render($templateText, $vars, $twigSettings);
        } catch (\Throwable $e) {
            $parsedEmail = '';
        }

        // Resilience: if Twig yields an EMPTY body for a NON-empty template (seen
        // in some environments where the Twig render silently produces nothing),
        // the email content would be dropped and only the tracking pixel would
        // send. Fall back to a direct placeholder substitution so the body is
        // never lost. Normalised placeholders are the spaced `{{ var }}` form.
        if (trim($parsedEmail) === '' && trim($templateText) !== '') {
            $map = [];
            foreach ($vars as $key => $value) {
                $map['{{ ' . $key . ' }}'] = (string) $value;
                $map['{{' . $key . '}}'] = (string) $value;
            }
            $parsedEmail = strtr($templateText, $map);
        }
        $pixelUrl = route('modules.newsletter.pixel') . '?email=' . $email . '&campaign_id=' . $this->campaign['id'];
        $trackingPixel = '<img src="' . $pixelUrl . '" style="display:none;">';

        // Find </body> and append the tracking pixel
        if (str_contains($parsedEmail, '</body>')) {
            $parsedEmail = str_replace('</body>', $trackingPixel . '</body>', $parsedEmail);
        } else {
            $parsedEmail .= $trackingPixel;
        }

        // Rewrite click-links through the HMAC-signed tracking redirect — but ONLY
        // when the body actually contains <a> links. The DOMDocument round-trip
        // below drops bare top-level text (LIBXML_HTML_NOIMPLIED gives no implied
        // <body> to hold it), which in some libxml builds silently emptied a
        // plain-text / link-less body and sent an email with only the tracking
        // pixel. Skipping the round-trip when there is nothing to sign preserves
        // the body. (Also drop the removed-in-PHP-8.2 mb_convert_encoding(
        // 'HTML-ENTITIES') hack — UTF-8 is handled via the XML prolog below.)
        if (stripos($parsedEmail, '<a ') === false) {
            return $parsedEmail;
        }

        // Wrap in a container so top-level text is never discarded, and declare
        // UTF-8 the modern way (the old mb_convert_encoding hack is gone).
        $dom = new \DOMDocument();
        @$dom->loadHTML(
            '<?xml encoding="UTF-8" ?><div data-mw-newsletter-body>' . $parsedEmail . '</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOENT
        );
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

        // Serialize the INNER content of the wrapper (not the wrapper div itself),
        // so the output is the email body with the links rewritten.
        $wrapper = $dom->documentElement;
        if ($wrapper !== null) {
            $inner = '';
            foreach ($wrapper->childNodes as $child) {
                $inner .= $dom->saveHTML($child);
            }
            $parsedEmail = $inner;
        }

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
