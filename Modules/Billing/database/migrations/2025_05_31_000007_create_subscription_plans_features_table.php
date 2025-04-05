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
        Schema::create('subscription_plans_features', function (Blueprint $table) {
            $table->id();
            $table->integer('subscription_plan_id')->nullable();
            $table->string('key')->nullable();
            $table->string('value')->nullable();
            $table->string('position')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans_features');
    }
};
