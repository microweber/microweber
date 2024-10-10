<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateTranslationKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('translation_keys')) {
            return;
        }


        Schema::create('translation_keys', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_bin';

            $table->bigIncrements('id');
            $table->string('translation_namespace')->nullable();
            $table->string('translation_group');
            $table->index('translation_group');
            $table->text('translation_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('translation_keys');
    }
}
