<?php

namespace Modules\Newsletter\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Newsletter\Models\NewsletterSenderAccount;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NewsletterSenderAccountTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_sender_account()
    {
        $data = [
            'name' => 'Test Sender',
            'from_name' => 'Test From',
            'from_email' => 'from@example.com',
            'reply_email' => 'reply@example.com',
            'account_type' => 'smtp',
            'smtp_username' => 'user',
            'smtp_password' => 'pass',
            'smtp_host' => 'smtp.example.com',
            'smtp_port' => 587,
            'mailchimp_secret' => 'secret1',
            'mailgun_domain' => 'mg.example.com',
            'mailgun_secret' => 'secret2',
            'mandrill_secret' => 'secret3',
            'sparkpost_secret' => 'secret4',
            'amazon_ses_key' => 'key',
            'amazon_ses_secret' => 'secret5',
            'amazon_ses_region' => 'us-east-1',
            'account_pass' => 'apass',
            'is_active' => true,
        ];

        $sender = NewsletterSenderAccount::create($data);

        $this->assertDatabaseHas('newsletter_sender_accounts', [
            'id' => $sender->id,
            'name' => 'Test Sender',
            'from_email' => 'from@example.com',
            'is_active' => true,
        ]);
    }

    #[Test]
    public function it_has_factory()
    {
        $sender = NewsletterSenderAccount::factory()->create();
        $this->assertInstanceOf(NewsletterSenderAccount::class, $sender);
        $this->assertDatabaseHas('newsletter_sender_accounts', [
            'id' => $sender->id,
        ]);
    }
}