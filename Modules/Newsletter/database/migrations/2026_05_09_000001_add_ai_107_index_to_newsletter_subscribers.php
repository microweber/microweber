<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * AI-107 / TICKET-BH (cycle-102 2026-05-09): compound index on the
 * Newsletter subscribers email-lookup path.
 *
 * The brief asked for `newsletter_subscribers(email, confirmed_at)`.
 * The actual schema (see 2024_02_28_164053_create_newsletter_subscribers_table.php
 * + 2025_04_04_100801_2025_04_04_100100_update_newsletter_subscribers_table.php)
 * uses `is_subscribed` (boolean) + `subscribed_at` (timestamp). There
 * is no `confirmed_at` column. Mapping the brief's intent to the actual
 * schema: compound index on `(email, is_subscribed)`.
 *
 * Why it matters: the Subscribe / Unsubscribe / coupon-rate-limit
 * lookups all filter "has this email confirmed their subscription?" —
 * without the compound index that's a full scan over every email row.
 */
return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('newsletter_subscribers')) {
            return;
        }
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            if (!Schema::hasIndex('newsletter_subscribers', 'newsletter_subscribers_email_is_subscribed_index')) {
                // (email, is_subscribed) — the "is this email an
                // active subscriber?" lookup. Schema-mapped from the
                // brief's `(email, confirmed_at)` (see migration
                // doc-comment for the rationale).
                $table->index(['email', 'is_subscribed'], 'newsletter_subscribers_email_is_subscribed_index');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('newsletter_subscribers')) {
            return;
        }
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            if (Schema::hasIndex('newsletter_subscribers', 'newsletter_subscribers_email_is_subscribed_index')) {
                $table->dropIndex('newsletter_subscribers_email_is_subscribed_index');
            }
        });
    }
};
