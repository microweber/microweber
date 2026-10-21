<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('vendors')) {
            return;
        }

        Schema::create('vendors', function (Blueprint $table) {
            $table->id();

            // The user who owns this vendor shop. One vendor profile per user.
            // Indexed + unique (not FK-constrained) to stay decoupled from the
            // User module's table lifecycle, matching the Booking module's
            // order_id/customer_id convention.
            $table->unsignedBigInteger('user_id');
            $table->unique('user_id');

            // Public storefront identity.
            $table->string('shop_name')->nullable();
            $table->string('slug')->nullable();
            $table->unique('slug');

            $table->text('description')->nullable();

            // Media URLs (stored as plain strings — same convention as
            // option/template image fields; no media relation needed here).
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();

            // Optional public contact / links shown on the vendor page.
            $table->string('contact_email')->nullable();
            $table->string('website')->nullable();

            // Moderation gate. A vendor is only listed + routable publicly
            // when approved. Admin marks a user as vendor (assigns the role);
            // the profile row is auto-created approved by default, but this
            // column keeps a future moderation toggle cheap.
            $table->boolean('is_approved')->default(true);

            $table->timestamps();

            $table->index('is_approved');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
