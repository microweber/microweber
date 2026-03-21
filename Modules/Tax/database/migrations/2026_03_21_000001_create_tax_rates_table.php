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
        if (Schema::hasTable('tax_rates')) {
            return;
        }

        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();

            // Location-based rules
            $table->string('country_code', 2)->nullable()->index();
            $table->string('state_code', 10)->nullable()->index();
            $table->string('zip_code_pattern')->nullable();
            $table->string('city')->nullable();

            // Tax calculation
            $table->enum('type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('rate', 10, 4)->default(0);
            $table->boolean('compound_tax')->default(false);

            // Priority and matching
            $table->integer('priority')->default(0)->index();
            $table->boolean('is_default')->default(false);

            // Status and validity
            $table->boolean('is_active')->default(true)->index();
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();

            // Product/category applicability
            $table->json('applies_to_products')->nullable();
            $table->json('applies_to_categories')->nullable();
            $table->json('applies_to_customer_groups')->nullable();

            $table->timestamps();

            // Composite indexes for efficient querying
            $table->index(['country_code', 'state_code', 'is_active']);
            $table->index(['country_code', 'is_active', 'priority']);
            $table->index(['is_active', 'priority']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_rates');
    }
};
