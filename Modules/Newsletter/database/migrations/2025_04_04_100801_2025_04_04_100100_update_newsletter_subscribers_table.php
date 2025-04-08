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

            if(!Schema::hasColumn('newsletter_subscribers', 'status')) {
                $table->string('status')->default('active');
            }
            if(!Schema::hasColumn('newsletter_subscribers', 'subscribed_at')) {
                $table->timestamp('subscribed_at')->nullable();
            }
            if(!Schema::hasColumn('newsletter_subscribers', 'unsubscribed_at')) {
                $table->timestamp('unsubscribed_at')->nullable();
            }
            if(!Schema::hasColumn('newsletter_subscribers', 'list_id')) {
                $table->foreignId('list_id')->nullable();
            }
            if(!Schema::hasColumn('newsletter_subscribers', 'confirmation_code')) {
                $table->string('confirmation_code')->nullable();
            }



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->dropColumn(['status', 'subscribed_at', 'unsubscribed_at', 'list_id']);
            $table->dropForeign(['list_id']);
            $table->dropUnique('subscriber_email_unique');
        });
    }
};
