<?php

use Illuminate\Support\Facades\Route;
use Modules\Accordion\Http\Controllers\AccordionItemsController;

/*
 * task-2026-09-15-qskit — accordion item CRUD for the Live-Edit quick-settings
 * inline list. All admin-guarded (session + admin auth) — they mutate the DB.
 * `reorder` is declared before the `{id}` update route so it isn't captured by
 * the wildcard.
 */
Route::middleware(['admin'])->group(function () {
    Route::get('api/accordion-items', [AccordionItemsController::class, 'index']);
    Route::post('api/accordion-items', [AccordionItemsController::class, 'store']);
    Route::post('api/accordion-items/reorder', [AccordionItemsController::class, 'reorder']);
    Route::post('api/accordion-items/{id}', [AccordionItemsController::class, 'update']);
    Route::delete('api/accordion-items/{id}', [AccordionItemsController::class, 'destroy']);
});
