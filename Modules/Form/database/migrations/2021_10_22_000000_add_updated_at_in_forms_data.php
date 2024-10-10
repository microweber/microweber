<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUpdatedAtInFormsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forms_data', function (Blueprint $table) {

            if(!Schema::hasColumn('forms_data', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }


        });
    }
}
