<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {


        Schema::table('users', function (Blueprint $table) {

            if (!Schema::hasColumn('users', 'demo_expired')) {
                $table->string('demo_expired')->nullable();
            }
            if (!Schema::hasColumn('users', 'demo_started_at')) {
                $table->dateTime('demo_started_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'demo_started_at')) {
                $table->dateTime('demo_expired_at')->nullable();
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_id',
                'pm_type',
                'pm_last_four',
                'trial_ends_at',
            ]);
        });
    }
};
