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
        Schema::table('newsletter_campaigns', function (Blueprint $table) {
            $table->timestamp('sent_at')->nullable()->after('scheduled_at');
        });

        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->foreign('list_id')->references('id')->on('newsletter_lists');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('newsletter_campaigns', function (Blueprint $table) {
            $table->dropColumn('sent_at');
        });

        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->dropForeign(['list_id']);
        });
    }
};
