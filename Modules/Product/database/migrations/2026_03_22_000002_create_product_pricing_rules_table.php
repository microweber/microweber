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
        Schema::create('product_pricing_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique()->index();
            $table->text('description')->nullable();
            
            // Product scope
            $table->json('product_ids')->nullable()->comment('Array of product IDs or "all"');
            $table->json('excluded_product_ids')->nullable()->comment('Array of excluded product IDs');
            $table->json('category_ids')->nullable()->comment('Array of category IDs');
            $table->json('excluded_category_ids')->nullable()->comment('Array of excluded category IDs');
            
            // Customer scope
            $table->json('customer_group_ids')->nullable()->comment('Array of customer group IDs');
            $table->json('customer_ids')->nullable()->comment('Array of specific customer IDs');
            $table->boolean('is_public')->default(true)->comment('Available to all customers if true');
            
            // Pricing rule type
            $table->enum('rule_type', [
                'bulk_quantity',           // Price per unit based on quantity tiers
                'bulk_amount',             // Price based on total amount spent
                'customer_specific',       // Special pricing for specific customers
                'customer_group',          // Special pricing for customer groups
                'variant_override',        // Override variant pricing
                'bundle_discount',         // Discount when buying multiple items
            ])->default('bulk_quantity');
            
            // Rule configuration
            $table->json('tiers')->nullable()->comment('Quantity or amount tiers with pricing');
            
            // Pricing configuration
            $table->enum('price_type', [
                'fixed',                   // Fixed price per unit
                'percentage_discount',   // Percentage off base price
                'fixed_discount',          // Fixed amount off base price
            ])->default('percentage_discount');
            
            // Priority and stacking
            $table->integer('priority')->default(0)->comment('Higher priority rules take precedence');
            $table->boolean('is_stackable')->default(false)->comment('Can stack with other pricing rules');
            $table->json('cannot_stack_with')->nullable()->comment('Array of rule IDs this rule cannot stack with');
            
            // Active dates
            $table->dateTime('valid_from')->nullable();
            $table->dateTime('valid_to')->nullable();
            
            // Limits
            $table->integer('max_usage_count')->nullable()->comment('Maximum total uses, null for unlimited');
            $table->integer('usage_count')->default(0)->comment('Current usage count');
            $table->integer('max_usage_per_customer')->nullable()->comment('Max uses per customer, null for unlimited');
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->timestamp('disabled_at')->nullable();
            
            // Metadata
            $table->json('metadata')->nullable()->comment('Additional rule metadata');
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['is_active', 'valid_from', 'valid_to'], 'pricing_rule_active_dates');
            $table->index(['rule_type', 'is_active'], 'pricing_rule_type_active');
            $table->index(['priority', 'is_active'], 'pricing_rule_priority');
        });
        
        // Create pivot table for customer-specific pricing
        Schema::create('product_customer_pricing', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->decimal('compare_price', 12, 2)->nullable();
            $table->decimal('minimum_quantity', 10, 2)->default(1);
            $table->dateTime('valid_from')->nullable();
            $table->dateTime('valid_to')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->unique(['product_id', 'user_id'], 'customer_product_unique');
            $table->index(['user_id', 'is_active'], 'customer_pricing_active');
            $table->index(['product_id', 'is_active'], 'product_pricing_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_customer_pricing');
        Schema::dropIfExists('product_pricing_rules');
    }
};
