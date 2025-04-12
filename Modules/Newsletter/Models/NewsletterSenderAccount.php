<?php

namespace Modules\Newsletter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Newsletter\Database\Factories\NewsletterSenderAccountFactory;

class NewsletterSenderAccount extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'newsletter_sender_accounts';

    public $fillable = [
        'name',
        'from_name',
        'from_email',
        'reply_email',
        'account_type',
        'smtp_username',
        'smtp_password',
        'smtp_host',
        'smtp_port',
        'mailchimp_secret',
        'mailgun_domain',
        'mailgun_secret',
        'mandrill_secret',
        'sparkpost_secret',
        'amazon_ses_key',
        'amazon_ses_secret',
        'amazon_ses_region',
        'account_pass',
        'is_active'
    ];

    protected static function newFactory()
    {
        return NewsletterSenderAccountFactory::new();
    }
}
