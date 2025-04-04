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
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->string('status')->default('active')->after('is_subscribed');
            $table->timestamp('subscribed_at')->nullable()->after('status');
            $table->timestamp('unsubscribed_at')->nullable()->after('subscribed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->dropColumn(['status', 'subscribed_at', 'unsubscribed_at']);
        });
    }
};
