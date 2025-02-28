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
        if (!Schema::hasTable('newsletter_subscribers_lists')) {
            Schema::create('newsletter_subscribers_lists', function (Blueprint $table) {
                $table->id();
                $table->integer('subscriber_id');
                $table->integer('list_id');
                $table->timestamps();
                
                // Add index for better query performance
                $table->index(['subscriber_id', 'list_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_subscribers_lists');
    }
};
