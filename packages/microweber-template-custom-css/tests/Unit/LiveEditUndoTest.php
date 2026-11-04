<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Tests\Unit;

use MicroweberPackages\TemplateCustomCss\Contracts\SnapshotStoreInterface;
use MicroweberPackages\TemplateCustomCss\Services\CssUrlRewriter;
use MicroweberPackages\TemplateCustomCss\Services\CssValidator;
use MicroweberPackages\TemplateCustomCss\Services\LiveEditCssManager;
use MicroweberPackages\TemplateCustomCss\Support\ArrayOptionStore;
use MicroweberPackages\TemplateCustomCss\Tests\TestCase;

/** In-memory snapshot store with the same bounded-history semantics as the DB one. */
class ArraySnapshotStore implements SnapshotStoreInterface
{
    /** @var array<string, list<string>> */
    public array $stacks = [];

    public function __construct(private int $max = 1000) {}

    private function key(string $t, string $y): string { return $t . '|' . $y; }

    public function snapshot(string $template, string $type, string $content): void
    {
        $k = $this->key($template, $type);
        $this->stacks[$k][] = $content;
        if (count($this->stacks[$k]) > $this->max) {
            $this->stacks[$k] = array_slice($this->stacks[$k], -$this->max);
        }
    }

    public function undo(string $template, string $type): ?string
    {
        $k = $this->key($template, $type);
        if (empty($this->stacks[$k])) {
            return null;
        }
        return array_pop($this->stacks[$k]);
    }

    public function count(string $template, string $type): int
    {
        return count($this->stacks[$this->key($template, $type)] ?? []);
    }
}

class LiveEditUndoTest extends TestCase
{
    protected function makeManager(ArraySnapshotStore $snap): LiveEditCssManager
    {
        $base = $this->tempCssPath !== '' ? $this->tempCssPath : sys_get_temp_dir() . '/mw-undo-' . uniqid();
        @mkdir($base . '/css', 0755, true);
        $config = [
            'css_base_path' => $base . '/css',
            'css_base_url' => '/storage/css',
            'userfiles_url' => 'http://example.test/userfiles/',
            'default_template' => 'test-theme',
            'validate_on_save' => false,
            'multisite' => false,
            'live_edit_option_key' => 'template_css',
            'live_edit_option_group_prefix' => 'template_',
            'file_types' => ['live_edit' => ['filename' => 'live_edit.css', 'validate' => false, 'rewrite_urls' => false, 'multisite' => false]],
        ];
        return new LiveEditCssManager($config, new ArrayOptionStore(), new CssValidator(true), new CssUrlRewriter((string) $config['userfiles_url']), $snap);
    }

    public function test_css_changes_snapshot_and_undo_walks_back(): void
    {
        $snap = new ArraySnapshotStore();
        $m = $this->makeManager($snap);

        $m->saveLiveEditCssContent('h1{color:red}', 'Bootstrap');    // first: no prior -> no snapshot
        $m->saveLiveEditCssContent('h1{color:blue}', 'Bootstrap');   // snapshots "red"
        $m->saveLiveEditCssContent('h1{color:green}', 'Bootstrap');  // snapshots "blue"

        $this->assertSame(2, $m->undoCount('css', 'Bootstrap'));
        $this->assertStringContainsString('green', $m->getContent('Bootstrap'));

        $this->assertStringContainsString('blue', (string) $m->undoLiveEdit('css', 'Bootstrap'));
        $this->assertStringContainsString('blue', $m->getContent('Bootstrap'));

        $this->assertStringContainsString('red', (string) $m->undoLiveEdit('css', 'Bootstrap'));
        $this->assertStringContainsString('red', $m->getContent('Bootstrap'));

        $this->assertSame(0, $m->undoCount('css', 'Bootstrap'));
        $this->assertNull($m->undoLiveEdit('css', 'Bootstrap')); // nothing left to undo
    }

    public function test_history_is_capped(): void
    {
        $snap = new ArraySnapshotStore(1000);
        $m = $this->makeManager($snap);
        for ($i = 0; $i <= 1005; $i++) {
            $m->saveLiveEditCssContent('h1{--n:' . $i . '}', 'Bootstrap');
        }
        $this->assertLessThanOrEqual(1000, $m->undoCount('css', 'Bootstrap'));
    }

    public function test_no_snapshot_store_means_no_undo(): void
    {
        $base = sys_get_temp_dir() . '/mw-undo-none-' . uniqid();
        @mkdir($base . '/css', 0755, true);
        $config = [
            'css_base_path' => $base . '/css', 'userfiles_url' => '', 'default_template' => 't',
            'validate_on_save' => false, 'multisite' => false,
            'live_edit_option_key' => 'template_css', 'live_edit_option_group_prefix' => 'template_',
            'file_types' => ['live_edit' => ['filename' => 'live_edit.css', 'validate' => false, 'rewrite_urls' => false]],
        ];
        $m = new LiveEditCssManager($config, new ArrayOptionStore(), new CssValidator(true), new CssUrlRewriter(''));
        $m->saveLiveEditCssContent('h1{color:red}', 'Bootstrap');
        $this->assertSame(0, $m->undoCount('css', 'Bootstrap'));
        $this->assertNull($m->undoLiveEdit('css', 'Bootstrap'));
    }
}
