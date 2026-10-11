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

        // task-2026-09-22 — consolidated settings for the redesigned Live Edit
        // "Page settings" (edit content) modal: everything the rows need in one
        // shape (title/url/design/visibility/location/menu/cover/seo).
        Route::get('content-settings', function (\Illuminate\Http\Request $request) {
            $id = (int) $request->get('id');
            $c = \Modules\Content\Models\Content::find($id);
            if (!$c) {
                return response()->json(['success' => false, 'message' => 'not found'], 404);
            }

            $type = $c->content_type ?: 'page';
            $isHome = false;
            try {
                $home = app()->content_manager->homepage();
                $isHome = is_array($home) && !empty($home['id']) && (int) $home['id'] === $id;
            } catch (\Throwable $e) {}

            // Design: template + layout friendly names.
            $templateName = ucwords(str_replace(['-', '_'], ' ', (string) ($c->active_site_template ?: (function_exists('template_name') ? template_name() : 'default'))));
            $layoutFile = (string) ($c->layout_file ?? '');
            $layoutName = 'Default layout';
            if ($layoutFile !== '') {
                $base = preg_replace('/\.(blade\.)?php$/', '', $layoutFile);
                $layoutName = ($base === 'clean') ? 'Clean page layout' : (ucwords(str_replace(['-', '_'], ' ', $base)) . ' layout');
            }

            // Menu membership + position (among its menu's items).
            $inMenu = false; $menuPos = null; $menuLabel = null;
            $menuItem = \Modules\Menu\Models\Menu::where('content_id', $id)->where('item_type', '!=', 'menu')->first();
            if ($menuItem) {
                $inMenu = true;
                $menuLabel = $menuItem->title ?: $c->title;
                $siblings = \Modules\Menu\Models\Menu::where('parent_id', $menuItem->parent_id)
                    ->where('item_type', '!=', 'menu')->orderBy('position')->orderBy('id')->pluck('id')->toArray();
                $idx = array_search($menuItem->id, $siblings, true);
                $menuPos = $idx === false ? null : ($idx + 1);
            }

            // Cover image (first picture media relation).
            $cover = null;
            $media = \Modules\Media\Models\Media::where('rel_type', \Modules\Content\Models\Content::class)
                ->where('rel_id', $id)->where('media_type', 'picture')->orderBy('position')->first();
            if ($media) {
                $fn = (string) $media->filename;
                $cover = [
                    'url' => $fn,
                    'name' => trim(basename(parse_url($fn, PHP_URL_PATH) ?: $fn)),
                ];
            }

            // SEO / sharing description.
            $metaDescription = '';
            try { $metaDescription = (string) ($c->getContentDataByFieldName('content_meta_description') ?: ''); } catch (\Throwable $e) {}
            if ($metaDescription === '') { $metaDescription = (string) ($c->description ?? ''); }

            // Location breadcrumb (parent chain, root-first).
            $crumbs = [];
            $parentId = (int) ($c->parent ?: 0);
            $guard = 0;
            while ($parentId && $guard++ < 8) {
                $p = \Modules\Content\Models\Content::find($parentId);
                if (!$p) { break; }
                array_unshift($crumbs, ['id' => (int) $p->id, 'title' => (string) $p->title]);
                $parentId = (int) ($p->parent ?: 0);
            }

            $liveDate = null;
            try { if ($c->created_at) { $liveDate = $c->created_at->format('j M Y'); } } catch (\Throwable $e) {}

            // Available templates + layouts (for the inline "Change design" picker).
            $activeTpl = (string) ($c->active_site_template ?: (function_exists('template_name') ? template_name() : 'default'));
            $templatesList = [];
            if (function_exists('site_templates')) {
                foreach ((array) site_templates() as $t) {
                    if (!empty($t['dir_name'])) {
                        $templatesList[] = ['dir' => $t['dir_name'], 'name' => $t['name'] ?? $t['dir_name']];
                    }
                }
            }
            $layoutsList = [];
            try {
                foreach ((array) app()->layouts_manager->get_all(['site_template' => $activeTpl, 'no_cache' => true, 'no_folder_sort' => true]) as $l) {
                    if (!empty($l['layout_file'])) {
                        $layoutsList[] = ['file' => $l['layout_file'], 'name' => $l['name'] ?? $l['layout_file']];
                    }
                }
            } catch (\Throwable $e) {}

            return response()->json([
                'success' => true,
                'id' => $id,
                'title' => (string) $c->title,
                'url' => (string) $c->url,
                'content_type' => $type,
                'is_active' => (int) $c->is_active === 1,
                'is_home' => $isHome,
                'design' => ['template' => $templateName, 'layout' => $layoutName],
                'template_key' => $activeTpl,
                'layout_key' => $layoutFile,
                'templates' => $templatesList,
                'layouts' => $layoutsList,
                'live_date' => $liveDate,
                'breadcrumb' => $crumbs,
                'in_menu' => $inMenu,
                'menu_position' => $menuPos,
                'menu_label' => $menuLabel,
                'cover' => $cover,
                'meta_description' => $metaDescription,
                'open_url' => (string) content_link($id),
                'edit_url' => (function () use ($c, $id) {
                    try {
                        return \Modules\Content\Filament\Admin\ContentResource::getUrl('edit', ['record' => $c]);
                    } catch (\Throwable $e) {
                        return function_exists('admin_url') ? admin_url('content/edit?id=' . $id) : '';
                    }
                })(),
            ]);
        })->name('content-settings');

        // Add/remove a content from the main menu (the Page-settings toggle).
        // Server-side so removal reliably clears EVERY menu link to the content
        // (the client can't cheaply hunt them across menus).
        Route::post('content-menu', function (\Illuminate\Http\Request $request) {
            $id = (int) $request->input('id');
            $inMenu = filter_var($request->input('in_menu'), FILTER_VALIDATE_BOOLEAN);
            if (!$id) {
                return response()->json(['success' => false, 'message' => 'missing id'], 422);
            }
            if ($inMenu) {
                $exists = \Modules\Menu\Models\Menu::where('content_id', $id)->where('item_type', '!=', 'menu')->exists();
                if (!$exists) {
                    $menu = \Modules\Menu\Models\Menu::where('item_type', 'menu')->where('title', 'header_menu')->first()
                        ?: \Modules\Menu\Models\Menu::where('item_type', 'menu')->first();
                    if ($menu) {
                        app()->menu_manager->menu_item_save(['menu_id' => $menu->id, 'content_id' => $id]);
                    }
                }
            } else {
                $items = \Modules\Menu\Models\Menu::where('content_id', $id)->where('item_type', '!=', 'menu')->get();
                foreach ($items as $it) {
                    app()->menu_manager->menu_item_delete($it->id);
                }
            }
            try { app()->cache_manager->delete('menus'); app()->cache_manager->delete('content'); } catch (\Throwable $e) {}
            return response()->json(['success' => true]);
        })->name('content-menu');

        // Layouts available for a given site template (the "Change design" picker
        // refreshes this when the template changes).
        Route::get('template-layouts', function (\Illuminate\Http\Request $request) {
            $tpl = (string) $request->get('template', '');
            $out = [];
            if ($tpl !== '') {
                try {
                    foreach ((array) app()->layouts_manager->get_all(['site_template' => $tpl, 'no_cache' => true, 'no_folder_sort' => true]) as $l) {
                        if (!empty($l['layout_file'])) {
                            $out[] = ['file' => $l['layout_file'], 'name' => $l['name'] ?? $l['layout_file']];
                        }
                    }
                } catch (\Throwable $e) {}
            }
            return response()->json(['success' => true, 'layouts' => $out]);
        })->name('template-layouts');

    });
