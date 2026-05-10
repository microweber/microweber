<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-157 (2026-05-10) — Big2 demo-page seeder contract.
 *
 * The `mw:big2-demo-seed` command (`Modules\Backup\Console\Commands\
 * Big2DemoSeedCommand`) builds a public Page that renders one block
 * from every Big2 layout category so mobile-audit testers can hit a
 * single URL and measure touch targets / overflow without manually
 * inserting each layout via Live Edit.
 *
 * This contract pins the load-bearing pieces of the command source so
 * future refactors that accidentally drop the slug-default, the
 * wrapper-skip list, the deterministic skin-pick preference, or the
 * service-provider registration get caught.
 */
class Big2DemoSeedCommandContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function command_file_exists_and_carries_anchor(): void
    {
        $src = $this->read('Modules/Backup/Console/Commands/Big2DemoSeedCommand.php');
        $this->assertStringContainsString('Cycle-157', $src,
            'Big2DemoSeedCommand.php MUST carry the cycle-157 anchor.');
        $this->assertStringContainsString('mw:big2-demo-seed', $src,
            'Big2DemoSeedCommand.php MUST declare the canonical command '
            . 'signature `mw:big2-demo-seed`.');
    }

    #[Test]
    public function command_signature_and_options_are_pinned(): void
    {
        $src = $this->read('Modules/Backup/Console/Commands/Big2DemoSeedCommand.php');
        // The command MUST expose --slug, --title, --include-wrappers
        // and --replace options. PM email + agent-test workflow
        // depend on these flags.
        $this->assertMatchesRegularExpression('/--slug=/', $src,
            'Command MUST expose --slug option for custom slugs.');
        $this->assertMatchesRegularExpression('/--title=/', $src,
            'Command MUST expose --title option for custom titles.');
        $this->assertMatchesRegularExpression('/--include-wrappers/', $src,
            'Command MUST expose --include-wrappers to optionally '
            . 'include header/footers/menus/templates.');
        $this->assertMatchesRegularExpression('/--replace/', $src,
            'Command MUST expose --replace so re-runs against the same '
            . 'slug recreate the page instead of erroring.');
    }

    #[Test]
    public function wrapper_categories_are_skipped_by_default(): void
    {
        $src = $this->read('Modules/Backup/Console/Commands/Big2DemoSeedCommand.php');
        // header / footers / menus / templates are NOT free-standing
        // layouts (master template embeds header + footer separately,
        // templates/ contains 404 fallbacks etc).
        $this->assertMatchesRegularExpression(
            '/WRAPPER_CATEGORIES\s*=\s*\[[^]]*[\'"]header[\'"][^]]*[\'"]footers[\'"][^]]*[\'"]menus[\'"][^]]*[\'"]templates[\'"]/m',
            $src,
            'Command MUST declare WRAPPER_CATEGORIES with header/'
            . 'footers/menus/templates so the demo page does not embed '
            . 'wrapper-style layouts inline by default.'
        );
    }

    #[Test]
    public function skin_pick_prefers_default_then_lowest_skin(): void
    {
        $src = $this->read('Modules/Backup/Console/Commands/Big2DemoSeedCommand.php');
        // The pickRepresentativeSkin algorithm MUST prefer
        // `default.blade.php` first so the demo page is stable across
        // re-runs and matches what a typical Big2 install ships with.
        $this->assertMatchesRegularExpression(
            '/in_array\([\'"]default[\'"]\s*,\s*\$blades\s*,\s*true\)\s*\)\s*return\s+[\'"]default[\'"]/m',
            $src,
            'pickRepresentativeSkin MUST return "default" when a '
            . 'default.blade.php is present so re-runs are deterministic.'
        );
        // Then lowest-numbered skin-N
        $this->assertMatchesRegularExpression(
            '/preg_match\([\'"][^\'"]*skin-\(\\\\d\+\)[^\'"]*[\'"]/m',
            $src,
            'pickRepresentativeSkin MUST regex-match skin-N filenames '
            . 'and return the lowest-numbered one as the next '
            . 'preference.'
        );
    }

    #[Test]
    public function module_markup_uses_layouts_with_template_filter(): void
    {
        $src = $this->read('Modules/Backup/Console/Commands/Big2DemoSeedCommand.php');
        // The block markup MUST be `<module type="layouts" template="<cat>/<skin>"
        // template-filter="<cat>" />` — the canonical live-edit
        // module-tag form recognized by the layouts module renderer
        // (matches Templates/Big2/.../master.blade.php pattern).
        $this->assertMatchesRegularExpression(
            '/<module type=\\\\?"layouts\\\\?"[^>]*template=\\\\?"%s\/%s\\\\?"[^>]*template-filter=\\\\?"%s\\\\?"/m',
            $src,
            'Block markup MUST use `<module type="layouts" template='
            . '"<cat>/<skin>" template-filter="<cat>" />` so the '
            . 'layouts module renderer picks up the right view.'
        );
    }

    #[Test]
    public function command_is_registered_in_service_provider(): void
    {
        $provider = $this->read('Modules/Backup/Providers/BackupServiceProvider.php');
        $this->assertStringContainsString('Big2DemoSeedCommand::class', $provider,
            'BackupServiceProvider MUST register Big2DemoSeedCommand '
            . 'via the $this->commands([...]) array, otherwise '
            . '`php artisan list` will not surface the command.');
        $this->assertStringContainsString('use Modules\Backup\Console\Commands\Big2DemoSeedCommand;', $provider,
            'BackupServiceProvider MUST import the Big2DemoSeedCommand '
            . 'class.');
    }

    #[Test]
    public function command_uses_content_model_directly(): void
    {
        $src = $this->read('Modules/Backup/Console/Commands/Big2DemoSeedCommand.php');
        // The command MUST instantiate Content directly — no
        // hand-rolled DB::table() inserts that bypass model events
        // (HasSlug / search / created-by traits depend on save()).
        $this->assertStringContainsString('use Modules\Content\Models\Content;', $src,
            'Command MUST import the Content model.');
        $this->assertMatchesRegularExpression(
            '/new\s+Content\(\)/m',
            $src,
            'Command MUST instantiate Content via `new Content()` so '
            . 'model events (HasSlug, etc.) fire on save().'
        );
    }
}
