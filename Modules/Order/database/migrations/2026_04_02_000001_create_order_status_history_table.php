<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('order_status_history')) {
            Schema::create('order_status_history', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('order_id');
                $table->string('old_status')->nullable();
                $table->string('new_status');
                $table->unsignedInteger('user_id')->nullable();
                $table->text('note')->nullable();
                $table->timestamps();

                $table->index('order_id');
                $table->index('created_at');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('order_status_history');
    }
};
