<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {

        if (Schema::hasTable('invoices')) {
            return;
        }


        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->date('invoice_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('invoice_number')->unique()->nullable();
            $table->string('reference_number')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('company_id')->nullable()->default(0);
            $table->string('status')->nullable();
            $table->string('paid_status')->nullable();
            $table->integer('sub_total')->nullable();
            $table->integer('discount_val')->nullable()->default(0);
            $table->integer('total')->nullable();
            $table->integer('due_amount')->nullable();
            $table->decimal('tax', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->string('unique_hash', 60)->nullable();
            $table->integer('invoice_template_id')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
