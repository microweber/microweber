<?php

namespace MicroweberPackages\Template\tests;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Modules\Content\Models\Content;
use MicroweberPackages\Option\Models\Option;
use MicroweberPackages\Template\Export\TemplateExporter;
use MicroweberPackages\Template\Export\TemplateMediaBundler;
use MicroweberPackages\Template\ContentJson\ContentJsonInstaller;

/**
 * Covers module-options portability (Slice 3): a module instance's saved options —
 * most importantly a background module's `data-background-image` — travel with the
 * exported template and are recreated on install, keyed by the module-instance id
 * from the (verbatim) content HTML. Media URLs inside option values round-trip via
 * the {{TPL_MEDIA}} placeholder; non-media options (colours) are preserved as-is.
 */
class TemplateModuleOptionsPortabilityTest extends TestCase
{
    private string $src = 'MoSrcTpl';
    private string $dest = 'MoDestTpl';
    private string $srcSlug = '';
    private string $moduleId = '';
    private string $outDir = '';
    private string $pageUrl = '';

    protected function setUp(): void
    {
        parent::setUp();
        // Keep media on the LOCAL disk in tests — avoid hitting/polluting a real S3
        // bucket (the testing env may select MEDIA_UPLOAD_DISK=s3).
        config(['media.upload_disk' => 'public']);

        $this->srcSlug = 'mosrc' . substr(md5(uniqid()), 0, 8);
        $this->moduleId = 'bg' . substr(md5(uniqid()), 0, 10);
        $this->outDir = sys_get_temp_dir() . '/mw-tpl-mo-' . uniqid();
        $this->pageUrl = 'mo-home-' . uniqid();

        // Background image on disk (a design asset the bundler can resolve).
        $rel = 'templates/' . $this->srcSlug . '/images/bg.png';
        $abs = public_path($rel);
        @mkdir(dirname($abs), 0755, true);
        file_put_contents($abs, base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='
        ));

        // A page containing a background module instance + that module's options.
        Content::create([
            'title' => 'MO Home',
            'url' => $this->pageUrl,
            'content_type' => 'page',
            'subtype' => 'static',
            'is_active' => 1,
            'active_site_template' => $this->src,
            'content' => '<module type="background" id="' . $this->moduleId . '"></module>',
        ]);
        save_module_option('data-background-image', '/' . $rel, $this->moduleId, 'background');
        save_module_option('data-background-color', '#182433', $this->moduleId, 'background');
    }

    protected function tearDown(): void
    {
        Content::where('active_site_template', $this->src)->forceDelete();
        Content::where('active_site_template', $this->dest)->forceDelete();
        try {
            Option::where('option_group', $this->moduleId)->delete();
        } catch (\Throwable) {
        }
        $this->rmrf(public_path('templates/' . $this->srcSlug));
        $this->rmrf($this->outDir);
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
    public function module_options_are_exported_with_a_portable_background_image(): void
    {
        $info = $this->export();
        $content = json_decode((string) file_get_contents($info['dir'] . '/content.json'), true);

        $mo = $content['pages'][0]['module_options'][$this->moduleId] ?? null;
        $this->assertNotNull($mo, 'The module instance options must be exported.');
        $this->assertSame('background', $mo['type']);
        $this->assertStringContainsString(TemplateMediaBundler::PLACEHOLDER, $mo['options']['data-background-image'],
            'The background image URL must be rewritten to a portable placeholder.');
        $this->assertSame('#182433', $mo['options']['data-background-color'],
            'Non-media options must be preserved verbatim.');
    }

    #[Test]
    public function module_options_are_recreated_on_install_with_resolved_media(): void
    {
        $info = $this->export();

        // Simulate a different site: wipe the option so install must re-write it.
        save_module_option('data-background-image', 'WIPED', $this->moduleId, 'background');

        $this->installPageOnly($info);

        $image = get_module_option('data-background-image', $this->moduleId);
        $color = get_module_option('data-background-color', $this->moduleId);

        $this->assertNotSame('WIPED', $image, 'Install must rewrite the module option.');
        $this->assertIsString($image);
        $this->assertStringNotContainsString(TemplateMediaBundler::PLACEHOLDER, $image,
            'The placeholder must be resolved on install.');
        $this->assertStringContainsString('/templates/' . strtolower($this->dest) . '/', $image,
            'The resolved background image must point at the materialised template media.');
        $this->assertSame('#182433', $color);
    }

    /**
     * Install only the exported page(s) + their module options — not the site-wide
     * menus (that global-menu path is unrelated to what this test covers and is slow
     * against the large shared test DB).
     */
    private function installPageOnly(array $info): void
    {
        $data = json_decode((string) file_get_contents($info['dir'] . '/content.json'), true);
        unset($data['menus']);
        (new ContentJsonInstaller())
            ->setTemplate($this->dest)
            ->setTemplateDir($info['dir'])
            ->setData($data)
            ->install();
    }

    #[Test]
    public function a_free_module_instance_gallery_round_trips(): void
    {
        // A free (module-instance) gallery: media rows keyed by rel_type='module',
        // rel_id = the module id, with a <module type="pictures" id="X"> in the HTML.
        $galModuleId = 'pics' . substr(md5(uniqid()), 0, 10);
        $rel = 'templates/' . $this->srcSlug . '/images/g1.png';
        $abs = public_path($rel);
        @mkdir(dirname($abs), 0755, true);
        file_put_contents($abs, base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='
        ));

        $page = Content::where('url', $this->pageUrl)->first();
        $page->content = '<module type="pictures" id="' . $galModuleId . '"></module>';
        $page->save();

        \Modules\Media\Models\Media::create([
            'rel_type' => 'module',
            'rel_id' => $galModuleId,
            'media_type' => 'picture',
            'position' => 0,
            'filename' => '/' . $rel,
        ]);

        try {
            $info = $this->export();
            $content = json_decode((string) file_get_contents($info['dir'] . '/content.json'), true);

            $mg = $content['pages'][0]['module_galleries'][$galModuleId] ?? null;
            $this->assertNotNull($mg, 'A free module-instance gallery must be exported.');
            $this->assertStringContainsString(TemplateMediaBundler::PLACEHOLDER, $mg[0]['filename']);

            // Wipe the source rows, then install and assert recreation under the same id.
            \Modules\Media\Models\Media::where('rel_type', 'module')->where('rel_id', $galModuleId)->delete();
            $this->installPageOnly($info);

            $rows = \Modules\Media\Models\Media::where('rel_type', 'module')->where('rel_id', $galModuleId)->get();
            $this->assertCount(1, $rows, 'The free gallery must be recreated under the same module id.');
            $this->assertStringNotContainsString(TemplateMediaBundler::PLACEHOLDER, (string) $rows[0]->filename);
            $this->assertStringContainsString('/templates/' . strtolower($this->dest) . '/', (string) $rows[0]->filename);
        } finally {
            \Modules\Media\Models\Media::where('rel_type', 'module')->where('rel_id', $galModuleId)->delete();
        }
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
