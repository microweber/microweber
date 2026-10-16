<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('digital_product_downloads')) {
            return;
        }

        Schema::create('digital_product_downloads', function (Blueprint $table) {
            $table->id();

            // The purchase this grant belongs to. Indexed (not FK-constrained) to
            // stay decoupled from the Order module's table lifecycle.
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();

            // The specific file granted. Cascades if the file is removed.
            $table->foreignId('downloadable_file_id')
                ->constrained('product_downloadable_files')
                ->onDelete('cascade');

            // Who it was granted to (informational / for the customer downloads list).
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('customer_email')->nullable();

            // The secret delivery key: /download/{token}.
            $table->string('token', 64)->unique();

            $table->unsignedInteger('downloads_count')->default(0);

            // Snapshot of the limit/expiry at grant time. NULL = unlimited / never
            // (the confirmed defaults); a value lets a per-product override apply.
            $table->unsignedInteger('download_limit')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('last_downloaded_at')->nullable();

            $table->timestamps();

            $table->index('order_id');
            $table->index('product_id');
            $table->index('customer_id');
            $table->index('customer_email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('digital_product_downloads');
    }
};
