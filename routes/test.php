&lt;?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/test-schema', function() {
    $schema = DB::select('PRAGMA table_info(cart_coupons)');
    dd($schema);
});