<?php

namespace MicroweberPackages\Template\tests;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Modules\Content\Models\Content;
use MicroweberPackages\Template\Export\TemplateExporter;
use MicroweberPackages\Template\ContentJson\ContentJsonInstaller;
use MicroweberPackages\TemplateCustomCss\Services\LiveEditCssManager;

/**
 * Covers exporting a Live-Edit site as a reusable child template: the output is
 * a PHP-less tree (JSON manifests + assets + content.json) with the design frozen
 * into css/base.css, and it round-trips back through ContentJsonInstaller.
 */
class TemplateExporterTest extends TestCase
{
    private string $src = 'ExpSrcTpl';
    private string $dest = 'ExpOutTpl';
    private string $outDir = '';

    protected function setUp(): void
    {
        parent::setUp();
        $this->outDir = sys_get_temp_dir() . '/mw-tpl-export-' . uniqid();

        // Seed a source "site": one page bound to the source template + a frozen
        // Live-Edit design (live_edit.css) for it.
        Content::create([
            'title' => 'Exp Home',
            'url' => 'exp-home-' . uniqid(),
            'content_type' => 'page',
            'subtype' => 'static',
            'is_active' => 1,
            'active_site_template' => $this->src,
            'content' => '<section class="element"><h1>Exported hero</h1></section>',
        ]);
        app(LiveEditCssManager::class)->saveContent(
            'h1{letter-spacing:-.02em;color:#182433} .btn{border-radius:10px}',
            $this->src
        );
    }

    protected function tearDown(): void
    {
        Content::where('active_site_template', $this->src)->forceDelete();
        Content::where('active_site_template', $this->dest)->forceDelete();
        if ($this->outDir && is_dir($this->outDir)) {
            $it = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($this->outDir, \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($it as $f) {
                $f->isDir() ? @rmdir($f->getPathname()) : @unlink($f->getPathname());
            }
            @rmdir($this->outDir);
        }
        parent::tearDown();
    }

    private function export(): array
    {
        return (new TemplateExporter())
            ->setName($this->dest)
            ->setSource($this->src)
            ->setParent('Base')
            ->export($this->outDir);
    }

    #[Test]
    public function the_export_is_php_less_with_json_manifests_and_frozen_design(): void
    {
        $info = $this->export();
        $root = $info['dir'];

        // No PHP anywhere.
        $php = [];
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS)) as $f) {
            if (preg_match('/\.(php|phtml|phar)$/i', $f->getFilename())) {
                $php[] = $f->getPathname();
            }
        }
        $this->assertSame([], $php, 'An exported template must contain no PHP files.');

        // JSON manifests present with the right shape.
        $config = json_decode((string) file_get_contents($root . '/config.json'), true);
        $this->assertSame($this->dest, $config['name']);
        $this->assertSame('Base', $config['parent_template']);
        $module = json_decode((string) file_get_contents($root . '/module.json'), true);
        $this->assertSame([], $module['providers'], 'A PHP-less template registers no providers.');

        // The Live-Edit design is frozen into css/base.css.
        $baseCss = (string) file_get_contents($root . '/resources/assets/css/base.css');
        $this->assertStringContainsString('letter-spacing:-.02em', $baseCss);
        $this->assertStringContainsString('border-radius:10px', $baseCss);
    }

    #[Test]
    public function exported_pages_are_rebound_to_the_new_template(): void
    {
        $info = $this->export();
        $content = json_decode((string) file_get_contents($info['dir'] . '/content.json'), true);

        $this->assertNotEmpty($content['pages']);
        foreach ($content['pages'] as $page) {
            $this->assertSame($this->dest, $page['active_site_template'],
                'Every exported page must bind to the new template, not the source.');
        }
    }

    #[Test]
    public function the_exported_content_round_trips_through_the_installer(): void
    {
        $info = $this->export();

        (new ContentJsonInstaller())
            ->setTemplate($this->dest)
            ->setFile($info['dir'] . '/content.json')
            ->install();

        $installed = Content::where('active_site_template', $this->dest)
            ->where('content_type', 'page')
            ->get();

        $this->assertNotEmpty($installed, 'Installing the export must recreate the pages under the new template.');
        $this->assertTrue(
            $installed->contains(fn ($c) => str_contains((string) ($c->content ?? ''), 'Exported hero')),
            'The page body must round-trip through the export/install cycle.'
        );
    }

    #[Test]
    public function export_to_zip_produces_a_php_free_archive(): void
    {
        $info = (new TemplateExporter())
            ->setName($this->dest)
            ->setSource($this->src)
            ->setParent('Base')
            ->exportToZip($this->outDir . '/out.zip', $this->outDir);

        $this->assertFileExists($info['zip']);
        $zip = new \ZipArchive();
        $this->assertTrue($zip->open($info['zip']) === true);
        $phpInZip = [];
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $n = $zip->getNameIndex($i);
            if (preg_match('/\.(php|phtml|phar)$/i', (string) $n)) {
                $phpInZip[] = $n;
            }
        }
        $zip->close();
        $this->assertSame([], $phpInZip, 'The exported ZIP must not contain any PHP file.');
    }
}
