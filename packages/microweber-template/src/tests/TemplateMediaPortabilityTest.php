<?php

namespace MicroweberPackages\Template\tests;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Modules\Content\Models\Content;
use Modules\Media\Models\Media;
use MicroweberPackages\Template\Export\TemplateExporter;
use MicroweberPackages\Template\Export\TemplateMediaBundler;
use MicroweberPackages\Template\ContentJson\ContentJsonInstaller;

/**
 * Covers inline-media portability: media the content references by a site-local URL
 * (template design assets, uploaded userfiles) is rewritten to a {{TPL_MEDIA}}
 * placeholder and bundled into the exported template, then materialised into the
 * target site's userfiles and resolved back to a real URL on install — so a template
 * uploaded to a different site renders with its images.
 */
class TemplateMediaPortabilityTest extends TestCase
{
    private string $src = 'MbSrcTpl';
    private string $dest = 'MbDestTpl';
    private string $srcSlug = '';
    private string $outDir = '';
    private string $assetRel = '';
    private string $pageUrl = '';

    protected function setUp(): void
    {
        parent::setUp();
        $this->srcSlug = 'mbsrc' . substr(md5(uniqid()), 0, 8);
        $this->outDir = sys_get_temp_dir() . '/mw-tpl-media-' . uniqid();
        $this->pageUrl = 'mb-home-' . uniqid();

        // A real design asset the bundler can resolve via public_path().
        $this->assetRel = 'templates/' . $this->srcSlug . '/images/hero.png';
        $abs = public_path($this->assetRel);
        @mkdir(dirname($abs), 0755, true);
        file_put_contents($abs, base64_decode(
            // 1x1 transparent PNG
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='
        ));

        Content::create([
            'title' => 'MB Home',
            'url' => $this->pageUrl,
            'content_type' => 'page',
            'subtype' => 'static',
            'is_active' => 1,
            'active_site_template' => $this->src,
            'content' => '<section class="element"><img src="/' . $this->assetRel . '" alt="hero"></section>',
        ]);
    }

    protected function tearDown(): void
    {
        // Gallery media rows attached to the test pages.
        try {
            foreach (Content::withTrashed()->whereIn('active_site_template', [$this->src, $this->dest])->get(['id']) as $c) {
                Media::where('rel_id', $c->id)->where('rel_type', morph_name(Content::class))->delete();
            }
        } catch (\Throwable) {
        }
        Content::where('active_site_template', $this->src)->forceDelete();
        Content::where('active_site_template', $this->dest)->forceDelete();
        $this->rmrf(public_path('templates/' . $this->srcSlug));
        $this->rmrf($this->outDir);
        // Clean materialised media on the (local) userfiles disk.
        try {
            $disk = Storage::disk(userfiles_disk());
            foreach ($disk->files(rtrim(media_base_path_relative(), '/') . '/templates/' . strtolower($this->dest)) as $f) {
                $disk->delete($f);
            }
        } catch (\Throwable) {
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
    public function export_rewrites_inline_media_to_a_placeholder_and_bundles_the_file(): void
    {
        $info = $this->export();
        $root = $info['dir'];

        $content = json_decode((string) file_get_contents($root . '/content.json'), true);
        $html = $content['pages'][0]['html'] ?? '';

        $this->assertStringContainsString(TemplateMediaBundler::PLACEHOLDER, $html,
            'The exported content must reference media by the portable placeholder.');
        $this->assertStringNotContainsString('/templates/' . $this->srcSlug, $html,
            'The original site-local media URL must not survive in the export.');

        // The file is bundled under resources/assets/media/.
        $bundled = glob($root . '/resources/assets/media/*') ?: [];
        $this->assertNotEmpty($bundled, 'The referenced media file must be bundled into the template.');
        $this->assertGreaterThanOrEqual(1, $info['media_files']);
        // Still PHP-free.
        foreach ($bundled as $f) {
            $this->assertDoesNotMatchRegularExpression('/\.(php|phtml|phar)$/i', basename($f));
        }
    }

    #[Test]
    public function install_materialises_media_and_resolves_the_placeholder(): void
    {
        $info = $this->export();

        (new ContentJsonInstaller())
            ->setTemplate($this->dest)
            ->setFile($info['dir'] . '/content.json')   // also sets templateDir = export root
            ->install();

        $row = Content::where('active_site_template', $this->dest)
            ->where('content_type', 'page')
            ->first();

        $this->assertNotNull($row, 'The page must install under the new template.');
        $body = (string) $row->content;

        $this->assertStringNotContainsString(TemplateMediaBundler::PLACEHOLDER, $body,
            'The placeholder must be resolved to a real URL on install.');
        $this->assertStringContainsString('/templates/' . strtolower($this->dest) . '/', $body,
            'The resolved URL must point at the template-media folder in userfiles.');

        // The bundled file must actually exist on the userfiles disk.
        $disk = Storage::disk(userfiles_disk());
        $prefix = rtrim(media_base_path_relative(), '/') . '/templates/' . strtolower($this->dest) . '/';
        $this->assertNotEmpty($disk->files(rtrim($prefix, '/')),
            'The media file must be materialised into the target userfiles.');
    }

    #[Test]
    public function content_attached_gallery_round_trips_with_portable_media(): void
    {
        // A gallery image on disk + a media row attaching it to the source page.
        $galRel = 'templates/' . $this->srcSlug . '/images/gallery1.png';
        $galAbs = public_path($galRel);
        @mkdir(dirname($galAbs), 0755, true);
        file_put_contents($galAbs, base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='
        ));

        $src = Content::where('url', $this->pageUrl)->first();
        Media::create([
            'rel_type' => morph_name(Content::class),
            'rel_id' => $src->id,
            'media_type' => 'picture',
            'position' => 0,
            'filename' => '/' . $galRel,
            'image_options' => ['crop' => 'center'],
        ]);

        $info = $this->export();

        // Export: the gallery is captured and its filename is a portable placeholder.
        $content = json_decode((string) file_get_contents($info['dir'] . '/content.json'), true);
        $gallery = $content['pages'][0]['gallery'] ?? [];
        $this->assertNotEmpty($gallery, 'The attached gallery must be exported.');
        $this->assertStringContainsString(TemplateMediaBundler::PLACEHOLDER, $gallery[0]['filename']);
        $this->assertSame('center', $gallery[0]['image_options']['crop'] ?? null,
            'image_options must survive the round-trip.');

        // Install: a media row is recreated for the NEW content id with a resolved URL.
        (new ContentJsonInstaller())
            ->setTemplate($this->dest)
            ->setFile($info['dir'] . '/content.json')
            ->install();

        $new = Content::where('active_site_template', $this->dest)->where('content_type', 'page')->first();
        $this->assertNotNull($new);
        $rows = Media::where('rel_type', morph_name(Content::class))->where('rel_id', $new->id)->get();
        $this->assertCount(1, $rows, 'The gallery must be recreated against the new content id.');
        $this->assertStringNotContainsString(TemplateMediaBundler::PLACEHOLDER, (string) $rows[0]->filename,
            'The recreated media filename must resolve the placeholder.');
        $this->assertStringContainsString('/templates/' . strtolower($this->dest) . '/', (string) $rows[0]->filename);
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
