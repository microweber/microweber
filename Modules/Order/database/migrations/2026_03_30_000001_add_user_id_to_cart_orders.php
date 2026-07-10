<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('cart_orders')) { return; }

        Schema::table('cart_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('created_by');
        });
    }

    public function down()
    {
        Schema::table('cart_orders', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
};
