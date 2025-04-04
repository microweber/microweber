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
        Schema::table('newsletter_lists', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->boolean('is_public')->default(true)->after('description');
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
    }
};
