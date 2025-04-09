<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('categories_items')) {
            return;
        }

        Schema::create('categories_items', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->nullable();
            $table->string('rel_type')->nullable();
            $table->integer('rel_id')->nullable();
            $table->timestamps();


        });

        try {

            Schema::table('categories_items', function (Blueprint $table) {


                $table->unique(['rel_id', 'parent_id']);


            });
        } catch (\Exception $e) {
            // do nothing
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories_items');
    }
};
