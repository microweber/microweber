<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('stats_geoip')) {
            Schema::create('stats_geoip', function (Blueprint $table) {
                $table->id();
                $table->string('country_code');
                $table->string('country_name');
                $table->string('region');
                $table->string('city');
                $table->string('latitude');
                $table->string('longitude');
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
        Schema::dropIfExists('stats_geoip');
    }
};
