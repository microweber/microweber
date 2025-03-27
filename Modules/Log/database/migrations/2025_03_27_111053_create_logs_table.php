<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->string('level')->nullable();
            $table->text('message')->nullable();
            $table->text('context')->nullable();
            $table->string('channel')->default('default');
            $table->timestamp('logged_at')->useCurrent();
            $table->timestamps();
            $table->index('level');
            $table->index('channel');
            $table->index('logged_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
