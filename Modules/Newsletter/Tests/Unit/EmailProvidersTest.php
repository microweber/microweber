<?php

namespace Modules\Newsletter\Tests\Unit;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Modules\Newsletter\EmailProviders\AmazonSesProvider;
use Modules\Newsletter\EmailProviders\MailgunProvider;
use Modules\Newsletter\EmailProviders\MailchimpProvider;
use Modules\Newsletter\EmailProviders\MandrillProvider;
use Modules\Newsletter\EmailProviders\SparkpostProvider;
use Modules\Newsletter\EmailProviders\DefaultProvider;
use Modules\Newsletter\EmailProviders\PHPMailProvider;
use Modules\Newsletter\EmailProviders\SMTPProvider;

class EmailProvidersTest extends TestCase
{
    private function setProviderCommonProps($provider)
    {
        $provider->setFromEmail('from@example.com');
        $provider->setFromName('From Name');
        $provider->setFromReplyEmail('reply@example.com');
        $provider->setToEmail('to@example.com');
        $provider->setToName('To Name');
        $provider->setSubject('Test Subject');
        $provider->setBody('Test Body');
    }

    #[Test]
    public function amazon_ses_provider_sets_config_and_does_not_send_mailable()
    {
        Mail::fake();
        $provider = new AmazonSesProvider();
        $this->setProviderCommonProps($provider);
        $provider->setKey('key');
        $provider->setSecret('secret');
        $provider->setRegion('region');

        $provider->send();

        $this->assertEquals('ses', Config::get('mail.driver'));
        $this->assertEquals('key', Config::get('services.ses.key'));
        $this->assertEquals('secret', Config::get('services.ses.secret'));
        $this->assertEquals('region', Config::get('services.ses.region'));

        Mail::assertNothingSent();
    }

    #[Test]
    public function mailgun_provider_sets_config_and_does_not_send_mailable()
    {
        Mail::fake();
        $provider = new MailgunProvider();
        $this->setProviderCommonProps($provider);
        $provider->setSecret('secret');
        $provider->setDomain('domain');

        $provider->send();

        $this->assertEquals('mailgun', Config::get('mail.driver'));
        $this->assertEquals('secret', Config::get('services.mailgun.secret'));
        $this->assertEquals('domain', Config::get('services.mailgun.domain'));

        Mail::assertNothingSent();
    }

    #[Test]
    public function mailchimp_provider_sets_config_and_does_not_send_mailable()
    {
        Mail::fake();
        $provider = new MailchimpProvider();
        $this->setProviderCommonProps($provider);
        $provider->setSecret('secret');

        $provider->send();

        $this->assertEquals('mailchimp', Config::get('mail.driver'));
        $this->assertEquals('secret', Config::get('services.mailchimp.secret'));

        Mail::assertNothingSent();
    }

    #[Test]
    public function mandrill_provider_sets_config_and_does_not_send_mailable()
    {
        Mail::fake();
        $provider = new MandrillProvider();
        $this->setProviderCommonProps($provider);
        $provider->setSecret('secret');

        $provider->send();

        $this->assertEquals('mandrill', Config::get('mail.driver'));
        $this->assertEquals('secret', Config::get('services.mandrill.secret'));

        Mail::assertNothingSent();
    }

    #[Test]
    public function sparkpost_provider_sets_config_and_does_not_send_mailable()
    {
        Mail::fake();
        $provider = new SparkpostProvider();
        $this->setProviderCommonProps($provider);
        $provider->setSecret('secret');

        $provider->send();

        $this->assertEquals('sparkpost', Config::get('mail.driver'));
        $this->assertEquals('secret', Config::get('services.sparkpost.secret'));

        Mail::assertNothingSent();
    }

    #[Test]
    public function default_provider_send_throws_exception()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("We don't support this mail provider.");
        $provider = new DefaultProvider();
        $provider->send();
    }

    #[Test]
    public function php_mail_provider_send_returns_string()
    {
        Mail::fake();
        // PHPMailProvider does not use Laravel Mail, so we just check it returns a string
        $provider = new PHPMailProvider();
        $provider->setToEmail('to@example.com');
        $provider->setSubject('Subject');
        $provider->setBody('Body');
        $provider->setFromEmail('from@example.com');
        $provider->setFromName('From Name');
        $provider->setFromReplyEmail('reply@example.com');

        $result = $provider->send();
        $this->assertIsString($result);
    }


}
