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
        if (Schema::hasTable('personal_access_tokens')) {
            return;
        }


        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id('id');
             $table->string('tokenable_type', 255);
            $table->string('tokenable_id', 255);
            $table->string('name', 255);
            $table->string('token', 64)->nullable();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();


            //tryto add idnex


        });


        Schema::table('personal_access_tokens', function (Blueprint $table) {
        try {
            $table->unique(['token']);
        } catch (\Exception $e) {
            // do nothing
        }
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};
