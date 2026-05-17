<?php

use Illuminate\Support\Facades\Route;
use Modules\Shop\Http\Controllers\ShopController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| task-2026-05-17-66a21a / AI-849 — frontend /shop route registration.
|
| Pre-fix: this route was not registered (the loadRoutesFrom call at
| ShopServiceProvider:34 was commented out) and no ShopController
| existed. `/shop`, `/shop/products`, `/shop/category`, `/shop/featured`,
| `/shop/categories` all fell through to the FrontendController catch-all
| which detected "shop" as an installed module name and rendered
| Templates/Bootstrap clean.blade.php with hardcoded fixture markup at
| 200 OK with no noindex header. Stage-3 propagation defect, FIRST
| instance on the primary commerce surface (sibling lineage AI-755 →
| AI-795 → AI-837 → this).
|
| Fix: register `Route::get('shop/{path?}', ...)` mapped to ShopController
| which renders a chrome-wrapped landing per the AI-795 standing chrome-
| application checklist (extends active template, semantic chrome
| container, recovery context, 200 OK + noindex-when-empty, contract test).
|
| Wildcard `{path}` regex `.*` catches /shop, /shop/products, /shop/category,
| /shop/featured, /shop/categories, and any future subpath that needs the
| same chrome — per designer's recon all 5 subroutes resolve to the same
| fallthrough pre-fix; Slice A scope is ONE renderer for the whole subtree.
|
| Loaded via ShopServiceProvider::register() through an explicit
| $this->loadRoutesFrom(...) call (uncommented in this ship) —
| BaseModuleServiceProvider does NOT auto-load module web routes by
| default (see scaffold/provider.stub:37 which leaves the call commented
| for opt-in per module; same opt-in pattern used by Search AI-837).
|
*/

Route::middleware('web')->group(function () {
    Route::get('shop', [ShopController::class, 'index'])->name('shop.index');
    Route::get('shop/{path}', [ShopController::class, 'index'])
        ->where('path', '.*')
        ->name('shop.subroute');
});
