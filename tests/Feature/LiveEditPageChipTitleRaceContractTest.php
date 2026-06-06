<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-06-pchiprace — the Live Edit toolbar PageChip showed the
 * hardcoded "Homepage" fallback for every non-home page.
 *
 * Root cause (browser-reproduced): readCurrentPageTitle() runs on mount() and
 * on the first liveEditCanvasLoaded event, but mw.top().app.canvas
 * .getLiveEditData() is frequently still empty at that instant (the canvas
 * content data populates a beat later). The single early read found nothing and
 * never re-ran, so currentPageTitleFull stayed empty and the template fell back
 * to 'Homepage'. Re-firing the event manually corrected the chip to the real
 * title — confirming a timing race, not a data problem.
 *
 * Fix: readCurrentPageTitle() now retries a bounded number of times until the
 * canvas data resolves.
 */
class LiveEditPageChipTitleRaceContractTest extends TestCase
{
    private string $src;

    protected function setUp(): void
    {
        parent::setUp();
        $this->src = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/components/Toolbar/PageChip.vue'
        ));
    }

    #[Test]
    public function read_current_page_title_accepts_a_retry_counter(): void
    {
        $this->assertMatchesRegularExpression(
            '/readCurrentPageTitle\(\s*retriesLeft\s*\)/',
            $this->src,
            'readCurrentPageTitle must take a retriesLeft argument so it can re-poll for the title.'
        );
    }

    #[Test]
    public function it_reschedules_itself_when_the_title_is_not_yet_available(): void
    {
        // A bounded retry: setTimeout that calls readCurrentPageTitle with a
        // decremented counter.
        $this->assertMatchesRegularExpression(
            '/setTimeout\(\s*function\s*\(\)\s*\{\s*self\.readCurrentPageTitle\(\s*retriesLeft\s*-\s*1\s*\)/',
            $this->src,
            'When the title is not yet available, the read must reschedule itself with retriesLeft - 1.'
        );
        $this->assertMatchesRegularExpression(
            '/if\s*\(\s*retriesLeft\s*>\s*0\s*\)/',
            $this->src,
            'The retry must be bounded by retriesLeft > 0.'
        );
    }

    #[Test]
    public function the_built_bundle_carries_the_retry_logic(): void
    {
        // PageChip compiles into the live-edit toolbar bundle(s). At least one
        // built bundle must carry the retry signature.
        $dir = base_path('public/vendor/microweber-packages/frontend-assets/build');
        if (! is_dir($dir)) {
            $this->markTestSkipped('Built bundle dir not present.');
        }
        $found = false;
        foreach (glob($dir . '/*.js') ?: [] as $f) {
            $js = (string) file_get_contents($f);
            if (str_contains($js, 'readCurrentPageTitle')) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, 'A built bundle must contain the PageChip readCurrentPageTitle logic.');
    }
}
