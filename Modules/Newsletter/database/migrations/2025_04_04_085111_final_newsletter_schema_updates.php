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
            $table->foreignId('list_id')->nullable()->after('id');
        });

        Schema::table('newsletter_campaigns', function (Blueprint $table) {
            $table->text('content')->nullable()->after('subject');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->dropColumn('list_id');
        });

        Schema::table('newsletter_campaigns', function (Blueprint $table) {
            $table->dropColumn('content');
        });
    }
};
