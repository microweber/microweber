<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class OfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*	'offers' => array(
		'id' => 'integer',
		'product_id' => 'integer',
		'price_id' => 'integer',
	//	'price_key' => 'string',
		'offer_price' => 'float',
		'created_at' => 'datetime',
		'updated_at' => 'datetime',
		'expires_at' => 'datetime',
		'created_by' => 'integer',
		'edited_by' => 'integer',
		'is_active' => 'integer',
	)*/
        if (!Schema::hasTable('offers')) {
            Schema::create('offers', function (Blueprint $table) {
                $table->increments('id')->index();
            });
        }

        if (Schema::hasTable('offers')) {
            if(!Schema::hasColumn('offers', 'product_id')) {
                Schema::table('offers', function (Blueprint $table) {
                    $table->integer('product_id')->nullable();
                });
            }
            if(!Schema::hasColumn('offers', 'price_id')) {
                Schema::table('offers', function (Blueprint $table) {
                    $table->integer('price_id')->nullable();
                });
            }
            if(!Schema::hasColumn('offers', 'offer_price')) {
                Schema::table('offers', function (Blueprint $table) {
                    $table->float('offer_price')->nullable();
                });
            }
            if(!Schema::hasColumn('offers', 'expires_at')) {
                Schema::table('offers', function (Blueprint $table) {
                    $table->datetime('expires_at')->nullable();
                });
            }
            if(!Schema::hasColumn('offers', 'created_by')) {
                Schema::table('offers', function (Blueprint $table) {
                    $table->integer('created_by')->nullable();
                });
            }
            if(!Schema::hasColumn('offers', 'edited_by')) {
                Schema::table('offers', function (Blueprint $table) {
                    $table->integer('edited_by')->nullable();
                });
            }
            if(!Schema::hasColumn('offers', 'is_active')) {
                Schema::table('offers', function (Blueprint $table) {
                    $table->integer('is_active')->nullable();
                });
            }
            if(!Schema::hasColumn('offers', 'created_at')) {
                Schema::table('offers', function (Blueprint $table) {
                    $table->datetime('created_at')->nullable();
                });
            }
            if(!Schema::hasColumn('offers', 'updated_at')) {
                Schema::table('offers', function (Blueprint $table) {
                    $table->datetime('updated_at')->nullable();
                });
            }

        }
    }
}
