<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * AI-107 / TICKET-BD (cycle-102 2026-05-09): compound index on the
 * Cart per-session product-lookup path.
 *
 * The brief asked for `cart_items(cart_id, product_id)`. In Microweber
 * the `cart` table IS the cart-items table (one row per item), and the
 * "cart owner" identifier is `session_id` (string), with the product
 * identifier in `rel_id` (int). Mapping the brief's intent to the
 * actual schema: compound index on `(session_id, rel_id)`.
 *
 * Why it matters: `CartRepository::getCartItem(...)` and the Add-to-
 * Cart de-dup path both look up "is this product already in this
 * session's cart?" — without the compound index that's a full table
 * scan. The cycle-72 N+1 work covered eager-loading on the read side;
 * this index covers the lookup side.
 */
return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('cart')) {
            return;
        }
        Schema::table('cart', function (Blueprint $table) {
            if (!Schema::hasIndex('cart', 'cart_session_rel_id_index')) {
                // session_id (string) + rel_id (int) — the
                // "find this product in this cart" lookup.
                $table->index(['session_id', 'rel_id'], 'cart_session_rel_id_index');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('cart')) {
            return;
        }
        Schema::table('cart', function (Blueprint $table) {
            if (Schema::hasIndex('cart', 'cart_session_rel_id_index')) {
                $table->dropIndex('cart_session_rel_id_index');
            }
        });
    }
};
