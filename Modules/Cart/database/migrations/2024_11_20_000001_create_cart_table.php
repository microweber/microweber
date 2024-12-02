<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if(Schema::hasTable('cart')) {
            return;
        }


        Schema::create('cart', function (Blueprint $table) {
            $table->id();
            $table->longText('title')->nullable();
            $table->string('is_active')->nullable();
            $table->integer('rel_id')->nullable();
            $table->string('rel_type')->nullable();
            $table->string('order_id')->nullable();
            $table->integer('qty')->nullable();
            $table->float('price')->nullable();
            $table->string('currency')->nullable();
            $table->integer('order_completed')->nullable();
            $table->string('session_id')->nullable();
            $table->longText('other_info')->nullable();
            $table->string('skip_promo_code')->nullable();
            $table->string('item_image')->nullable();
            $table->string('link')->nullable();
            $table->longText('description')->nullable();
            $table->longText('custom_fields_data')->nullable();
            $table->longText('custom_fields_json')->nullable();
            $table->integer('created_by')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->index(['rel_type', 'rel_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart');
    }
};
