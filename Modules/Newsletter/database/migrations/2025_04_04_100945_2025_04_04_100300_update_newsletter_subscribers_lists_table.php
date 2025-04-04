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
        Schema::table('newsletter_subscribers_lists', function (Blueprint $table) {
            $table->unique(['subscriber_id', 'list_id'], 'subscriber_list_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('newsletter_subscribers_lists', function (Blueprint $table) {
            $table->dropUnique('subscriber_list_unique');
        });
    }
};
