<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if(Schema::hasTable('backups')) {
            return;
        }

        Schema::create('backups', function (Blueprint $table) {
            $table->id();
            $table->string('filename')->nullable();
            $table->string('filepath')->nullable();
            $table->integer('size')->nullable();
            $table->string('session_id')->nullable();
            $table->integer('progress')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('backups');
    }
};
