<?php

namespace MicroweberPackages\Template\tests;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Covers the PHP-less child-template format used by "export a Live-Edit site as
 * a reusable template": a template that contains NO .php files at all (no
 * config.php, no ServiceProvider, no blade views) — only JSON manifests
 * (config.json / module.json / composer.json), design assets and content.json —
 * and inherits every executable behaviour from its parent_template (Base).
 *
 * This pins the two framework hooks that make that format work:
 *   1. getConfig() reads config.json when no PHP config is present.
 *   2. a view-less child inherits the parent's views (clean/post/…) via
 *      resolveParentTemplateViewFile, so pages still render through Base.
 *
 * The fixture is created on disk under Templates/ for the test and removed
 * after, so no PHP-less template is committed to the repo.
 */
class PhplessTemplateTest extends TestCase
{
    private string $tpl = 'ZzPhplessFixture';
    private string $dir = '';

    protected function setUp(): void
    {
        parent::setUp();

        $this->dir = rtrim(base_path('Templates/' . $this->tpl), '/') . '/';
        @mkdir($this->dir . 'resources/assets/design-styles', 0755, true);

        // JSON-only manifests — deliberately NO .php files anywhere.
        file_put_contents($this->dir . 'config.json', json_encode([
            'name' => $this->tpl,
            'parent_template' => 'Base',
            'version' => '1.0.0',
        ]));
        file_put_contents($this->dir . 'module.json', json_encode([
            'name' => $this->tpl,
            'alias' => strtolower($this->tpl),
            'providers' => [],
            'files' => [],
        ]));
        file_put_contents($this->dir . 'composer.json', json_encode([
            'name' => 'microweber-template/' . strtolower($this->tpl),
            'type' => 'microweber-template',
            'extra' => ['laravel' => ['providers' => []]],
        ]));
        file_put_contents(
            $this->dir . 'resources/assets/design-styles/design-vars.css',
            ":root{--mw-heading-font-family:'Poppins',sans-serif}"
        );
    }

    protected function tearDown(): void
    {
        if ($this->dir && is_dir($this->dir)) {
            $it = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($this->dir, \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($it as $f) {
                $f->isDir() ? @rmdir($f->getPathname()) : @unlink($f->getPathname());
            }
            @rmdir(rtrim($this->dir, '/'));
        }
        // The templates repository caches the scanned template list; if a later
        // boot sees this now-deleted fixture in a stale cache, createModule()
        // returns null and throws. Flush so the fixture never outlives the dir.
        try {
            \Illuminate\Support\Facades\Cache::flush();
        } catch (\Throwable) {
        }
        parent::tearDown();
    }

    private function adapter()
    {
        return app()->template_manager->templateAdapter;
    }

    #[Test]
    public function the_fixture_contains_no_php_files(): void
    {
        $php = [];
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->dir, \FilesystemIterator::SKIP_DOTS)) as $f) {
            if (preg_match('/\.(php|phtml|phar|php\d)$/i', $f->getFilename())) {
                $php[] = $f->getPathname();
            }
        }
        $this->assertSame([], $php, 'A PHP-less template must contain no PHP files.');
    }

    #[Test]
    public function getConfig_reads_config_json_when_there_is_no_php_config(): void
    {
        $config = app()->template_manager->get_config($this->tpl);

        $this->assertIsArray($config);
        $this->assertSame($this->tpl, $config['name']);
        $this->assertSame('Base', $config['parent_template'] ?? null,
            'parent_template must be read from config.json so rendering can fall back to Base.');
    }

    #[Test]
    public function a_view_less_child_inherits_the_parent_clean_view(): void
    {
        // A content_type=page with no page.blade.php anywhere falls back to the
        // parent's clean.blade.php (what the child's own clean would have given).
        $view = $this->adapter()->resolveParentTemplateViewFile($this->tpl, 'clean.blade.php');

        $this->assertIsString($view);
        $this->assertStringEndsWith('Templates/Base/resources/views/clean.blade.php', str_replace('\\', '/', (string) $view));
    }

    #[Test]
    public function a_view_less_child_inherits_the_parent_content_type_view(): void
    {
        // A post inherits Base's post.blade.php directly.
        $view = $this->adapter()->resolveParentTemplateViewFile($this->tpl, 'post.blade.php', 'post');

        $this->assertIsString($view);
        $this->assertStringEndsWith('Templates/Base/resources/views/post.blade.php', str_replace('\\', '/', (string) $view));
    }
}
