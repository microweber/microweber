<?php

declare(strict_types=1);

namespace Modules\Backup\Console\Commands;

use Illuminate\Console\Command;
use Modules\Content\Models\Content;

/**
 * Cycle-157 (2026-05-10) — Big2 demo-page seeder for mobile audits.
 *
 * Builds a single page that renders one block from every Big2 layout
 * category so agent-test (and any future mobile-audit pass) can hit a
 * single URL and measure touch targets / overflow / responsiveness on
 * every layout type without manually clicking through Live Edit's
 * "Insert Layout" picker for each category.
 *
 * The page content is just `<module type="layouts" template="<cat>/<skin>"/>`
 * markup — the Big2 template itself renders each block via the views
 * under `Templates/Big2/resources/views/modules/layouts/templates/`.
 *
 * The skin choice for each category prefers (in order):
 *   1. `default.blade.php`        (canonical default if present)
 *   2. `skin-1.blade.php`         (lowest-numbered skin)
 *   3. The first skin file alphabetically
 *
 * This deterministic preference keeps the demo page stable across runs
 * — re-running the command against the same Big2 install reproduces
 * the same layout list.
 *
 * The "wrapper" categories (`header`, `footers`, `menus`, `templates`)
 * are skipped by default — they are not free-standing layout blocks
 * (the master template embeds header + footer separately, and
 * `templates/` contains page-level fallbacks like `404.blade.php`).
 * Use `--include-wrappers` to include them anyway.
 *
 * Usage:
 *   php artisan mw:big2-demo-seed
 *   php artisan mw:big2-demo-seed --slug=big2-demo-mobile
 *   php artisan mw:big2-demo-seed --include-wrappers
 *   php artisan mw:big2-demo-seed --replace          # delete + recreate
 */
class Big2DemoSeedCommand extends Command
{
    protected $signature = 'mw:big2-demo-seed
        {--slug=big2-demo : Page slug (URL segment)}
        {--title=Big2 Demo — All Layouts : Page title}
        {--include-wrappers : Include header/footers/menus/templates wrappers (default: skip)}
        {--replace : If a page with this slug already exists, delete it first and recreate}';

    protected $description = 'Create a public demo page that renders one block from every Big2 layout category — for mobile-audit touch-target / overflow testing.';

    /** Wrapper-style category dirs that are NOT free-standing layouts. */
    private const WRAPPER_CATEGORIES = ['header', 'footers', 'menus', 'templates'];

    public function handle(): int
    {
        $templatesDir = base_path('Templates' . DIRECTORY_SEPARATOR . 'Big2'
            . DIRECTORY_SEPARATOR . 'resources'
            . DIRECTORY_SEPARATOR . 'views'
            . DIRECTORY_SEPARATOR . 'modules'
            . DIRECTORY_SEPARATOR . 'layouts'
            . DIRECTORY_SEPARATOR . 'templates');

        if (!is_dir($templatesDir)) {
            $this->error("Big2 layout templates directory not found: {$templatesDir}");
            $this->line('The Big2 template ships gitignored — make sure it is installed under Templates/Big2.');
            return self::FAILURE;
        }

        $includeWrappers = (bool) $this->option('include-wrappers');
        $categories = $this->discoverCategories($templatesDir, $includeWrappers);

        if ($categories === []) {
            $this->error('No layout categories found under ' . $templatesDir);
            return self::FAILURE;
        }

        $this->info('Discovered ' . count($categories) . ' Big2 layout categories.');

        $blocks = [];
        $picked = [];
        foreach ($categories as $category) {
            $skin = $this->pickRepresentativeSkin($templatesDir . DIRECTORY_SEPARATOR . $category);
            if ($skin === null) {
                $this->warn("  - {$category}: no .blade.php skins found, skipping.");
                continue;
            }
            $picked[$category] = $skin;
            // Section heading for human-readable page rendering, then the
            // module markup. The heading uses an .edit wrapper so live-edit
            // doesn't try to pick it up as a separate module.
            $blocks[] = sprintf(
                "<div class=\"big2-demo-section\" data-big2-demo-category=\"%s\">"
                . "<h2 class=\"big2-demo-section-heading\">Big2 layout: %s / %s</h2>"
                . "<module type=\"layouts\" template=\"%s/%s\" template-filter=\"%s\" />"
                . "</div>\n",
                e($category),
                e($category),
                e($skin),
                e($category),
                e($skin),
                e($category)
            );
            $this->line("  - {$category}: skin = {$skin}");
        }

        if ($blocks === []) {
            $this->error('No representative skins discovered. Aborting.');
            return self::FAILURE;
        }

        $slug = (string) $this->option('slug');
        $title = (string) $this->option('title');
        $replace = (bool) $this->option('replace');

        $existing = Content::query()->where('url', $slug)->where('content_type', 'page')->first();
        if ($existing !== null) {
            if (!$replace) {
                $this->warn("Page with slug '{$slug}' already exists (id={$existing->id}). Re-run with --replace to recreate, or --slug=other-slug.");
                $this->line("Existing URL: " . $this->resolvePublicUrl($existing));
                return self::SUCCESS;
            }
            $existing->delete();
            $this->line("Deleted existing page id={$existing->id} (--replace).");
        }

        $contentHtml = "<div class=\"big2-demo-page\">\n"
            . "<div class=\"big2-demo-intro\"><h1>Big2 Demo — All Layouts</h1>"
            . "<p>Auto-generated by <code>mw:big2-demo-seed</code> at " . now()->toIso8601String() . ". "
            . "One block per Big2 layout category for mobile touch-target + overflow audits.</p></div>\n"
            . implode("\n", $blocks)
            . "\n</div>";

        $page = new Content();
        $page->title = $title;
        $page->url = $slug;
        $page->content_type = 'page';
        $page->subtype = 'static';
        $page->parent = 0;
        $page->is_active = 1;
        $page->is_deleted = 0;
        $page->is_home = 0;
        $page->content = $contentHtml;
        $page->description = 'Auto-generated demo page rendering one block from every Big2 layout category. For mobile-audit testing only.';
        $page->save();

        $publicUrl = $this->resolvePublicUrl($page);

        $this->newLine();
        $this->info("Big2 demo page created.");
        $this->table(
            ['Field', 'Value'],
            [
                ['id', (string) $page->id],
                ['title', $page->title],
                ['slug', $page->url],
                ['categories', (string) count($picked)],
                ['public URL', $publicUrl],
            ]
        );
        $this->newLine();
        $this->line('Add ?nocache=1 when verifying in a fresh browser:');
        $this->line('  ' . $publicUrl . '?nocache=1');

        return self::SUCCESS;
    }

    /**
     * Return sorted list of layout-category directory names found under
     * the Big2 templates dir, optionally including wrappers.
     *
     * @return array<int, string>
     */
    private function discoverCategories(string $templatesDir, bool $includeWrappers): array
    {
        $entries = scandir($templatesDir);
        if ($entries === false) {
            return [];
        }
        $categories = [];
        foreach ($entries as $entry) {
            if ($entry === '.' || $entry === '..') continue;
            $full = $templatesDir . DIRECTORY_SEPARATOR . $entry;
            if (!is_dir($full)) continue;
            if (!$includeWrappers && in_array($entry, self::WRAPPER_CATEGORIES, true)) continue;
            $categories[] = $entry;
        }
        sort($categories);
        return $categories;
    }

    /**
     * Pick the representative skin file for a category.
     *
     *   1. default.blade.php if present
     *   2. lowest-numbered skin-N.blade.php
     *   3. first .blade.php alphabetically
     */
    private function pickRepresentativeSkin(string $categoryDir): ?string
    {
        if (!is_dir($categoryDir)) return null;
        $files = scandir($categoryDir);
        if ($files === false) return null;

        $blades = [];
        foreach ($files as $f) {
            if (str_ends_with($f, '.blade.php')) {
                $blades[] = substr($f, 0, -strlen('.blade.php'));
            }
        }
        if ($blades === []) return null;

        if (in_array('default', $blades, true)) return 'default';

        $numbered = array_filter($blades, static fn (string $name) =>
            (bool) preg_match('/^skin-(\d+)$/', $name)
        );
        if ($numbered !== []) {
            usort($numbered, static function (string $a, string $b): int {
                preg_match('/^skin-(\d+)$/', $a, $ma);
                preg_match('/^skin-(\d+)$/', $b, $mb);
                return ((int) $ma[1]) <=> ((int) $mb[1]);
            });
            return $numbered[0];
        }

        sort($blades);
        return $blades[0];
    }

    private function resolvePublicUrl(Content $page): string
    {
        // content_link() can return either an absolute URL or just the
        // slug depending on environment configuration. Prefer it if it
        // gives us an absolute URL, else fall back to site_url() + slug.
        if (function_exists('content_link')) {
            try {
                $link = content_link((int) $page->id);
                if (is_string($link) && $link !== '' && preg_match('#^https?://#i', $link)) {
                    return $link;
                }
            } catch (\Throwable) {
                // fall through
            }
        }
        $base = function_exists('site_url') ? rtrim((string) site_url(), '/') : rtrim((string) url('/'), '/');
        return $base . '/' . ltrim((string) $page->url, '/');
    }
}
