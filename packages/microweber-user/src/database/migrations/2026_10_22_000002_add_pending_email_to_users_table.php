<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Columns backing the verified email-change flow: a self-service email change is
 * parked here (pending_email + a hashed token) and the account keeps its current
 * email until the owner confirms the new address via the emailed link.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'pending_email')) {
                $table->string('pending_email')->nullable();
            }
            if (! Schema::hasColumn('users', 'pending_email_hash')) {
                $table->string('pending_email_hash')->nullable();
            }
            if (! Schema::hasColumn('users', 'pending_email_created_at')) {
                $table->timestamp('pending_email_created_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            foreach (['pending_email', 'pending_email_hash', 'pending_email_created_at'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
