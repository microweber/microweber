<?php

use App\Models\Shop\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->string('rel_id')->nullable();

            $table->string('rel_type')->nullable();

            $table->string('payment_provider_id')->nullable();

            $table->string('payment_provider_reference_id')->nullable();

            $table->decimal('amount')->nullable();

            $table->string('currency')->nullable();

            $table->string('status')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
