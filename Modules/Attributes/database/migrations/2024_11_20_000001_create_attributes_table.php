<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->text('attribute_name')->nullable();
            $table->longText('attribute_value')->nullable();
            $table->string('rel_type')->nullable();
            $table->string('rel_id')->nullable();
            $table->string('attribute_type')->nullable();
            $table->string('session_id')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('edited_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};
