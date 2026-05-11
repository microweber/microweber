<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Eager-first-image regression coverage.
 *
 * Pins:
 *   - The `responsive_thumbnail()` helper consults a request-scoped
 *     counter and emits `loading="eager"` for the first 2 images
 *     (default; configurable via `eager_first_n`), `loading="lazy"`
 *     for the rest. Explicit caller-side `loading` overrides win.
 *   - The Pictures gallery skin-14 first image carries the explicit
 *     `loading="{{ $loop->first ? 'eager' : 'lazy' }}"` shape.
 *   - The Newsletter choose-template-modal first preview ditto.
 *
 * Contract test style: file-system reads only, no DB touch.
 */
class EagerFirstImageContractTest extends TestCase
{
    private const HELPER     = 'Modules/Media/Support/media_functions.php';
    private const PICTURES   = 'Modules/Pictures/resources/views/templates/skin-14.blade.php';
    private const NEWSLETTER = 'Modules/Newsletter/resources/views/livewire/admin/choose-template-modal.blade.php';

    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function responsive_thumbnail_helper_carries_eager_first_n_logic(): void
    {
        $src = $this->read(self::HELPER);

        $this->assertStringContainsString(
            'AI-115 / TICKET-CG (cycle-105',
            $src,
            self::HELPER . ': must carry the eager-first-image audit-trail marker'
        );

        // Default eager_first_n = 2 (covers a typical 4-card product
        // grid above the fold on a 1280×720 viewport).
        $this->assertStringContainsString(
            "isset(\$options['eager_first_n'])",
            $src,
            'responsive_thumbnail(): must read $options[\'eager_first_n\']'
        );
        $this->assertStringContainsString(
            ': 2',
            $src,
            'responsive_thumbnail(): default eager_first_n fallback must be 2'
        );

        // Request-scoped counter (static $renderedCount).
        $this->assertStringContainsString(
            'static $renderedCount = 0',
            $src,
            'responsive_thumbnail(): must use a static $renderedCount counter'
        );
        $this->assertStringContainsString(
            '$renderedCount++',
            $src,
            'responsive_thumbnail(): must increment the static counter on every call'
        );

        // Explicit caller-side loading still wins over the default.
        $this->assertStringContainsString(
            "\$options['loading'] ?? \$defaultLoading",
            $src,
            'responsive_thumbnail(): caller-side `loading` must win over $defaultLoading via the ?? coalesce'
        );
    }

    #[Test]
    public function pictures_gallery_first_image_uses_loop_first_eager(): void
    {
        $src = $this->read(self::PICTURES);

        $this->assertStringContainsString(
            "'loading' => \$loop->first ? 'eager' : 'lazy'",
            $src,
            self::PICTURES . ": gallery first image must carry the explicit `\$loop->first ? 'eager' : 'lazy'` ternary"
        );
    }

    #[Test]
    public function newsletter_choose_template_first_preview_uses_loop_first_eager(): void
    {
        $src = $this->read(self::NEWSLETTER);

        $this->assertStringContainsString(
            'loading="{{ $loop->first ? \'eager\' : \'lazy\' }}"',
            $src,
            self::NEWSLETTER . ': first email-template preview must use {{ $loop->first ? \'eager\' : \'lazy\' }}'
        );
    }
}
