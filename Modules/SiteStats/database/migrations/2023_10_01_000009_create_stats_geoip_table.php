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
                $table->string('country_code')->nullable();
                $table->string('country_name')->nullable();
                $table->string('region')->nullable();
                $table->string('city')->nullable();
                $table->string('latitude')->nullable();
                $table->string('longitude')->nullable();
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
