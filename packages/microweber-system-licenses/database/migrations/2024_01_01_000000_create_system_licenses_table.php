<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('system_licenses')) {
            return;
        }

        Schema::create('system_licenses', function (Blueprint $table) {
            $table->id();
            $table->text('rel_type')->nullable();
            $table->text('rel_id')->nullable();
            $table->text('local_key')->nullable();
            $table->text('local_key_hash')->nullable();
            $table->text('registered_name')->nullable();
            $table->text('company_name')->nullable();
            $table->text('domains')->nullable();
            $table->text('status')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('service_id')->nullable();
            $table->text('billing_cycle')->nullable();
            $table->dateTime('reg_on')->nullable();
            $table->dateTime('due_on')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('edited_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_licenses');
    }
};