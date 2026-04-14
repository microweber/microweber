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
        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity'); // index added below, separately
            });
        }

        // Add the last_activity index in a separate guarded step so that duplicate-index
        // errors (e.g. when mw_migrator and artisan migrate both run this file) do not
        // block the install.
        try {
            if (!Schema::hasIndex('sessions', 'sessions_last_activity_index')) {
                Schema::table('sessions', function (Blueprint $table) {
                    $table->index('last_activity', 'sessions_last_activity_index');
                });
            }
        } catch (\Throwable $e) {
            // Index already exists – safe to continue.
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
