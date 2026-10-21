<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('vendor_order_notifications')) {
            return;
        }

        // Idempotency ledger: one row per (vendor, order) that has been
        // notified. OrderWasPaid and the admin "mark paid" (OrderWasUpdated)
        // can both fire for the same order, so the listener firstOrCreate()s
        // here and only sends when the row is newly created — the vendor is
        // never double-notified for the same order.
        Schema::create('vendor_order_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('order_id');
            $table->timestamps();

            $table->unique(['vendor_id', 'order_id']);
            $table->index('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_order_notifications');
    }
};
