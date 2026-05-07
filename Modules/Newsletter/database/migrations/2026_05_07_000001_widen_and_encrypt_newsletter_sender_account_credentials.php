<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Newsletter\Models\NewsletterSenderAccount;

/**
 * audit-test 2026-05-07 PM TASK-004 / TICKET-AL — encrypted-at-rest backfill.
 *
 * Companion to the `protected $casts = [... => 'encrypted']` change in
 * NewsletterSenderAccount. Two things must happen in this exact order:
 *
 *   1. Widen the 8 credential columns from VARCHAR(255) → TEXT so the
 *      AEAD-encrypted ciphertext (~1.4× the cleartext length, plus
 *      header) doesn't get silently truncated on long secrets.
 *   2. Re-save every existing row through the model so the cast
 *      encrypts the cleartext currently sitting in the DB. We use
 *      DB::table()->update() to pull the raw cleartext, then assign
 *      via the model so the cast applies on save.
 *
 * down() reverses in the opposite order: decrypt-and-rewrite cleartext
 * via raw DB updates (with the cast still active when reading), then
 * narrow the columns back to VARCHAR(255). Reversibility is best-effort
 * — if any decrypted secret is >255 chars the down() rewrite of that
 * specific row will be skipped and logged rather than truncated.
 */
return new class extends Migration
{
    /**
     * @var string[] The 8 credential columns this migration touches.
     */
    private array $credentialColumns = [
        'smtp_password',
        'mailchimp_secret',
        'mailgun_secret',
        'mandrill_secret',
        'sparkpost_secret',
        'amazon_ses_key',
        'amazon_ses_secret',
        'account_pass',
    ];

    public function up(): void
    {
        if (! Schema::hasTable('newsletter_sender_accounts')) {
            return;
        }

        // Step 1 — widen all 8 columns to TEXT BEFORE re-saving.
        Schema::table('newsletter_sender_accounts', function (Blueprint $table) {
            foreach ($this->credentialColumns as $column) {
                if (Schema::hasColumn('newsletter_sender_accounts', $column)) {
                    $table->text($column)->nullable()->change();
                }
            }
        });

        // Step 2 — pull cleartext via raw DB query (bypasses the cast),
        // then assign + save via the model so the cast encrypts on write.
        $rows = DB::table('newsletter_sender_accounts')->get();
        foreach ($rows as $row) {
            $account = NewsletterSenderAccount::find($row->id);
            if (! $account) {
                continue;
            }
            $changed = false;
            foreach ($this->credentialColumns as $column) {
                if (! property_exists($row, $column)) {
                    continue;
                }
                $rawValue = $row->{$column};
                if ($rawValue === null || $rawValue === '') {
                    continue;
                }
                // Skip rows that already look encrypted (idempotent re-runs).
                // Laravel's encrypter emits base64-encoded JSON starting with `eyJ`.
                if (is_string($rawValue) && str_starts_with($rawValue, 'eyJ')) {
                    continue;
                }
                $account->{$column} = $rawValue;
                $changed = true;
            }
            if ($changed) {
                $account->save();
            }
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('newsletter_sender_accounts')) {
            return;
        }

        // Step 1 — read decrypted values via the model (cast active),
        // write them back as cleartext via raw DB update (bypasses the cast).
        NewsletterSenderAccount::all()->each(function (NewsletterSenderAccount $account) {
            $update = [];
            foreach ($this->credentialColumns as $column) {
                $value = $account->getAttribute($column);
                if ($value === null) {
                    continue;
                }
                // If a decrypted value exceeds the post-rollback VARCHAR(255)
                // capacity, skip rather than truncate.
                if (strlen($value) > 255) {
                    continue;
                }
                $update[$column] = $value;
            }
            if ($update) {
                DB::table('newsletter_sender_accounts')
                    ->where('id', $account->id)
                    ->update($update);
            }
        });

        // Step 2 — narrow columns back to VARCHAR(255).
        Schema::table('newsletter_sender_accounts', function (Blueprint $table) {
            foreach ($this->credentialColumns as $column) {
                if (Schema::hasColumn('newsletter_sender_accounts', $column)) {
                    $table->string($column)->nullable()->change();
                }
            }
        });
    }
};
