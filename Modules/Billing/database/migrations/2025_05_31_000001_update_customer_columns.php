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



        Schema::table('customers', function (Blueprint $table) {

            if(!Schema::hasColumn('customers', 'stripe_id')) {
                $table->string('stripe_id')->nullable()->index();
            }
            if(!Schema::hasColumn('customers', 'pm_type')) {
                $table->string('pm_type')->nullable();
            }

            if(!Schema::hasColumn('customers', 'pm_last_four')) {
                $table->string('pm_last_four', 4)->nullable();
            }
            if(!Schema::hasColumn('customers', 'trial_ends_at')) {
                $table->timestamp('trial_ends_at')->nullable();
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
