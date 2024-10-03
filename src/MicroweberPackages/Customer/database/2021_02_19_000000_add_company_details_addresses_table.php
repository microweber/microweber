<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('addresses')) {
            Schema::table('addresses', function (Blueprint $table) {
                if(!Schema::hasColumn('addresses', 'company_name')) {
                    $table->string('company_name')->nullable();
                }
                if(!Schema::hasColumn('addresses', 'company_vat')) {
                    $table->string('company_vat')->nullable();
                }
                if(!Schema::hasColumn('addresses', 'company_vat_registered')) {
                    $table->string('company_vat_registered')->nullable();
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('addresses')) {
            Schema::table('addresses', function (Blueprint $table) {

                if(Schema::hasColumn('addresses', 'company_name')) {
                    $table->dropColumn('company_name');
                }
                if(Schema::hasColumn('addresses', 'company_vat')) {
                    $table->dropColumn('company_vat');
                }
                if(Schema::hasColumn('addresses', 'company_vat_registered')) {
                    $table->dropColumn('company_vat_registered');
                }


            });
        }
    }
};
