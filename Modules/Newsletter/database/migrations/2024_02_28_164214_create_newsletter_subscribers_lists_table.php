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
                $table->integer('subscriber_id')->nullable();
                $table->integer('list_id')->nullable();;
                $table->timestamps();

            });


            try {
                Schema::create('newsletter_subscribers_lists', function (Blueprint $table) {
                    $table->index('subscriber_id');
                    $table->index('list_id');
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
        Schema::dropIfExists('newsletter_subscribers_lists');
    }
};
