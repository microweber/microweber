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
        Schema::table('forms_data', function (Blueprint $table) {

            if (Schema::hasColumn('forms_data', 'is_read')) {
                return;
            }

            $table->integer('is_read')->nullable();
        });
    }
};
