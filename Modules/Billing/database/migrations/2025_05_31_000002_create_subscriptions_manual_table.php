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
        Schema::create('subscriptions_manual', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->integer('subscription_plan_id')->nullable();
            $table->tinyInteger('auto_activate_free_trial_after_date')->nullable();
            $table->dateTime('activate_free_trial_after_date');
            $table->timestamps();
            $table->index(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions_manual');
    }
};
