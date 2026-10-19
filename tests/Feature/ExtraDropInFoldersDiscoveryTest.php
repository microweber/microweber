<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Proves the extra drop-in folders are scanned alongside the primary
 * Modules/ and Templates/ roots:
 *
 *   - A module dropped into ExtraModules/<Name>/ is discovered by the
 *     laravel-modules repository (app()->modules->find()).
 *   - A template dropped into ExtraTemplates/<Name>/ is discovered both by the
 *     laravel-templates repository (app()->templates->find()) and by the
 *     site_templates() scanner that powers the admin install screen.
 *
 * Fixtures are created under the real repo-root drop-in folders and removed in
 * tearDown(). Because the module/template caches are shared on disk with any
 * running dev server, tearDown() also flushes those caches so a deleted fixture
 * can never leave a stale cache entry behind (which would break a later scan).
 */
class ExtraDropInFoldersDiscoveryTest extends TestCase
{
    private const MODULE_NAME = 'zzextradropinmodulefixture';
    private const TEMPLATE_NAME = 'zzextradropintemplatefixture';

    private string $moduleDir;
    private string $templateDir;

    protected function setUp(): void
    {
        parent::setUp();

        $this->moduleDir = base_path('ExtraModules' . DIRECTORY_SEPARATOR . self::MODULE_NAME);
        $this->templateDir = base_path('ExtraTemplates' . DIRECTORY_SEPARATOR . self::TEMPLATE_NAME);

        $this->removeFixtures();
        $this->writeModuleFixture();
        $this->writeTemplateFixture();
        $this->flushCaches();
    }

    protected function tearDown(): void
    {
        $this->removeFixtures();
        // Rebuild-free flush: the next scan re-globs from disk (fixture gone),
        // so no stale cache entry survives to break a later boot.
        $this->flushCaches();

        parent::tearDown();
    }

    #[Test]
    public function extra_module_folder_is_discovered(): void
    {
        $module = app()->modules->find(self::MODULE_NAME);

        $this->assertNotNull($module, 'Module dropped into ExtraModules/ was not discovered.');
        $this->assertSame(self::MODULE_NAME, $module->getName());
        $this->assertStringContainsString(
            'ExtraModules',
            normalize_path($module->getPath(), false),
            'Discovered module should resolve to the ExtraModules/ drop-in root.'
        );
    }

    #[Test]
    public function extra_template_folder_is_discovered_by_repository(): void
    {
        $template = app()->templates->find(self::TEMPLATE_NAME);

        $this->assertNotNull($template, 'Template dropped into ExtraTemplates/ was not discovered by the repository.');
        $this->assertSame(self::TEMPLATE_NAME, $template->getName());
    }

    #[Test]
    public function extra_template_folder_is_returned_by_site_templates_scan(): void
    {
        $templates = site_templates();

        $this->assertIsArray($templates, 'site_templates() should return an array of templates.');

        $names = array_map(
            static fn ($config) => $config['dir_name'] ?? ($config['name'] ?? null),
            $templates
        );

        $this->assertContains(
            self::TEMPLATE_NAME,
            $names,
            'Template dropped into ExtraTemplates/ was not returned by the site_templates() scan.'
        );
    }

    private function writeModuleFixture(): void
    {
        if (!is_dir($this->moduleDir)) {
            mkdir($this->moduleDir, 0777, true);
        }

        file_put_contents(
            $this->moduleDir . DIRECTORY_SEPARATOR . 'module.json',
            json_encode([
                'name' => self::MODULE_NAME,
                'alias' => self::MODULE_NAME,
                'description' => 'Extra drop-in module fixture (test).',
                'keywords' => [],
                'priority' => 0,
                // Intentionally no providers: discovery must not require booting.
                'providers' => [],
                'files' => [],
            ], JSON_PRETTY_PRINT)
        );

        file_put_contents(
            $this->moduleDir . DIRECTORY_SEPARATOR . 'composer.json',
            json_encode([
                'name' => 'microweber-module/' . self::MODULE_NAME,
                'description' => 'Extra drop-in module fixture (test).',
                'type' => 'microweber-module',
            ], JSON_PRETTY_PRINT)
        );
    }

    private function writeTemplateFixture(): void
    {
        $configDir = $this->templateDir . DIRECTORY_SEPARATOR . 'config';
        if (!is_dir($configDir)) {
            mkdir($configDir, 0777, true);
        }

        file_put_contents(
            $configDir . DIRECTORY_SEPARATOR . 'config.php',
            "<?php\n\nreturn " . var_export([
                'name' => self::TEMPLATE_NAME,
                'title' => 'Extra Drop-in Template Fixture',
                'version' => '1.0',
            ], true) . ";\n"
        );

        file_put_contents(
            $this->templateDir . DIRECTORY_SEPARATOR . 'module.json',
            json_encode([
                'name' => self::TEMPLATE_NAME,
                'alias' => self::TEMPLATE_NAME,
                'description' => 'Extra drop-in template fixture (test).',
                'priority' => 0,
                'providers' => [],
                'files' => [],
            ], JSON_PRETTY_PRINT)
        );

        file_put_contents(
            $this->templateDir . DIRECTORY_SEPARATOR . 'composer.json',
            json_encode([
                'name' => 'microweber-template/' . self::TEMPLATE_NAME,
                'description' => 'Extra drop-in template fixture (test).',
                'type' => 'microweber-template',
            ], JSON_PRETTY_PRINT)
        );
    }

    private function flushCaches(): void
    {
        app()->modules->flushCache();
        app()->templates->flushCache();
    }

    private function removeFixtures(): void
    {
        $this->deleteRecursive($this->moduleDir);
        $this->deleteRecursive($this->templateDir);
    }

    private function deleteRecursive(string $path): void
    {
        if (!is_dir($path)) {
            if (is_file($path)) {
                @unlink($path);
            }
            return;
        }

        $items = scandir($path) ?: [];
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $this->deleteRecursive($path . DIRECTORY_SEPARATOR . $item);
        }

        @rmdir($path);
    }
}
