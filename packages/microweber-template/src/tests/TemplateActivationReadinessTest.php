<?php

namespace MicroweberPackages\Template\tests;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Modules\Content\Models\Content;
use MicroweberPackages\Template\Export\TemplateExporter;
use MicroweberPackages\Template\Export\TemplateImporter;
use MicroweberPackages\Template\ContentJson\ContentJsonInstaller;

/**
 * End-to-end "activation readiness": a template produced by the exporter and
 * installed by the importer must be a real, discoverable, renderable template —
 * its config resolves with parent_template=Base and, being view-less, its pages
 * render through Base's inherited views. This ties exporter → importer →
 * template-manager together (the step after the admin upload: the user activates
 * the template and the front-end renders it through Base).
 */
class TemplateActivationReadinessTest extends TestCase
{
    private string $src = 'ArSrcTpl';
    private string $dest = 'ArDestTpl';
    private string $outDir = '';
    private string $importRoot = '';
    private string $installedDir = '';
    private string $pageUrl = '';

    protected function setUp(): void
    {
        parent::setUp();
        config(['media.upload_disk' => 'public']);
        $this->outDir = sys_get_temp_dir() . '/mw-tpl-ar-' . uniqid();
        $this->importRoot = base_path('ExtraTemplates');
        $this->installedDir = $this->importRoot . '/' . $this->dest;
        $this->pageUrl = 'ar-home-' . uniqid();

        Content::create([
            'title' => 'AR Home',
            'url' => $this->pageUrl,
            'content_type' => 'page',
            'subtype' => 'static',
            'is_active' => 1,
            'active_site_template' => $this->src,
            'content' => '<section class="element"><h1>Activation ready</h1></section>',
        ]);
    }

    protected function tearDown(): void
    {
        Content::where('active_site_template', $this->src)->forceDelete();
        Content::where('active_site_template', $this->dest)->forceDelete();
        $this->rmrf($this->installedDir);
        $this->rmrf($this->outDir);
        // A stale scanned-template cache that still lists the now-removed dir makes
        // the next boot's createModule() return null and throw — flush it.
        try {
            Cache::flush();
        } catch (\Throwable) {
        }
        parent::tearDown();
    }

    /** Export the seeded site to a ZIP and import it into ExtraTemplates/. */
    private function exportAndImport(): array
    {
        $info = (new TemplateExporter())
            ->setName($this->dest)
            ->setSource($this->src)
            ->setParent('Base')
            ->exportToZip($this->outDir . '/tpl.zip', $this->outDir);

        $imported = (new TemplateImporter())->importZip($info['zip']);
        // A fresh scan must see the just-imported dir.
        Cache::flush();
        return $imported;
    }

    #[Test]
    public function an_exported_then_imported_template_is_discoverable_with_base_parent(): void
    {
        $imported = $this->exportAndImport();
        $this->assertSame($this->dest, $imported['name']);
        $this->assertDirectoryExists($this->installedDir);

        $config = app()->template_manager->get_config($this->dest);
        $this->assertIsArray($config);
        $this->assertSame($this->dest, $config['name']);
        $this->assertSame('Base', $config['parent_template'] ?? null,
            'The imported template must declare Base as its parent so rendering falls back to Base.');
    }

    #[Test]
    public function a_page_of_the_imported_template_resolves_a_base_inherited_view(): void
    {
        $this->exportAndImport();

        // The PHP-less child has no views of its own; a page falls back to Base's
        // clean.blade.php (the rendering path the front-end takes).
        $adapter = app()->template_manager->templateAdapter;
        $view = $adapter->resolveParentTemplateViewFile($this->dest, 'clean.blade.php');

        $this->assertIsString($view);
        $this->assertStringEndsWith(
            'Templates/Base/resources/views/clean.blade.php',
            str_replace('\\', '/', (string) $view)
        );
    }

    #[Test]
    public function activating_installs_the_content_under_the_new_template(): void
    {
        $this->exportAndImport();

        // Activation installs content.json (menu-free, to keep the test fast on the
        // large shared test DB) under the imported template.
        $data = json_decode((string) file_get_contents($this->installedDir . '/content.json'), true);
        unset($data['menus']);
        (new ContentJsonInstaller())
            ->setTemplate($this->dest)
            ->setTemplateDir($this->installedDir)
            ->setData($data)
            ->install();

        $page = Content::where('active_site_template', $this->dest)
            ->where('content_type', 'page')
            ->first();
        $this->assertNotNull($page, 'Activating the imported template must install its page.');
        $this->assertStringContainsString('Activation ready', (string) $page->content);
    }

    private function rmrf(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        $it = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($it as $f) {
            $f->isDir() ? @rmdir($f->getPathname()) : @unlink($f->getPathname());
        }
        @rmdir($dir);
    }
}
