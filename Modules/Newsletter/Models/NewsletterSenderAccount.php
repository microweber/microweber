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

    /**
     * audit-test 2026-05-07 PM TASK-004 / TICKET-AL — encrypted-at-rest:
     * Cycle-30 added `->password()` masking at the Filament UI but the DB
     * still stored cleartext. Anyone with DB read access could see all
     * SMTP/Mailchimp/Mailgun/Mandrill/Sparkpost/SES credentials in the
     * `newsletter_sender_accounts` table. The `encrypted` cast wraps each
     * column read/write with the app-key AEAD encrypt/decrypt pipeline.
     *
     * Companion migration (`2026_05_07_000001_widen_and_encrypt_newsletter_sender_account_credentials.php`):
     *   1. Widens the 8 credential columns from VARCHAR(255) to TEXT
     *      (encrypted output is ~1.4× longer plus AEAD header — would
     *      truncate on long secrets if left at 255).
     *   2. Re-saves every existing row through the model so the cast
     *      encrypts the cleartext that was already there.
     *
     * NOTE: `gmail_app_password` is intentionally NOT in this list. Per
     * agent-test follow-up Gotcha #2, the Filament form has gmail_email +
     * gmail_app_password fields but neither is in $fillable nor in the
     * migration — the form values are silently dropped on save. Filed
     * under TICKET-AX (Newsletter Gmail save-path) for separate work
     * before encryption can apply.
     */
    protected $casts = [
        'smtp_password' => 'encrypted',
        'mailchimp_secret' => 'encrypted',
        'mailgun_secret' => 'encrypted',
        'mandrill_secret' => 'encrypted',
        'sparkpost_secret' => 'encrypted',
        'amazon_ses_key' => 'encrypted',
        'amazon_ses_secret' => 'encrypted',
        'account_pass' => 'encrypted',
        'is_active' => 'boolean',
    ];

    protected static function newFactory()
    {
        return NewsletterSenderAccountFactory::new();
    }
}
