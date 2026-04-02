<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_refunds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('type'); // full, partial
            $table->string('reason')->nullable();
            $table->text('note')->nullable();
            $table->string('status')->default('completed'); // completed, pending, failed
            $table->unsignedBigInteger('refunded_by')->nullable();
            $table->timestamps();

            $table->index('order_id');
            $table->index('payment_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_refunds');
    }
};
