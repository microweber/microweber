<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('site_options', function (Blueprint $table) {
            $table->string('option_group');
            $table->string('option_key');
            $table->text('option_value')->nullable();
        });
        
        // Add minimal required options
        DB::table('site_options')->insert([
            ['option_group' => 'white_label', 'option_key' => 'enabled', 'option_value' => '0']
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('site_options');
    }
};