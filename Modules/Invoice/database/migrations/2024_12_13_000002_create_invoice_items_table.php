<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('invoice_items')) {
            return;
        }

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->integer('invoice_id')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('tax', 10, 2)->nullable();

            $table->integer('quantity')->nullable();
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
