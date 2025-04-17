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

        if(Schema::hasTable('subscription_plans')) {
            return;
        }


        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('sku')->nullable();
            $table->longText('description')->nullable();
            $table->string('type')->nullable();
            $table->integer('subscription_plan_group_id')->nullable();
            $table->json('plan_data')->nullable();
            $table->longText('price')->nullable();
            $table->longText('discount_price')->nullable();
            $table->longText('save_price')->nullable();
            $table->longText('save_price_badge')->nullable();
            $table->longText('auto_apply_coupon_code')->nullable();
            $table->longText('billing_interval')->nullable();
            $table->integer('trial_days')->nullable();
            $table->string('default_interval')->nullable();
            $table->string('remote_provider')->nullable();
            $table->string('remote_provider_id')->nullable();
            $table->string('remote_provider_price_id')->nullable();
            $table->integer('alternative_annual_plan_id')->nullable();
            $table->integer('sort_order')->nullable();
            $table->integer('is_hidden')->nullable();

        });


        try {
            Schema::create('subscription_plans', function (Blueprint $table) {
                $table->unique('sku');
            });
        } catch (\Exception $e) {
            // Handle the exception if needed
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
