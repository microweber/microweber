<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMultilanguageTranslations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('multilanguage_translations')) {
            Schema::create('multilanguage_translations', function (Blueprint $table) {
                $table->increments('id');

                $table->string('rel_id');
                $table->string('rel_type');

                $table->string('field_name');
                $table->text('field_value')->nullable();

                $table->string('locale');

                $table->timestamps();
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
        Schema::dropIfExists('multilanguage_translations');
    }
}
