<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Product variant attributes table (e.g., Size, Color, Material)
        if (!Schema::hasTable('product_variant_attributes')) {
            Schema::create('product_variant_attributes', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // e.g., "Size", "Color"
                $table->string('key')->unique(); // e.g., "size", "color" - used for lookups
                $table->text('description')->nullable();
                $table->string('type')->default('select'); // select, radio, color, button
                $table->integer('position')->default(0); // Display order
                $table->boolean('is_active')->default(true);
                $table->json('settings')->nullable(); // Additional settings per attribute type
                $table->unsignedInteger('created_by')->nullable();
                $table->unsignedInteger('edited_by')->nullable();
                $table->timestamps();

                $table->index(['key', 'is_active']);
                $table->index('position');
            });
        }

        // Product variant attribute values table (e.g., Small, Red, Cotton)
        if (!Schema::hasTable('product_variant_attribute_values')) {
            Schema::create('product_variant_attribute_values', function (Blueprint $table) {
                $table->id();
                $table->foreignId('attribute_id')->constrained('product_variant_attributes')->onDelete('cascade');
                $table->string('value'); // e.g., "Small", "Red"
                $table->string('key'); // e.g., "small", "red" - unique per attribute
                $table->text('description')->nullable();
                $table->string('color_code')->nullable(); // For color type attributes (hex code)
                $table->string('image')->nullable(); // Optional image for the value
                $table->integer('position')->default(0);
                $table->boolean('is_active')->default(true);
                $table->json('metadata')->nullable(); // Additional metadata
                $table->unsignedInteger('created_by')->nullable();
                $table->unsignedInteger('edited_by')->nullable();
                $table->timestamps();

                $table->unique(['attribute_id', 'key']);
                $table->index(['attribute_id', 'position']);
                $table->index('is_active');
            });
        }

        // Product variant combinations table
        if (!Schema::hasTable('product_variants_combinations')) {
            Schema::create('product_variants_combinations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('content')->onDelete('cascade');
                $table->string('sku')->nullable()->unique(); // Variant-specific SKU
                $table->string('barcode')->nullable()->unique(); // Variant-specific barcode
                $table->decimal('price', 10, 2)->nullable(); // Variant-specific price (optional)
                $table->decimal('compare_price', 10, 2)->nullable(); // Compare at price
                $table->decimal('cost_price', 10, 2)->nullable(); // Cost price for profit calculations
                $table->integer('quantity')->default(0); // Stock quantity
                $table->string('quantity_type')->default('limited'); // limited, unlimited
                $table->boolean('track_quantity')->default(true);
                $table->boolean('allow_backorders')->default(false);
                $table->decimal('weight', 8, 2)->nullable(); // Variant-specific weight
                $table->string('image')->nullable(); // Variant-specific image
                $table->boolean('is_default')->default(false); // Default variant
                $table->boolean('is_active')->default(true);
                $table->json('metadata')->nullable(); // Additional variant data
                $table->timestamps();

                $table->index(['product_id', 'is_active']);
                $table->index(['product_id', 'is_default']);
                $table->index('sku');
                $table->index('barcode');
            });
        }

        // Pivot table for variant combinations and their attribute values
        if (!Schema::hasTable('product_variant_combination_attributes')) {
            Schema::create('product_variant_combination_attributes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('combination_id')->constrained('product_variants_combinations')->onDelete('cascade');
                $table->foreignId('attribute_value_id');
                $table->foreign('attribute_value_id', 'pvca_attr_value_id_foreign')->references('id')->on('product_variant_attribute_values')->onDelete('cascade');
                $table->timestamps();

                $table->unique(['combination_id', 'attribute_value_id'], 'pvca_combination_attr_unique');
                $table->index('attribute_value_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_variant_combination_attributes');
        Schema::dropIfExists('product_variants_combinations');
        Schema::dropIfExists('product_variant_attribute_values');
        Schema::dropIfExists('product_variant_attributes');
    }
};
