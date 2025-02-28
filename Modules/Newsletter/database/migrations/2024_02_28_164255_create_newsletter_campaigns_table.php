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
        if (!Schema::hasTable('newsletter_campaigns')) {
            Schema::create('newsletter_campaigns', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('subject')->nullable();
                $table->string('recipients_from')->nullable();
                $table->string('delivery_type')->nullable();
                $table->string('from_name')->nullable();
                $table->integer('email_template_id')->nullable();
                $table->integer('list_id')->nullable();
                $table->integer('sender_account_id')->nullable();
                $table->integer('sending_limit_per_day')->nullable();
                $table->boolean('is_scheduled')->nullable()->default(false);
                $table->dateTime('scheduled_at')->nullable();
                $table->string('scheduled_timezone')->nullable();
                $table->boolean('is_done')->nullable()->default(false);
                $table->string('status')->nullable();
                $table->text('status_log')->nullable();
                $table->string('email_content_type')->nullable();
                $table->longText('email_content_html')->nullable();
                $table->text('email_attached_files')->nullable();
                $table->boolean('enable_email_attachments')->nullable()->default(false);
                $table->integer('delay_between_sending_emails')->nullable();
                $table->string('jobs_batch_id')->nullable();
                $table->integer('jobs_progress')->nullable();
                $table->integer('total_jobs')->nullable();
                $table->integer('completed_jobs')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_campaigns');
    }
};
