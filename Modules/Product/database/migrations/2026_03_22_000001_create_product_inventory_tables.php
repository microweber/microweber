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
        // Inventory movements table - tracks all stock changes
        if (!Schema::hasTable('product_inventory_movements')) {
            Schema::create('product_inventory_movements', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('content')->onDelete('cascade');
                $table->unsignedBigInteger('variant_id')->nullable();
                $table->integer('quantity_change'); // Can be positive (restock) or negative (sale)
                $table->integer('quantity_before');
                $table->integer('quantity_after');
                $table->string('type'); // sale, restock, adjustment, reservation, return, etc.
                $table->string('reference_type')->nullable(); // Order, Cart, Adjustment, etc.
                $table->unsignedBigInteger('reference_id')->nullable();
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('user_id')->nullable(); // Who made the change
                $table->json('metadata')->nullable(); // Additional data
                $table->timestamps();

                $table->index(['product_id', 'type']);
                $table->index(['product_id', 'variant_id']);
                $table->index(['reference_type', 'reference_id']);
                $table->index(['created_at']);
                $table->index(['type', 'created_at']);
            });
        }

        // Inventory alerts table - tracks low stock alerts
        if (!Schema::hasTable('product_inventory_alerts')) {
            Schema::create('product_inventory_alerts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('content')->onDelete('cascade');
                $table->unsignedBigInteger('variant_id')->nullable();
                $table->string('alert_type'); // low_stock, out_of_stock, critical
                $table->integer('current_quantity');
                $table->integer('threshold_quantity');
                $table->boolean('is_resolved')->default(false);
                $table->timestamp('resolved_at')->nullable();
                $table->unsignedBigInteger('resolved_by')->nullable();
                $table->text('resolution_notes')->nullable();
                $table->json('notification_sent_to')->nullable(); // Array of user IDs notified
                $table->timestamps();

                $table->index(['product_id', 'is_resolved']);
                $table->index(['alert_type', 'is_resolved']);
                $table->index(['is_resolved', 'created_at']);
                $table->index(['alert_type', 'created_at']);
            });
        }

        // Stock reservations table - tracks reserved stock (cart items, pending orders)
        if (!Schema::hasTable('product_stock_reservations')) {
            Schema::create('product_stock_reservations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('content')->onDelete('cascade');
                $table->unsignedBigInteger('variant_id')->nullable();
                $table->integer('quantity');
                $table->string('reservation_type'); // cart, order, hold, etc.
                $table->string('session_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('order_id')->nullable();
                $table->timestamp('expires_at'); // When reservation expires
                $table->boolean('is_active')->default(true);
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->index(['product_id', 'variant_id', 'is_active']);
                $table->index(['reservation_type', 'is_active']);
                $table->index(['expires_at', 'is_active']);
                $table->index(['user_id', 'is_active']);
                $table->index(['order_id']);
                $table->index(['session_id']);
            });
        }

        // Add inventory fields to existing variant combinations table
        if (Schema::hasTable('product_variants_combinations')) {
            Schema::table('product_variants_combinations', function (Blueprint $table) {
                if (!Schema::hasColumn('product_variants_combinations', 'low_stock_threshold')) {
                    $table->integer('low_stock_threshold')->default(10)->after('quantity');
                }
                if (!Schema::hasColumn('product_variants_combinations', 'reorder_point')) {
                    $table->integer('reorder_point')->default(5)->after('low_stock_threshold');
                }
                if (!Schema::hasColumn('product_variants_combinations', 'reorder_quantity')) {
                    $table->integer('reorder_quantity')->default(50)->after('reorder_point');
                }
                if (!Schema::hasColumn('product_variants_combinations', 'last_stock_check')) {
                    $table->timestamp('last_stock_check')->nullable()->after('reorder_quantity');
                }
            });
        }

        // Add inventory fields to content table (for non-variant products)
        if (Schema::hasTable('content')) {
            Schema::table('content', function (Blueprint $table) {
                if (!Schema::hasColumn('content', 'low_stock_threshold')) {
                    $table->integer('low_stock_threshold')->default(10)->nullable()->after('updated_at');
                }
                if (!Schema::hasColumn('content', 'reorder_point')) {
                    $table->integer('reorder_point')->default(5)->nullable()->after('low_stock_threshold');
                }
                if (!Schema::hasColumn('content', 'reorder_quantity')) {
                    $table->integer('reorder_quantity')->default(50)->nullable()->after('reorder_point');
                }
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
        // Remove added columns first
        if (Schema::hasTable('content')) {
            Schema::table('content', function (Blueprint $table) {
                if (Schema::hasColumn('content', 'reorder_quantity')) {
                    $table->dropColumn('reorder_quantity');
                }
                if (Schema::hasColumn('content', 'reorder_point')) {
                    $table->dropColumn('reorder_point');
                }
                if (Schema::hasColumn('content', 'low_stock_threshold')) {
                    $table->dropColumn('low_stock_threshold');
                }
            });
        }

        if (Schema::hasTable('product_variants_combinations')) {
            Schema::table('product_variants_combinations', function (Blueprint $table) {
                if (Schema::hasColumn('product_variants_combinations', 'last_stock_check')) {
                    $table->dropColumn('last_stock_check');
                }
                if (Schema::hasColumn('product_variants_combinations', 'reorder_quantity')) {
                    $table->dropColumn('reorder_quantity');
                }
                if (Schema::hasColumn('product_variants_combinations', 'reorder_point')) {
                    $table->dropColumn('reorder_point');
                }
                if (Schema::hasColumn('product_variants_combinations', 'low_stock_threshold')) {
                    $table->dropColumn('low_stock_threshold');
                }
            });
        }

        Schema::dropIfExists('product_stock_reservations');
        Schema::dropIfExists('product_inventory_alerts');
        Schema::dropIfExists('product_inventory_movements');
    }
};
