<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Newsletter lists updates
        Schema::table('newsletter_lists', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->boolean('is_public')->default(true)->after('description');
        });

        // Subscribers updates
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->string('status')->default('active')->after('is_subscribed');
            $table->timestamp('subscribed_at')->nullable()->after('status');
            $table->timestamp('unsubscribed_at')->nullable()->after('subscribed_at');
            $table->foreignId('list_id')->nullable()->after('id');
            $table->foreign('list_id')->references('id')->on('newsletter_lists');
            $table->unique('email', 'subscriber_email_unique');
        });

        // Campaigns updates
        Schema::table('newsletter_campaigns', function (Blueprint $table) {
            $table->text('content')->nullable()->after('subject');
            $table->timestamp('sent_at')->nullable()->after('scheduled_at');
        });

        // Pivot table updates
        Schema::table('newsletter_subscribers_lists', function (Blueprint $table) {
            $table->unique(['subscriber_id', 'list_id'], 'subscriber_list_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('newsletter_lists', function (Blueprint $table) {
            $table->dropColumn(['description', 'is_public']);
        });

        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->dropColumn(['status', 'subscribed_at', 'unsubscribed_at', 'list_id']);
            $table->dropForeign(['list_id']);
            $table->dropUnique('subscriber_email_unique');
        });

        Schema::table('newsletter_campaigns', function (Blueprint $table) {
            $table->dropColumn(['content', 'sent_at']);
        });

        Schema::table('newsletter_subscribers_lists', function (Blueprint $table) {
            $table->dropUnique('subscriber_list_unique');
        });
    }
};
