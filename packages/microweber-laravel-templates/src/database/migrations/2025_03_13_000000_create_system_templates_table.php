<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('alias')->nullable();
            $table->text('description')->nullable();
            $table->string('path')->nullable();
            $table->string('version')->nullable();
            $table->integer('type')->nullable();
            $table->integer('priority')->nullable();
            $table->integer('sort')->nullable();
            $table->boolean('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_templates');
    }
};
