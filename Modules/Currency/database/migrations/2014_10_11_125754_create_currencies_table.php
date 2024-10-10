<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if(Schema::hasTable('currencies')) {
            return;
        }

        Schema::create('currencies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code');
            $table->string('symbol');
            $table->integer('precision');
            $table->string('thousand_separator');
            $table->string('decimal_separator');
            $table->boolean('swap_currency_symbol');
            $table->timestamps();
        });


        // Get the currencies from config/money.php
        $currencies = config('money');
        if (empty($currencies)) {
           return;
        }
        // Insert each currency into the currencies table
        foreach ($currencies as $code => $currency) {
            if(!isset($currency['name']) || !isset($currency['symbol']) || !isset($currency['precision']) || !isset($currency['thousands_separator']) || !isset($currency['decimal_mark']) || !isset($currency['symbol_first'])) {
                continue;
            }

            \Illuminate\Support\Facades\DB::table('currencies')->insert([
                'name' => $currency['name'],
                'code' => $code,
                'symbol' => $currency['symbol'],
                'precision' => $currency['precision'],
                'thousand_separator' => $currency['thousands_separator'],
                'decimal_separator' => $currency['decimal_mark'],
                'swap_currency_symbol' => !$currency['symbol_first'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
};
