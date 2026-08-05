<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('failed_jobs')) {

            Schema::table('failed_jobs', function (Blueprint $table) {

                if (!Schema::hasColumn('failed_jobs', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
            });
        }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('failed_jobs') && Schema::hasColumn('failed_jobs', 'created_at')) {
            Schema::table('failed_jobs', function (Blueprint $table) {
                $table->dropColumn('created_at');
            });
        }
    }
};
