<?php

use \Illuminate\Support\Facades\Route;


Route::name('api.live-edit.')
    ->prefix('api/live-edit')
    ->middleware(['admin'])
    ->group(function () {


        Route::get('get-top-right-menu', MicroweberPackages\LiveEdit\Http\Controllers\Api\LiveEditMenusApi::class . '@getTopRightMenu')
            ->name('get-top-right-menu');
        Route::get('get-website-info', MicroweberPackages\LiveEdit\Http\Controllers\Api\LiveEditSetupWizardApi::class . '@getWebsiteInfo')
            ->name('get-website-info');

        // task-2026-09-22 — saved module options as a flat key=>value map, for
        // the quick-settings panel to read back values when the module emits no
        // inline settings <script> (layout-embedded modules: logo/menu/spacer…
        // don't, unlike content modules). Keyed by the module's option_group id.
        Route::get('module-options', function (\Illuminate\Http\Request $request) {
            $id = (string) $request->get('id', '');
            $map = [];
            if ($id !== '' && function_exists('get_module_options')) {
                $rows = get_module_options($id);
                if (is_array($rows)) {
                    foreach ($rows as $r) {
                        if (is_array($r) && isset($r['option_key'])) {
                            $map[$r['option_key']] = $r['option_value'] ?? '';
                        }
                    }
                }
            }
            return response()->json(['options' => $map]);
        })->name('module-options');

    });
