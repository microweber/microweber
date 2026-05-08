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
     * NOTE (resolved cycle-48 PM TASK-009 / TICKET-AX): the Filament form
     * had vestigial `gmail_email` + `gmail_app_password` fields that never
     * persisted (not in $fillable, not in the migration — silent drop on
     * save). The right fix turned out to be a form-side rename, not a
     * column-add: NewsletterMailSender.php:122-123 reads
     * `$sender['smtp_username']` + `$sender['smtp_password']` for both
     * account_type='gmail' AND account_type='smtp' (Gmail just hardcodes
     * smtp.gmail.com:465). The form fields are now `smtp_username` +
     * `smtp_password` — same columns the SMTP path uses, already in
     * $fillable, already 'encrypted'-cast (smtp_password). No additional
     * cast or column-add needed for Gmail.
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
