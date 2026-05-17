<?php

use Illuminate\Support\Facades\Route;
use Modules\Search\Http\Controllers\SearchController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| task-2026-05-17-3e91f4 / AI-837 — frontend /search route registration.
|
| Pre-fix: this entire group was commented out (lines 17-19) and the
| referenced SearchController did not exist. `/search?q=X` fell through
| to the FrontendController catch-all, which detected "search" as an
| installed module name and rendered Templates/Bootstrap clean.blade.php
| with its hardcoded "My title / My text content" placeholder at 200 OK
| with no noindex header. Stage-3 propagation defect (sibling of AI-795
| frontend 404) — see AI-735b follow-up flag in
| src/MicroweberPackages/Frontend/routes/web.php lines 36-39.
|
| Fix: register a real `Route::get('search', ...)` mapped to a proper
| SearchController that renders a chrome-wrapped results view with the
| AI-795 standing chrome-application checklist applied (extends active
| template, semantic chrome container, recovery/empty-state context,
| 200 OK + noindex headers, contract test).
|
| Loaded via SearchServiceProvider::register() through an explicit
| $this->loadRoutesFrom(...) call — BaseModuleServiceProvider does NOT
| auto-load module web routes by default (see scaffold/provider.stub
| line 37 which leaves the call commented for opt-in per module).
|
*/

Route::middleware('web')->group(function () {
    Route::get('search', [SearchController::class, 'index'])->name('search.index');
});
