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
        if(Schema::hasTable('oauth_personal_access_clients')) {
            return;
        }


        Schema::create('oauth_personal_access_clients', function (Blueprint $table) {
            $table->increments('id');
            //$table->integer('client_id')->index();
            $table->integer('client_id');
            $table->timestamps();

       //     $table->index('client_id', 'oauth_personal_access_clients_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oauth_personal_access_clients');
    }
};
