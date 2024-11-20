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
        if (Schema::hasTable('custom_fields_values')) {
            return;
        }

        Schema::create('custom_fields_values', function (Blueprint $table) {
            $table->id();
            $table->integer('custom_field_id')->nullable();
            $table->text('value')->nullable();
            $table->integer('price_modifier')->nullable();
            $table->integer('position')->nullable();
            $table->timestamps();

            $table->index(['custom_field_id', 'value']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_fields_values');
    }
};
