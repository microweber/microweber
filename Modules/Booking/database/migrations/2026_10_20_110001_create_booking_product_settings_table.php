<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('booking_product_settings')) {
            return;
        }

        // Products are Content rows (Product extends Content), but module
        // migrations run in scan (alphabetical) order, which places "Booking"
        // BEFORE "Content" — so the `content` table may not exist yet. A hard
        // constrained() FK here would throw SQLSTATE 1824 and abort the install's
        // whole module-reload loop, leaving every later module (e.g.
        // WordPressMigration) un-enabled. So add the DB-level FK only when
        // `content` is already present; otherwise keep product_id as a plain
        // indexed column (matching how Content/Menu/SiteStats reference content).
        $contentExists = Schema::hasTable('content');

        Schema::create('booking_product_settings', function (Blueprint $table) use ($contentExists) {
            $table->id();

            if ($contentExists) {
                $table->foreignId('product_id')->constrained('content')->onDelete('cascade');
            } else {
                $table->unsignedBigInteger('product_id');
            }

            // 'date_range'  → nightly stays (check-in / check-out by day)
            // 'time_slot'   → time-based services (a start/end datetime on a day)
            $table->string('booking_type')->default('date_range');

            // How many identical units can be booked at once (e.g. 3 rooms).
            $table->unsignedInteger('capacity')->default(1);

            // 'per_night' | 'per_hour' | 'per_slot' | 'flat'. The amount is
            // unit_price when set, otherwise the product's own price.
            $table->string('price_unit')->default('per_night');
            $table->decimal('unit_price', 15, 4)->nullable();

            // Duration bounds, expressed in the type's natural unit:
            // date_range → nights; time_slot → minutes.
            $table->unsignedInteger('min_duration')->nullable();
            $table->unsignedInteger('max_duration')->nullable();

            // time_slot only: fixed slot length + the daily open/close window.
            $table->unsignedInteger('slot_minutes')->nullable();
            $table->time('opens_at')->nullable();
            $table->time('closes_at')->nullable();

            // Turnaround/cleanup buffer applied around each booking (minutes).
            $table->unsignedInteger('buffer_minutes')->default(0);

            // date_range only: default clock times stamped on the chosen days.
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('product_id');
            $table->index(['product_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_product_settings');
    }
};
