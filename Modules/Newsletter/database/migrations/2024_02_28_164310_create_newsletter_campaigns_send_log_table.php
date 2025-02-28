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
        if (!Schema::hasTable('newsletter_campaigns_send_log')) {
            Schema::create('newsletter_campaigns_send_log', function (Blueprint $table) {
                $table->id();
                $table->integer('campaign_id')->nullable();
                $table->integer('subscriber_id')->nullable();
                $table->boolean('is_sent')->nullable()->default(false);
                $table->timestamps();
                
                // Add index for better query performance
                $table->index(['campaign_id', 'subscriber_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_campaigns_send_log');
    }
};
