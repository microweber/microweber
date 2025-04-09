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
        if (!Schema::hasTable('newsletter_campaigns_pixel')) {
            Schema::create('newsletter_campaigns_pixel', function (Blueprint $table) {
                $table->id();
                $table->integer('campaign_id')->nullable();
                $table->string('email')->nullable();
                $table->string('ip')->nullable();
                $table->string('user_agent')->nullable();
                $table->timestamps();

            });


            try {
                Schema::create('newsletter_campaigns_pixel', function (Blueprint $table) {
                    $table->index('campaign_id');
                    $table->index('email');
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
        Schema::dropIfExists('newsletter_campaigns_pixel');
    }
};
