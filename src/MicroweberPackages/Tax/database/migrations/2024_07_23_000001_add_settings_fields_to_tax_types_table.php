<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSettingsFieldToTaxTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tax_types', function (Blueprint $table) {
            //has field
            if (!Schema::hasColumn('tax_types', 'settings')) {
                $table->text('settings')->nullable();
            }
        });
    }


}
