<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-84 / AI-71 / TICKET-AA — module-zoo dev/QA page regression
 * coverage.
 *
 * Pins:
 *   - Admin route `module-zoo` is registered under the admin
 *     middleware so non-admins get a 404.
 *   - AdminModuleController has a `zoo(Request $request)` method
 *     that resolves the modules via ModuleRepository.
 *   - The controller's skin-discovery helper globs the
 *     `Modules/<Name>/resources/views/templates/*.blade.php` folder
 *     and returns at minimum ['default'] as a fallback so every
 *     module renders at least one preview.
 *   - The view auto-discovers + renders a section per module + a
 *     <module> tag per discovered skin, wrapped in <article> +
 *     <section> landmarks for AT navigation.
 *   - Filter form supports `?type=<name>` + `?installed_only=<0|1>`
 *     URL parameters.
 *
 * Style after the cycle-52..83 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class ModuleZooContractTest extends TestCase
{
    private string $routesSrc;
    private string $controllerSrc;
    private string $viewSrc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->routesSrc = file_get_contents(base_path(
            'src/MicroweberPackages/Module/routes/admin.php'
        ));
        $this->controllerSrc = file_get_contents(base_path(
            'src/MicroweberPackages/Module/Http/Controllers/Admin/AdminModuleController.php'
        ));
        $this->viewSrc = file_get_contents(base_path(
            'src/MicroweberPackages/Module/resources/views/admin/module-zoo.blade.php'
        ));
    }

    #[Test]
    public function module_zoo_route_is_registered_under_admin_middleware(): void
    {
        // The route registration lives under the existing
        // Route::name('admin.module.')->prefix(...)->middleware(['admin'])
        // group, so the admin middleware wraps it transitively.
        $this->assertStringContainsString(
            "Route::get('module-zoo', 'AdminModuleController@zoo')->name('zoo');",
            $this->routesSrc,
            'admin.php: must register Route::get("module-zoo") → AdminModuleController@zoo'
        );

        // The route must live INSIDE the existing admin group (which
        // applies middleware(['admin'])). Pin the admin middleware
        // declaration is present at the top of the same file.
        $this->assertStringContainsString(
            "->middleware(['admin'])",
            $this->routesSrc,
            'admin.php: admin middleware group must wrap the module-zoo route'
        );
    }

    #[Test]
    public function controller_zoo_method_resolves_modules_via_repository(): void
    {
        $this->assertStringContainsString(
            'public function zoo(Request $request)',
            $this->controllerSrc,
            'AdminModuleController: zoo(Request $request) method must exist'
        );
        $this->assertStringContainsString(
            'app(\\MicroweberPackages\\Module\\Repositories\\ModuleRepository::class)',
            $this->controllerSrc,
            'AdminModuleController::zoo: must resolve modules via the ModuleRepository (FQCN reference)'
        );
        $this->assertStringContainsString(
            '$repository->getAllModules()',
            $this->controllerSrc,
            'AdminModuleController::zoo: must call getAllModules() on the repository'
        );
    }

    #[Test]
    public function controller_supports_type_and_installed_only_filters(): void
    {
        // Per-module filtering keeps the page reviewable when
        // narrowing to a single module.
        $this->assertStringContainsString(
            "\$request->get('type', '')",
            $this->controllerSrc,
            'AdminModuleController::zoo: must read type filter from request query'
        );
        $this->assertStringContainsString(
            "\$request->get('installed_only', '1') === '1'",
            $this->controllerSrc,
            'AdminModuleController::zoo: must read installed_only filter (default true)'
        );

        // Filter logic: skip if type doesn't match (case-insensitive).
        $this->assertStringContainsString(
            "strcasecmp(\$name, \$typeFilter) !== 0",
            $this->controllerSrc,
            'AdminModuleController::zoo: type filter must be case-insensitive (strcasecmp)'
        );
    }

    #[Test]
    public function skin_discovery_falls_back_to_default_when_folder_empty(): void
    {
        // Every module renders at least its default skin — even if
        // the templates folder is missing or empty. Without this
        // fallback, the zoo would skip modules entirely.
        $this->assertMatchesRegularExpression(
            "/protected\\s+function\\s+discoverModuleSkins\\(string\\s+\\\$moduleName\\):\\s*array/s",
            $this->controllerSrc,
            'AdminModuleController: discoverModuleSkins helper must exist'
        );
        $this->assertStringContainsString(
            "if (empty(\$skins)) {\n            return ['default'];",
            $this->controllerSrc,
            'discoverModuleSkins: must fall back to [\'default\'] when no .blade.php files are found'
        );

        // The glob target shape — pin both the templates path AND
        // the .blade.php extension filter.
        $this->assertStringContainsString(
            "Modules/{\$studlyName}/resources/views/templates",
            $this->controllerSrc,
            'discoverModuleSkins: must glob Modules/<StudlyName>/resources/views/templates'
        );
        $this->assertStringContainsString(
            "glob(\$path . '/*.blade.php')",
            $this->controllerSrc,
            'discoverModuleSkins: must glob *.blade.php (not all files)'
        );
    }

    #[Test]
    public function view_renders_module_section_with_landmark_semantics(): void
    {
        // Each module is a <section> landmark with aria-labelledby
        // pointing at the h2 heading; each skin is an <article>
        // landmark with its own aria-labelledby pointing at the h3.
        $this->assertStringContainsString(
            '<section class="mw-module-zoo-section card mb-5"',
            $this->viewSrc,
            'view: each module must be wrapped in a <section> landmark'
        );
        $this->assertStringContainsString(
            'aria-labelledby="mw-module-zoo-{{ $module[\'name\'] }}-h"',
            $this->viewSrc,
            'view: <section> must declare aria-labelledby pointing at the h2'
        );
        $this->assertStringContainsString(
            '<article class="mw-module-zoo-skin border rounded p-3 h-100"',
            $this->viewSrc,
            'view: each skin must be wrapped in an <article> landmark'
        );
    }

    #[Test]
    public function view_renders_module_tag_per_skin_with_unique_id(): void
    {
        // The actual rendering is delegated to Microweber's <module>
        // tag — same engine the live-edit canvas uses. Pin the call
        // shape so a future refactor can't silently break preview.
        $this->assertMatchesRegularExpression(
            '/<module\\s+type="\\{\\{\\s*\\$module\\[.name.\\]\\s*\\}\\}"\\s*\\n\\s*template="\\{\\{\\s*\\$skin\\s*\\}\\}"/s',
            $this->viewSrc,
            'view: must render <module type="{{ $module[name] }}" template="{{ $skin }}" /> per skin'
        );
        // Each module instance gets a unique id derived from
        // module-name + skin so per-instance config doesn't collide.
        $this->assertStringContainsString(
            'id="mw-module-zoo-{{ $module[\'name\'] }}-{{ $skin }}"',
            $this->viewSrc,
            'view: <module> tag must carry a unique id derived from module-name + skin'
        );
    }

    #[Test]
    public function view_filter_form_supports_type_and_installed_only_query_params(): void
    {
        $this->assertStringContainsString(
            'name="type"',
            $this->viewSrc,
            'view: filter form must include a `type` text input for module-name filtering'
        );
        $this->assertStringContainsString(
            'name="installed_only"',
            $this->viewSrc,
            'view: filter form must include an installed_only checkbox'
        );
        // Form submits via GET so URL state is shareable.
        $this->assertStringContainsString(
            '<form method="get"',
            $this->viewSrc,
            'view: filter form must use method="get" so filter state is reflected in the URL'
        );
    }
}
