<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('customer_tags')) {
            return;
        }

        Schema::create('customer_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedInteger('tag_id');
            $table->timestamp('created_at')->useCurrent();

            // Foreign key constraint for customer_id
            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('cascade');

            // Unique constraint to prevent duplicate tags per customer
            $table->unique(['customer_id', 'tag_id']);

            // Index for fast lookups
            $table->index(['tag_id', 'customer_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_tags');
    }
};
