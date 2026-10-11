<?php

namespace MicroweberPackages\Module\Http\Controllers\Admin;

use Illuminate\Http\Request;

class AdminModuleController
{
    public function index(Request $request)
    {
        return view('module::admin.index');
    }

    public function view(Request $request)
    {

        $type = $request->get('type', false);
        $type = module_name_decode($type);

        $module_info = module_info($type);

        $module_permissions = module_permissions($module_info);
        $module_denied = true;

        if ($module_permissions) {
            if (user_can_access($module_permissions['index'])) {
                $module_denied = false;
            }
            if (user_can_access($module_permissions['create'])) {
                $module_denied = false;
            }
            if (user_can_access($module_permissions['edit'])) {
                $module_denied = false;
            }
            if (user_can_access($module_permissions['destroy'])) {
                $module_denied = false;
            }
            if ($module_denied) {
                return 'Permission denied';
            }
        }




        if(!is_module($type)){
            return 'No module found';
        }

        return view('module::admin.view', [
            'type' => $type,
        ]);

    }

    /**
     * AI-71 / TICKET-AA (cycle-84 2026-05-08): module-zoo dev/QA
     * page. Discovers every installed module via the
     * ModuleRepository + scans each module's `resources/views/templates`
     * folder for skin variations. Renders them in one page so
     * reviewers can audit every skin side-by-side without having to
     * stage 33+ live-edit pages by hand.
     *
     * Filtering: optional `?type=Posts` query string limits the page
     * to a single module — useful for narrowing reviews. Optional
     * `?installed_only=0` shows uninstalled modules too (default
     * filters to installed).
     */
    public function zoo(Request $request)
    {
        $repository = app(\MicroweberPackages\Module\Repositories\ModuleRepository::class);
        $allModules = $repository->getAllModules();

        $typeFilter = trim((string) $request->get('type', ''));
        $installedOnly = $request->get('installed_only', '1') === '1';

        $modules = [];
        foreach ($allModules as $module) {
            $name = (string) ($module['module'] ?? $module['name'] ?? '');
            if ($name === '') {
                continue;
            }
            if ($typeFilter !== '' && strcasecmp($name, $typeFilter) !== 0) {
                continue;
            }
            if ($installedOnly && (int) ($module['installed'] ?? 0) !== 1) {
                continue;
            }

            // Discover skins by scanning the module's templates folder.
            // Falls back to ['default'] when the folder is empty or
            // missing — every module renders at least its default skin.
            $skins = $this->discoverModuleSkins($name);

            $modules[] = [
                'name' => $name,
                'title' => (string) ($module['title'] ?? $module['name'] ?? $name),
                'description' => (string) ($module['description'] ?? ''),
                'icon' => (string) ($module['icon'] ?? ''),
                'skins' => $skins,
            ];
        }

        return view('module::admin.module-zoo', [
            'modules' => $modules,
            'typeFilter' => $typeFilter,
            'installedOnly' => $installedOnly,
            'totalModuleCount' => count($modules),
        ]);
    }

    /**
     * AI-71 / TICKET-AA (cycle-84 2026-05-08): glob the module's
     * `resources/views/templates/*.blade.php` folder and return a
     * deduplicated, sorted list of skin names. Returns ['default']
     * when nothing else is found so the zoo page always renders at
     * least one preview per module.
     */
    protected function discoverModuleSkins(string $moduleName): array
    {
        $studlyName = str_replace(' ', '', ucwords(str_replace(['_', '-'], ' ', $moduleName)));
        $candidatePaths = [
            base_path("Modules/{$studlyName}/resources/views/templates"),
            base_path("Modules/" . ucfirst($moduleName) . "/resources/views/templates"),
        ];

        $skins = [];
        foreach ($candidatePaths as $path) {
            if (! is_dir($path)) {
                continue;
            }
            $bladeFiles = glob($path . '/*.blade.php') ?: [];
            foreach ($bladeFiles as $bladePath) {
                $skin = basename($bladePath, '.blade.php');
                // Skip per-skin partials and option files; keep only
                // the canonical skin entry-points.
                if (str_contains($skin, '_options')
                    || str_contains($skin, 'shop_inner')
                    || str_starts_with($skin, '_')) {
                    continue;
                }
                $skins[$skin] = $skin;
            }
            break; // first matching path wins
        }

        if (empty($skins)) {
            return ['default'];
        }

        ksort($skins);
        return array_values($skins);
    }
}
