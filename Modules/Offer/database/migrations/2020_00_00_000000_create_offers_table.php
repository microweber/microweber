<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (Schema::hasTable('offers')) {
            return;
        }

        if (!Schema::hasTable('offers')) {
            Schema::create('offers', function (Blueprint $table) {
                $table->increments('id');
            });


        }

        if (Schema::hasTable('offers')) {
            if (!Schema::hasColumn('offers', 'product_id')) {
                Schema::table('offers', function (Blueprint $table) {
                    $table->integer('product_id')->nullable();
                });
            }
            if (!Schema::hasColumn('offers', 'price_id')) {
                Schema::table('offers', function (Blueprint $table) {
                    $table->integer('price_id')->nullable();
                });
            }
            if (!Schema::hasColumn('offers', 'offer_price')) {
                Schema::table('offers', function (Blueprint $table) {
                    $table->float('offer_price')->nullable();
                });
            }
            if (!Schema::hasColumn('offers', 'expires_at')) {
                Schema::table('offers', function (Blueprint $table) {
                    $table->datetime('expires_at')->nullable();
                });
            }
            if (!Schema::hasColumn('offers', 'created_by')) {
                Schema::table('offers', function (Blueprint $table) {
                    $table->integer('created_by')->nullable();
                });
            }
            if (!Schema::hasColumn('offers', 'edited_by')) {
                Schema::table('offers', function (Blueprint $table) {
                    $table->integer('edited_by')->nullable();
                });
            }
            if (!Schema::hasColumn('offers', 'is_active')) {
                Schema::table('offers', function (Blueprint $table) {
                    $table->integer('is_active')->nullable();
                });
            }
            if (!Schema::hasColumn('offers', 'created_at')) {
                Schema::table('offers', function (Blueprint $table) {
                    $table->datetime('created_at')->nullable();
                });
            }
            if (!Schema::hasColumn('offers', 'updated_at')) {
                Schema::table('offers', function (Blueprint $table) {
                    $table->datetime('updated_at')->nullable();
                });
            }

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offers');
    }
};
