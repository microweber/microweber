<?php

namespace Modules\Newsletter\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Newsletter\Models\NewsletterSenderAccount;

class NewsletterSenderAccountFactory extends Factory
{
    protected $model = NewsletterSenderAccount::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'from_name' => $this->faker->name,
            // Avoid @example.* domains: SenderAccountsResource hides those Faker
            // emails from the admin list, making factory rows invisible in tests.
            'from_email' => str_replace(['@example.com', '@example.org', '@example.net'], '@mw-sender.test', $this->faker->unique()->safeEmail),
            'reply_email' => str_replace(['@example.com', '@example.org', '@example.net'], '@mw-sender.test', $this->faker->unique()->safeEmail),
            'account_type' => 'smtp',
            'smtp_username' => $this->faker->userName,
            'smtp_password' => $this->faker->password,
            'smtp_host' => $this->faker->domainName,
            'smtp_port' => '587',
            'mailchimp_secret' => null,
            'mailgun_domain' => null,
            'mailgun_secret' => null,
            'mandrill_secret' => null,
            'sparkpost_secret' => null,
            'amazon_ses_key' => null,
            'amazon_ses_secret' => null,
            'amazon_ses_region' => null,
            'account_pass' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}