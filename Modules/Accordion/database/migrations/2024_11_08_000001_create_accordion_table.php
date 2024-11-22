<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if(Schema::hasTable('accordion')) {
            return;
        }

        Schema::create('accordion', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('icon')->nullable();
            $table->longText('content')->nullable();
            $table->integer('position')->nullable();
            $table->string('rel_type')->nullable();
            $table->string('rel_id')->nullable();
            $table->longText('settings')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accordion');
    }
};
