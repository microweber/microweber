<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('is_premium');
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->boolean('is_premium')->default(0)->after('active');
        });
    }
};