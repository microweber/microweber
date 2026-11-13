<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateFonts\Tests\Unit;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use MicroweberPackages\TemplateFonts\Models\TemplateFont;
use MicroweberPackages\TemplateFonts\Services\TemplateFontsManager;
use MicroweberPackages\TemplateFonts\Tests\TestCase;

/**
 * Covers the S3 "mirror + serve" capability of TemplateFontsManager.
 *
 * All disk access is routed through Storage::fake('s3'), so no real network
 * or bucket is touched. The compiled stylesheet is still written to the local
 * cache; the remote mirror only changes which URL is served.
 */
class TemplateFontsManagerS3MirrorTest extends TestCase
{
    protected string $tempCachePath = '';

    protected function setUp(): void
    {
        parent::setUp();
        $this->tempCachePath = sys_get_temp_dir() . '/mw-fonts-s3-' . uniqid();
        @mkdir($this->tempCachePath, 0755, true);
    }

    protected function tearDown(): void
    {
        if ($this->tempCachePath !== '' && is_dir($this->tempCachePath)) {
            foreach (glob($this->tempCachePath . '/*') ?: [] as $f) {
                @unlink($f);
            }
            @rmdir($this->tempCachePath);
        }
        parent::tearDown();
    }

    protected function makeManager(array $overrides = []): TemplateFontsManager
    {
        return new TemplateFontsManager(array_merge([
            'compile_assets' => true,
            'css_cache_path' => $this->tempCachePath,
            'css_cache_url' => '/storage/cache',
            'download_google_fonts_locally' => false,
            'migrate_legacy_options' => false,
        ], $overrides));
    }

    public function test_remote_disk_mirrors_compiled_stylesheet_to_s3_and_returns_bucket_url(): void
    {
        Storage::fake('s3', ['url' => 'https://bucket.example.test']);

        $manager = $this->makeManager([
            'disk' => 's3',
            'disk_is_remote' => true,
            'css_cache_relpath' => 'userfiles/cache',
        ]);

        $url = $manager->getFontsStylesheetCssUrl();

        // The compiled filename carries a content signature; derive it the same
        // way the manager does so the assertion targets the exact object.
        $filename = $manager->getFontsStylesheetFilename();
        $expectedRel = 'userfiles/cache/' . $filename;

        // The compiled stylesheet is mirrored onto the fake s3 disk.
        Storage::disk('s3')->assertExists($expectedRel);

        // The compiled file is still written to the local cache as well.
        $this->assertFileExists($this->tempCachePath . DIRECTORY_SEPARATOR . $filename);

        // getFontsStylesheetCssUrl() serves from the bucket.
        $this->assertStringStartsWith('https://bucket.example.test', $url);
        $this->assertStringContainsString($expectedRel, $url);
    }

    public function test_local_only_returns_local_cache_url_and_writes_nothing_to_s3(): void
    {
        Storage::fake('s3', ['url' => 'https://bucket.example.test']);

        $manager = $this->makeManager([
            'disk' => 's3',
            'disk_is_remote' => false,
            'css_cache_relpath' => 'userfiles/cache',
        ]);

        $url = $manager->getFontsStylesheetCssUrl();
        $filename = $manager->getFontsStylesheetFilename();

        // Nothing is mirrored to the remote disk when disk_is_remote=false.
        $this->assertSame([], Storage::disk('s3')->allFiles());
        Storage::disk('s3')->assertMissing('userfiles/cache/' . $filename);

        // The compiled file is still written to the local cache.
        $this->assertFileExists($this->tempCachePath . DIRECTORY_SEPARATOR . $filename);

        // The returned URL is the local cache URL, not a bucket URL.
        $this->assertSame('/storage/cache/' . $filename, $url);
        $this->assertStringNotContainsString('bucket.example.test', $url);
    }

    /**
     * Build a throwaway downloaded-font folder (font.css + a woff2) and return
     * the font.css path, the way GoogleFontDownloader leaves it on local disk.
     */
    private function makeLocalFontFolder(string $slug): string
    {
        $dir = $this->tempCachePath . DIRECTORY_SEPARATOR . $slug;
        @mkdir($dir, 0755, true);
        file_put_contents($dir . DIRECTORY_SEPARATOR . 'font.css',
            "@font-face{font-family:'X';src:url(./x.woff2) format('woff2');}");
        file_put_contents($dir . DIRECTORY_SEPARATOR . 'x.woff2', 'FAKEWOFF2');

        return $dir . DIRECTORY_SEPARATOR . 'font.css';
    }

    private function callMirror(TemplateFontsManager $m, string $cssPath): ?string
    {
        $ref = new \ReflectionMethod($m, 'mirrorFontFilesToRemote');
        $ref->setAccessible(true);

        return $ref->invoke($m, $cssPath);
    }

    public function test_remote_disk_mirrors_downloaded_font_binaries_and_css_to_s3(): void
    {
        Storage::fake('s3', ['url' => 'https://bucket.example.test']);

        $manager = $this->makeManager([
            'disk' => 's3',
            'disk_is_remote' => true,
            'fonts_relpath' => 'userfiles/fonts',
        ]);

        $cssPath = $this->makeLocalFontFolder('roboto');
        $remoteUrl = $this->callMirror($manager, $cssPath);

        // BOTH the stylesheet AND the binary land in the bucket under the
        // per-site fonts path — otherwise the CSS's ./x.woff2 ref would 404.
        Storage::disk('s3')->assertExists('userfiles/fonts/roboto/font.css');
        Storage::disk('s3')->assertExists('userfiles/fonts/roboto/x.woff2');
        $this->assertSame('FAKEWOFF2', Storage::disk('s3')->get('userfiles/fonts/roboto/x.woff2'));

        // It returns the bucket URL of font.css (so css_url points at S3).
        $this->assertStringStartsWith('https://bucket.example.test', (string) $remoteUrl);
        $this->assertStringContainsString('userfiles/fonts/roboto/font.css', (string) $remoteUrl);
    }

    public function test_font_files_are_not_mirrored_when_disk_is_local(): void
    {
        Storage::fake('s3', ['url' => 'https://bucket.example.test']);

        $manager = $this->makeManager([
            'disk' => 's3',
            'disk_is_remote' => false,
            'fonts_relpath' => 'userfiles/fonts',
        ]);

        $cssPath = $this->makeLocalFontFolder('roboto');
        $remoteUrl = $this->callMirror($manager, $cssPath);

        // Local-only install: nothing uploaded, css_url left untouched (null).
        $this->assertNull($remoteUrl);
        $this->assertSame([], Storage::disk('s3')->allFiles());
    }

    public function test_font_mirror_is_noop_without_fonts_relpath(): void
    {
        Storage::fake('s3', ['url' => 'https://bucket.example.test']);

        // Remote disk but no per-site fonts relpath configured -> skip safely.
        $manager = $this->makeManager([
            'disk' => 's3',
            'disk_is_remote' => true,
            'fonts_relpath' => '',
        ]);

        $cssPath = $this->makeLocalFontFolder('roboto');
        $this->assertNull($this->callMirror($manager, $cssPath));
        $this->assertSame([], Storage::disk('s3')->allFiles());
    }

    /**
     * Full flow: enabling a Google font on a remote install mirrors its folder
     * to S3 and persists the bucket URL as the font's css_url, so the compiled
     * stylesheet @imports the font from the CDN. A font folder is pre-seeded so
     * provider->enable() hits its local-cache branch (no network download).
     */
    public function test_enable_font_on_remote_disk_persists_s3_css_url(): void
    {
        Storage::fake('s3', ['url' => 'https://bucket.example.test']);

        $fontsPath = $this->tempCachePath . '/fonts';
        $manager = $this->makeManager([
            'download_google_fonts_locally' => true,
            'fonts_path' => $fontsPath,
            'fonts_url' => '/storage/fonts',
            'disk' => 's3',
            'disk_is_remote' => true,
            'fonts_relpath' => 'userfiles/fonts',
        ]);

        if (!$manager->tableReady()) {
            $this->markTestSkipped('template_fonts table not ready');
        }

        // Pre-seed the downloaded folder so enable() short-circuits to the local
        // cache hit instead of fetching from Google.
        @mkdir($fontsPath . '/roboto', 0755, true);
        file_put_contents($fontsPath . '/roboto/font.css',
            "@font-face{font-family:'Roboto';src:url(./r.woff2) format('woff2');}");
        file_put_contents($fontsPath . '/roboto/r.woff2', 'FAKE');

        $this->assertTrue($manager->enableFont('Roboto', 'google', 'sans-serif'));

        // The binaries + css are on the bucket, and the DB row points at S3.
        Storage::disk('s3')->assertExists('userfiles/fonts/roboto/font.css');
        Storage::disk('s3')->assertExists('userfiles/fonts/roboto/r.woff2');
        $this->assertDatabaseHas('template_fonts', [
            'family' => 'Roboto',
            'provider' => 'google',
            'css_url' => 'https://bucket.example.test/userfiles/fonts/roboto/font.css',
        ]);
    }

    /**
     * A CUSTOM uploaded font follows the identical mirror path: its binary +
     * generated font.css land locally, then get mirrored to the bucket and the
     * DB row's css_url points at S3.
     */
    public function test_upload_custom_font_on_remote_disk_mirrors_to_s3(): void
    {
        Storage::fake('s3', ['url' => 'https://bucket.example.test']);

        $fontsPath = $this->tempCachePath . '/fonts';
        $manager = $this->makeManager([
            'fonts_path' => $fontsPath,
            'fonts_url' => '/storage/fonts',
            'disk' => 's3',
            'disk_is_remote' => true,
            'fonts_relpath' => 'userfiles/fonts',
        ]);

        if (!$manager->tableReady()) {
            $this->markTestSkipped('template_fonts table not ready');
        }

        $file = UploadedFile::fake()->createWithContent('MyBrand.woff2', 'FAKEBINARY');
        $res = $manager->uploadCustomFont($file, 'MyBrand');
        $this->assertTrue($res['success'] ?? false, $res['message'] ?? '');

        $slug = 'mybrand';
        Storage::disk('s3')->assertExists("userfiles/fonts/{$slug}/font.css");
        Storage::disk('s3')->assertExists("userfiles/fonts/{$slug}/{$slug}.woff2");
        $this->assertDatabaseHas('template_fonts', [
            'family' => 'MyBrand',
            'provider' => 'custom',
            'css_url' => "https://bucket.example.test/userfiles/fonts/{$slug}/font.css",
        ]);
    }

    /**
     * Removing a font deletes its folder both locally AND on the bucket, so no
     * orphaned binaries are left behind on a remote install.
     */
    public function test_remove_font_purges_local_and_s3_copies(): void
    {
        Storage::fake('s3', ['url' => 'https://bucket.example.test']);

        $fontsPath = $this->tempCachePath . '/fonts';
        $manager = $this->makeManager([
            'fonts_path' => $fontsPath,
            'disk' => 's3',
            'disk_is_remote' => true,
            'fonts_relpath' => 'userfiles/fonts',
        ]);

        if (!$manager->tableReady()) {
            $this->markTestSkipped('template_fonts table not ready');
        }

        // Seed a local folder + a bucket copy + the DB row, as an enabled font.
        $cssPath = $this->makeLocalFontFolder('lato'); // under tempCachePath/lato
        // Point the font's local dir at fonts_path/lato so purge targets it.
        @mkdir($fontsPath . '/lato', 0755, true);
        file_put_contents($fontsPath . '/lato/font.css', 'x');
        file_put_contents($fontsPath . '/lato/l.woff2', 'x');
        Storage::disk('s3')->put('userfiles/fonts/lato/font.css', 'x');
        Storage::disk('s3')->put('userfiles/fonts/lato/l.woff2', 'x');

        TemplateFont::query()->create([
            'family' => 'Lato', 'provider' => 'google', 'is_enabled' => true,
            'css_path' => $fontsPath . '/lato/font.css',
            'css_url' => 'https://bucket.example.test/userfiles/fonts/lato/font.css',
            'sort_order' => 1,
        ]);

        $this->assertTrue($manager->removeFont('Lato', 'google'));

        // Local folder gone, bucket folder gone, row gone.
        $this->assertDirectoryDoesNotExist($fontsPath . '/lato');
        $this->assertSame([], Storage::disk('s3')->files('userfiles/fonts/lato'));
        $this->assertDatabaseMissing('template_fonts', ['family' => 'Lato', 'provider' => 'google']);
    }
}
