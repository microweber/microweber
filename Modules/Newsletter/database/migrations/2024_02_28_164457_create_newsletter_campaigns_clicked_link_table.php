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
        if (!Schema::hasTable('newsletter_campaigns_clicked_link')) {
            Schema::create('newsletter_campaigns_clicked_link', function (Blueprint $table) {
                $table->id();
                $table->integer('campaign_id')->nullable();
                $table->string('email')->nullable();
                $table->string('ip')->nullable();
                $table->string('user_agent')->nullable();
                $table->text('link')->nullable();
                $table->timestamps();
                
                // Add index for better query performance
                $table->index(['campaign_id', 'email']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_campaigns_clicked_link');
    }
};
