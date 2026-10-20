<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('bookings')) {
            return;
        }

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // The purchase this booking belongs to. Indexed (not FK-constrained)
            // to stay decoupled from the Order module's table lifecycle.
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();

            $table->string('booking_type')->default('date_range');

            // The reserved interval as a half-open range [starts_at, ends_at):
            // date_range → check-in 00:00 .. check-out 00:00 (checkout day free);
            // time_slot  → the slot's start .. end datetime.
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');

            // How many units this booking consumes (default 1).
            $table->unsignedInteger('units')->default(1);
            $table->unsignedInteger('guests')->nullable();

            // pending  = placed, not yet paid (does NOT consume availability)
            // confirmed = paid (consumes availability)
            // checked_in / checked_out = operational states (still consume)
            // cancelled = released
            $table->string('status')->default('pending');

            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('customer_email')->nullable();

            // Price snapshot for this booking line.
            $table->decimal('price', 15, 4)->nullable();
            $table->string('currency', 8)->nullable();

            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('checked_out_at')->nullable();

            // When the buyer was emailed their booking confirmation (idempotency).
            $table->timestamp('notified_at')->nullable();

            $table->timestamps();

            $table->index('order_id');
            $table->index('customer_id');
            $table->index('customer_email');
            $table->index('status');
            // The hot path: overlap lookups for a product's active bookings.
            $table->index(['product_id', 'status', 'starts_at', 'ends_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
