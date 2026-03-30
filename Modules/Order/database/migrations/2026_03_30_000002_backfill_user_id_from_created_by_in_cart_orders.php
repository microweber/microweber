<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        DB::table('cart_orders')
            ->whereNull('user_id')
            ->whereNotNull('created_by')
            ->where('created_by', '>', 0)
            ->update(['user_id' => DB::raw('created_by')]);
    }

    public function down()
    {
        // Irreversible data backfill
    }
};
