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
        if (!Schema::hasTable('newsletter_sender_accounts')) {
            Schema::create('newsletter_sender_accounts', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('from_name')->nullable();
                $table->string('from_email')->nullable();
                $table->string('reply_email')->nullable();
                $table->string('account_type')->nullable();
                
                // Smtp settings
                $table->string('smtp_username')->nullable();
                $table->string('smtp_password')->nullable();
                $table->string('smtp_host')->nullable();
                $table->string('smtp_port')->nullable();
                
                // Mailchimp settings
                $table->string('mailchimp_secret')->nullable();
                
                // Mailgun settings
                $table->string('mailgun_domain')->nullable();
                $table->string('mailgun_secret')->nullable();
                
                // Mandrill settings
                $table->string('mandrill_secret')->nullable();
                
                // Sparkpost settings
                $table->string('sparkpost_secret')->nullable();
                
                // Amazon ses settings
                $table->string('amazon_ses_key')->nullable();
                $table->string('amazon_ses_secret')->nullable();
                $table->string('amazon_ses_region')->nullable(); // e.g. us-east
                
                $table->string('account_pass')->nullable();
                $table->boolean('is_active')->nullable()->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_sender_accounts');
    }
};
