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


            });

            try {
                Schema::create('rating', function (Blueprint $table) {
                    $table->index('rel_type');
                    $table->index('rel_id');
                });
            } catch (\Exception $e) {
                // Handle the exception if needed
            }
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
