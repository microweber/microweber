<?php

namespace MicroweberPackages\Template\tests;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Modules\Content\Models\Content;
use MicroweberPackages\Template\Export\TemplateExporter;
use MicroweberPackages\Template\Export\TemplateMediaBundler;
use MicroweberPackages\Template\ContentJson\ContentJsonInstaller;

/**
 * Covers content_data (custom fields / setContentData) portability: a content item's
 * custom fields travel with the exported template and are recreated under the new
 * content id on install. Media URLs in custom-field values (e.g. a single-image
 * picker) round-trip via the {{TPL_MEDIA}} placeholder; plain fields are preserved.
 */
class TemplateContentDataPortabilityTest extends TestCase
{
    private string $src = 'CdSrcTpl';
    private string $dest = 'CdDestTpl';
    private string $srcSlug = '';
    private string $outDir = '';
    private string $pageUrl = '';

    protected function setUp(): void
    {
        parent::setUp();
        config(['media.upload_disk' => 'public']);
        $this->srcSlug = 'cdsrc' . substr(md5(uniqid()), 0, 8);
        $this->outDir = sys_get_temp_dir() . '/mw-tpl-cd-' . uniqid();
        $this->pageUrl = 'cd-home-' . uniqid();

        // A custom-field image on disk.
        $rel = 'templates/' . $this->srcSlug . '/images/cover.png';
        $abs = public_path($rel);
        @mkdir(dirname($abs), 0755, true);
        file_put_contents($abs, base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='
        ));

        $page = Content::create([
            'title' => 'CD Home',
            'url' => $this->pageUrl,
            'content_type' => 'page',
            'subtype' => 'static',
            'is_active' => 1,
            'active_site_template' => $this->src,
            'content' => '<section class="element"><h1>Custom fields</h1></section>',
        ]);
        // A media custom field + a plain custom field.
        $page->setContentData([
            'cover_image' => '/' . $rel,
            'subtitle' => 'Hello from custom data',
        ])->save();
    }

    protected function tearDown(): void
    {
        Content::where('active_site_template', $this->src)->forceDelete();
        Content::where('active_site_template', $this->dest)->forceDelete();
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
    public function content_data_is_exported_with_portable_media_and_plain_fields(): void
    {
        $info = $this->export();
        $content = json_decode((string) file_get_contents($info['dir'] . '/content.json'), true);

        $cd = $content['pages'][0]['content_data'] ?? null;
        $this->assertNotNull($cd, 'Custom fields (content_data) must be exported.');
        $this->assertStringContainsString(TemplateMediaBundler::PLACEHOLDER, $cd['cover_image'],
            'A custom-field image URL must be rewritten to a portable placeholder.');
        $this->assertSame('Hello from custom data', $cd['subtitle'],
            'Plain custom fields must be preserved verbatim.');
    }

    #[Test]
    public function content_data_is_recreated_on_install_with_resolved_media(): void
    {
        $info = $this->export();
        $this->installPageOnly($info);

        $new = Content::where('active_site_template', $this->dest)->where('content_type', 'page')->first();
        $this->assertNotNull($new);

        $map = $new->getContentData();
        $this->assertSame('Hello from custom data', $map['subtitle'] ?? null,
            'Plain custom fields must round-trip under the new content id.');
        $this->assertArrayHasKey('cover_image', $map);
        $this->assertStringNotContainsString(TemplateMediaBundler::PLACEHOLDER, (string) $map['cover_image'],
            'The custom-field media placeholder must resolve on install.');
        $this->assertStringContainsString('/templates/' . strtolower($this->dest) . '/', (string) $map['cover_image']);
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
