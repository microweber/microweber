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

        if(!Schema::hasTable('forms')) {
            return;
        }

        Schema::table('forms', function (Blueprint $table) {

            if(!Schema::hasColumn('forms', 'module_id')) {
                $table->string('module_id')->nullable();
            }


        });
    }
};
