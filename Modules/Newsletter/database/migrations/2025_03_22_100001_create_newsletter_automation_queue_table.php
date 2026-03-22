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
        if (!Schema::hasTable('newsletter_automation_queue')) {
            Schema::create('newsletter_automation_queue', function (Blueprint $table) {
                $table->id();
                $table->foreignId('campaign_id')->constrained('newsletter_campaigns')->onDelete('cascade');
                $table->foreignId('subscriber_id')->nullable()->constrained('newsletter_subscribers')->onDelete('cascade');
                $table->string('email');
                $table->string('trigger_event');
                $table->json('event_data')->nullable()->comment('Cart data, order data, etc.');
                $table->timestamp('scheduled_at');
                $table->timestamp('sent_at')->nullable();
                $table->string('status')->default('pending')->comment('pending, sent, failed, canceled');
                $table->text('error_message')->nullable();
                $table->timestamps();

                $table->index(['status', 'scheduled_at']);
                $table->index(['trigger_event', 'status']);
                $table->index('email');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_automation_queue');
    }
};
