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
        if (Schema::hasTable('exchange_rates')) {
            return;
        }

        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->string('from_currency', 3)->index();
            $table->string('to_currency', 3)->index();
            $table->decimal('rate', 18, 8);
            $table->decimal('inverse_rate', 18, 8)->nullable();
            $table->string('source', 50)->default('manual');
            $table->timestamp('last_updated')->useCurrent();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            $table->unique(['from_currency', 'to_currency']);
            $table->index(['from_currency', 'to_currency', 'is_active']);
            $table->index(['last_updated']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_rates');
    }
};
