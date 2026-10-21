<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-06-AI824 + task-2026-06-06-AI823 — ESE copy clarity.
 *
 * AI-824 (Border): the "Sides" selector already names the side the controls
 * below apply to, so the per-side prefix on Style / Size / Color ("Top Border
 * Style" …) was redundant. "Sides" → "Apply to"; dependent labels → "Style" /
 * "Size" / "Color".
 *
 * AI-823 (Background image picker): the affordance must read "Add" on an empty
 * field and only say "Replace" once an image is set — saying "Replace" when
 * empty is wrong. The tooltip is now state-dependent.
 */
class EseAI824AI823BorderBackgroundCopyContractTest extends TestCase
{
    private function read(string $rel): string
    {
        return (string) file_get_contents(base_path($rel));
    }


    #[Test]
    public function background_image_tooltip_is_state_dependent(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/components/ImagePicker.vue');

        $this->assertStringContainsString(
            "selectedFile ? 'Replace background image' : 'Add background image'",
            $src,
            'The image-picker tooltip must say "Add" when empty and "Replace" only when an image is set.'
        );
        // The old always-on neutral tooltip attribute must be gone.
        $this->assertStringNotContainsString('data-tip="Select background image"', $src,
            'The static "Select background image" tooltip must be replaced by the state-dependent one.');
    }

    #[Test]
    public function the_changes_are_present_in_the_built_ese_bundle(): void
    {
        $bundle = base_path('public/vendor/microweber-packages/frontend-assets/build/element-style-editor-app.js');
        if (! is_file($bundle)) {
            $this->markTestSkipped('Built ESE bundle not present.');
        }
        $js = (string) file_get_contents($bundle);
        $this->assertStringContainsString('Apply to', $js, 'Built bundle must carry the "Apply to" label.');
        $this->assertStringContainsString('Replace background image', $js, 'Built bundle must carry the state-dependent tooltip.');
        $this->assertStringNotContainsString('Top Border Style', $js, 'Built bundle must not carry the stale per-side label.');
    }
}
