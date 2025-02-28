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
        if (!Schema::hasTable('newsletter_lists')) {
            Schema::create('newsletter_lists', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                
                // Email template relationships
                $table->integer('success_email_template_id')->nullable();
                $table->integer('unsubscription_email_template_id')->nullable();
                $table->integer('confirmation_email_template_id')->nullable();
                
                // Sender account relationships
                $table->integer('success_sender_account_id')->nullable();
                $table->integer('unsubscription_sender_account_id')->nullable();
                $table->integer('confirmation_sender_account_id')->nullable();
                
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_lists');
    }
};
