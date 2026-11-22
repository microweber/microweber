<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Tests\Feature;

use MicroweberPackages\TemplateCustomCss\Services\LiveEditCssManager;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * "Reset theme styles" must reset the page to the ORIGINAL design — that means
 * clearing BOTH the Live-Edit custom CSS and the custom JS (AI animations /
 * interactivity), and emptying the remote (S3/CDN) copies, not just renaming the
 * local CSS file. A CSS-only reset left the custom JavaScript running on the
 * published page and the old files on the bucket.
 *
 * Source-contract (the manager boots a DB in a full suite run; this pins the
 * reset wiring in source so it can't silently regress to CSS-only).
 */
final class LiveEditResetClearsCssAndJsTest extends TestCase
{
    private function source(): string
    {
        $ref = new \ReflectionClass(LiveEditCssManager::class);

        return (string) file_get_contents($ref->getFileName());
    }

    #[Test]
    public function remove_clears_the_js_layer_not_only_css(): void
    {
        $src = $this->source();
        $pos = strpos($src, 'public function remove(');
        $this->assertNotFalse($pos, 'LiveEditCssManager::remove() must exist.');
        $body = substr($src, $pos, 4200);

        $this->assertStringContainsString('getLiveEditJsFilename', $body,
            'remove() must also target the live_edit.js file, not only the CSS.');
        $this->assertStringContainsString("'live_edit_js'", $body,
            'remove() must clear the stored template_js option for the JS layer.');
    }

    #[Test]
    public function remove_clears_the_remote_copies(): void
    {
        $src = $this->source();
        $pos = strpos($src, 'public function remove(');
        $body = substr($src, $pos, 4200);

        // Empty the S3/CDN CSS + JS copies so nothing stale is served on an
        // S3-backed install (the local rename alone leaves the bucket untouched).
        $this->assertStringContainsString('mirrorToRemoteDisk', $body,
            'remove() must empty the remote CSS copy.');
        $this->assertStringContainsString('mirrorJsToRemoteDisk', $body,
            'remove() must empty the remote JS copy.');
    }
}
