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
        if (!Schema::hasTable('rating')) {
            Schema::create('rating', function (Blueprint $table) {
                $table->id();
                $table->string('rel_type');
                $table->string('rel_id');
                $table->integer('rating');
                $table->text('comment')->nullable();
                $table->string('session_id')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('edited_by')->nullable();
                $table->timestamps();

                $table->index(['rel_type', 'rel_id']);
                $table->index('session_id');
                $table->index('created_by');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rating');
    }
};
