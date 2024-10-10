<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateTranslationTextsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('translation_texts')) {
            return;
        }


        Schema::create('translation_texts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('translation_key_id');
            $table->text('translation_text');
            $table->string('translation_locale');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('translation_texts');
    }
}
