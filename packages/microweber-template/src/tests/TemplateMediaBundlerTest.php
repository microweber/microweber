<?php

namespace MicroweberPackages\Template\tests;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use MicroweberPackages\Template\Export\TemplateMediaBundler;

/**
 * Unit coverage for {@see TemplateMediaBundler} — the media-portability layer of the
 * JSON-template (export-as-template) format. The round-trip is exercised elsewhere
 * (TemplateMediaPortabilityTest) through a full DB export; this pins the bundler's own
 * contract directly, with crafted HTML/arrays and throwaway files, so each rule is
 * checked in isolation and fast:
 *
 *   - which URL positions in the HTML are rewritten to the {{TPL_MEDIA}} placeholder
 *     (src, href, poster, data- attributes and css url parens) and which are left alone
 *     (data: URIs, off-site hosts, non-media);
 *   - the bundleString() rules for module-option / content_data values (colors, `none`,
 *     already-placeholdered and data: values pass through untouched);
 *   - placeholder stability, name hashing (same basename under different folders does not
 *     collide), and idempotence (one source bundled once);
 *   - process() rewrites gallery / module_options / content_data media too;
 *   - writeInto() copies the bundled bytes out and returns the count;
 *   - the max-file-size guard skips oversized files instead of bundling them.
 *
 * Files are written under public_path() so the bundler can resolve them the same way it
 * would resolve a real design asset, and removed in tearDown — nothing is committed.
 */
class TemplateMediaBundlerTest extends TestCase
{
    /** 1x1 transparent PNG. */
    private const PNG = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

    private string $slug = '';
    private string $publicDir = '';
    private string $outDir = '';

    protected function setUp(): void
    {
        parent::setUp();
        // Keep media on the LOCAL disk so userfiles_disk_is_remote() is false and the
        // bundler resolves via public_path() — never touching a real S3 bucket.
        config(['media.upload_disk' => 'public']);

        $this->slug = 'mbundler' . substr(md5(uniqid()), 0, 8);
        $this->publicDir = 'templates/' . $this->slug;
        $this->outDir = sys_get_temp_dir() . '/mw-bundler-' . uniqid();
    }

    protected function tearDown(): void
    {
        $this->rmrf(public_path($this->publicDir));
        $this->rmrf($this->outDir);
        parent::tearDown();
    }

    /** Create a resolvable media file under public/ and return its root-relative URL. */
    private function asset(string $relUnderSlug, string $b64 = self::PNG): string
    {
        $rel = $this->publicDir . '/' . ltrim($relUnderSlug, '/');
        $abs = public_path($rel);
        @mkdir(dirname($abs), 0755, true);
        file_put_contents($abs, base64_decode($b64));
        return '/' . $rel;
    }

    #[Test]
    public function the_placeholder_token_is_stable(): void
    {
        // The installer resolves exactly this token; it must not drift.
        $this->assertSame('{{TPL_MEDIA}}', TemplateMediaBundler::PLACEHOLDER);
    }

    #[Test]
    public function process_rewrites_resolvable_inline_media_and_records_the_file(): void
    {
        $url = $this->asset('images/hero.png');
        $bundler = new TemplateMediaBundler();

        $out = $bundler->process([
            'pages' => [[
                'html' => '<section class="element"><img src="' . $url . '" alt="x"></section>',
            ]],
        ]);

        $html = $out['pages'][0]['html'];
        $this->assertStringContainsString(TemplateMediaBundler::PLACEHOLDER . '/', $html,
            'A resolvable site-local image must be rewritten to the portable placeholder.');
        $this->assertStringNotContainsString($url, $html,
            'The original site-local URL must not survive in the rewritten HTML.');

        $stats = $bundler->stats();
        $this->assertSame(1, $stats['bundled']);
        $this->assertSame(1, $stats['files']);
    }

    #[Test]
    public function process_rewrites_media_in_css_url_and_data_attribute_positions(): void
    {
        $bg = $this->asset('images/bg.jpg', self::PNG);
        $poster = $this->asset('images/clip.png');
        $bundler = new TemplateMediaBundler();

        $out = $bundler->process([
            'pages' => [[
                'html' => '<div style="background:url(\'' . $bg . '\')">'
                    . '<video poster="' . $poster . '"></video>'
                    . '<span data-background-image="' . $bg . '"></span></div>',
            ]],
        ]);

        $html = $out['pages'][0]['html'];
        $this->assertStringNotContainsString($bg, $html, 'url() and data-* media must be rewritten.');
        $this->assertStringNotContainsString($poster, $html, 'poster media must be rewritten.');
        // Two distinct source URLs → two bundled files; the repeated bg is bundled once.
        $this->assertSame(2, $bundler->stats()['files']);
    }

    #[Test]
    public function process_leaves_offsite_and_data_and_non_media_urls_untouched(): void
    {
        $html = '<img src="https://cdn.example.com/remote.png">'
            . '<img src="data:image/png;base64,' . self::PNG . '">'
            . '<a href="/about-us">About</a>'
            . '<module type="shop" id="abc"></module>';
        $bundler = new TemplateMediaBundler();

        $out = $bundler->process(['pages' => [['html' => $html]]]);

        $this->assertSame($html, $out['pages'][0]['html'],
            'Third-party, data: and non-media URLs (and module tags) must pass through verbatim.');
        $this->assertSame(0, $bundler->stats()['files'], 'Nothing resolvable → nothing bundled.');
    }

    #[Test]
    public function bundle_string_passes_non_media_option_values_through_unchanged(): void
    {
        $bundler = new TemplateMediaBundler();

        foreach (['#0d6efd', 'none', 'cover', 'rgba(0,0,0,.5)', '', '{{TPL_MEDIA}}/already.png',
                  'data:image/png;base64,' . self::PNG] as $value) {
            $this->assertSame($value, $bundler->bundleString($value),
                "A non-media / already-portable option value must be returned unchanged: {$value}");
        }
        $this->assertSame(0, $bundler->stats()['files']);
    }

    #[Test]
    public function bundle_string_rewrites_a_bare_and_a_wrapped_media_url(): void
    {
        $bare = $this->asset('images/opt-bg.jpg');
        $bundler = new TemplateMediaBundler();

        $this->assertStringStartsWith(TemplateMediaBundler::PLACEHOLDER . '/', $bundler->bundleString($bare),
            'A bare media URL option value must become a placeholder.');
        $wrapped = $bundler->bundleString("url('" . $bare . "')");
        $this->assertStringContainsString(TemplateMediaBundler::PLACEHOLDER . '/', $wrapped);
        $this->assertStringNotContainsString($bare, $wrapped);
        // Same source URL both times → bundled once.
        $this->assertSame(1, $bundler->stats()['files']);
    }

    #[Test]
    public function process_rewrites_gallery_module_option_and_content_data_media(): void
    {
        $gal = $this->asset('images/gal.png');
        $modGal = $this->asset('images/modgal.png');
        $optBg = $this->asset('images/optbg.jpg');
        $cf = $this->asset('images/field.png');
        $bundler = new TemplateMediaBundler();

        $out = $bundler->process([
            'pages' => [[
                'html' => '<section class="element"></section>',
                'gallery' => [['filename' => $gal, 'media_type' => 'picture']],
                'module_galleries' => ['42' => [['filename' => $modGal]]],
                'module_options' => ['42' => [
                    'type' => 'pictures',
                    'options' => ['data-background-image' => $optBg, 'overlay-color' => '#111111'],
                ]],
                'content_data' => ['hero_image' => $cf, 'subtitle' => 'Just text'],
            ]],
        ]);

        $item = $out['pages'][0];
        $ph = TemplateMediaBundler::PLACEHOLDER . '/';
        $this->assertStringStartsWith($ph, $item['gallery'][0]['filename']);
        $this->assertStringStartsWith($ph, $item['module_galleries']['42'][0]['filename']);
        $this->assertStringStartsWith($ph, $item['module_options']['42']['options']['data-background-image']);
        $this->assertStringStartsWith($ph, $item['content_data']['hero_image']);

        // Non-media siblings are untouched.
        $this->assertSame('#111111', $item['module_options']['42']['options']['overlay-color']);
        $this->assertSame('Just text', $item['content_data']['subtitle']);
        $this->assertSame('picture', $item['gallery'][0]['media_type']);

        $this->assertSame(4, $bundler->stats()['files'], 'Four distinct media sources must be bundled.');
    }

    #[Test]
    public function same_basename_in_different_folders_does_not_collide(): void
    {
        $a = $this->asset('a/logo.png');
        $b = $this->asset('b/logo.png');
        $bundler = new TemplateMediaBundler();

        $out = $bundler->process(['pages' => [[
            'html' => '<img src="' . $a . '"><img src="' . $b . '">',
        ]]]);

        $html = $out['pages'][0]['html'];
        // Pull the two placeholder names out of the rewritten HTML.
        preg_match_all('#' . preg_quote(TemplateMediaBundler::PLACEHOLDER, '#') . '/([^"\')\s]+)#', $html, $m);
        $this->assertCount(2, $m[1], 'Both images must be rewritten.');
        $this->assertNotSame($m[1][0], $m[1][1],
            'Two "logo.png" under different folders must get distinct bundled names.');
        $this->assertSame(2, $bundler->stats()['files']);
    }

    #[Test]
    public function write_into_copies_the_bundled_bytes_and_returns_the_count(): void
    {
        $url = $this->asset('images/hero.png');
        $srcBytes = base64_decode(self::PNG);
        $bundler = new TemplateMediaBundler();

        $bundler->process(['pages' => [['html' => '<img src="' . $url . '">']]]);
        $written = $bundler->writeInto($this->outDir);

        $this->assertSame(1, $written);
        $files = glob($this->outDir . '/resources/assets/media/*') ?: [];
        $this->assertCount(1, $files, 'The bundled file must land under resources/assets/media/.');
        $this->assertSame($srcBytes, file_get_contents($files[0]),
            'The copied bytes must be identical to the source media.');
        $this->assertDoesNotMatchRegularExpression('/\.(php|phtml|phar)$/i', basename($files[0]),
            'Bundled media must never carry an executable extension.');
    }

    #[Test]
    public function write_into_returns_zero_when_nothing_was_bundled(): void
    {
        $bundler = new TemplateMediaBundler();
        $bundler->process(['pages' => [['html' => '<p>no media here</p>']]]);

        $this->assertSame(0, $bundler->writeInto($this->outDir));
        $this->assertFalse(is_dir($this->outDir . '/resources/assets/media'),
            'No media dir should be created when there is nothing to write.');
    }

    #[Test]
    public function the_max_file_size_guard_skips_oversized_media(): void
    {
        $url = $this->asset('images/big.png'); // a few dozen bytes — larger than the 1-byte cap
        $bundler = (new TemplateMediaBundler())->setMaxFileBytes(1);

        $out = $bundler->process(['pages' => [['html' => '<img src="' . $url . '">']]]);

        $this->assertStringContainsString($url, $out['pages'][0]['html'],
            'An oversized file must be left as its original URL, not bundled.');
        $stats = $bundler->stats();
        $this->assertSame(0, $stats['bundled']);
        $this->assertSame(1, $stats['skipped']);
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
