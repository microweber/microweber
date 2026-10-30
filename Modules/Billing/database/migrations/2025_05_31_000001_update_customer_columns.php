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



        if (!Schema::hasTable('customers')) { return; }




        Schema::table('customers', function (Blueprint $table) {

            if(!Schema::hasColumn('customers', 'stripe_id')) {
                $table->string('stripe_id')->nullable()->index();
            }
            if(!Schema::hasColumn('customers', 'pm_type')) {
                $table->string('pm_type')->nullable();
            }

            if(!Schema::hasColumn('customers', 'status')) {
                $table->string('status')->nullable();
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
        if (!Schema::hasTable('customers')) {
            return;
        }

        // Drop exactly what up() added, guarded (up() adds conditionally, and
        // 'status' was previously missing from the drop list).
        $columns = array_values(array_filter(
            ['stripe_id', 'pm_type', 'status', 'pm_last_four', 'trial_ends_at'],
            static fn (string $column): bool => Schema::hasColumn('customers', $column)
        ));

        if ($columns !== []) {
            Schema::table('customers', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }
};
