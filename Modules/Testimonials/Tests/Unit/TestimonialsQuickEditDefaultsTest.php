<?php

namespace Modules\Testimonials\Tests\Unit;

use Illuminate\Http\Request;
use Modules\Testimonials\Http\Controllers\TestimonialItemsController;
use Modules\Testimonials\Models\Testimonial;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Regression for the quick-edit "edit testimonials shows nothing" fix
 * (ModuleItemsController::seedDefaultsIfEmpty). The inline item panel reads
 * rows scoped to the module's rel_id; when that rel_id has no rows yet the
 * endpoint must materialise the module's default_content.json so the panel
 * shows the SAME defaults the module renders, instead of an empty list — while
 * respecting the module's one-time seed guard so cleared content never comes
 * back and defaults are never double-seeded.
 */
class TestimonialsQuickEditDefaultsTest extends TestCase
{
    private function openPanel(string $relId): array
    {
        $controller = new TestimonialItemsController();
        $response = $controller->index(
            Request::create('/api/testimonial-items', 'GET', ['rel_id' => $relId])
        );

        return json_decode((string) $response->getContent(), true)['items'] ?? [];
    }

    private function cleanup(string $relId): void
    {
        Testimonial::where('rel_type', 'module')->where('rel_id', $relId)->delete();
        if (function_exists('delete_option')) {
            delete_option('getTestimonialsCreatedDefault', $relId);
        }
    }

    #[Test]
    public function quick_edit_seeds_the_modules_default_testimonials_for_a_fresh_rel_id(): void
    {
        $relId = 'qs-defaults-' . uniqid();
        $this->cleanup($relId);

        try {
            $items = $this->openPanel($relId);
            $this->assertCount(
                4,
                $items,
                'A fresh testimonials module must seed its 4 default items into the quick-edit panel.'
            );
            $this->assertSame('Wilma Hover', $items[0]['name'] ?? null);

            // Idempotent — reopening the panel does not double-seed.
            $this->assertCount(4, $this->openPanel($relId));

            // Guard — after the operator clears every item, reopening must NOT
            // resurrect the defaults (the module's get<X>CreatedDefault guard).
            Testimonial::where('rel_type', 'module')->where('rel_id', $relId)->delete();
            $this->assertCount(
                0,
                $this->openPanel($relId),
                'Cleared items must stay cleared — the seed guard must prevent re-seeding.'
            );
        } finally {
            $this->cleanup($relId);
        }
    }

    #[Test]
    public function quick_edit_returns_existing_rows_untouched_and_does_not_seed_over_them(): void
    {
        $relId = 'qs-existing-' . uniqid();
        $this->cleanup($relId);

        try {
            Testimonial::create([
                'name' => 'Only One',
                'content' => 'Single existing testimonial.',
                'rel_id' => $relId,
                'rel_type' => 'module',
            ]);

            $items = $this->openPanel($relId);
            $this->assertCount(1, $items, 'A module that already has rows must not be seeded with defaults.');
            $this->assertSame('Only One', $items[0]['name'] ?? null);
        } finally {
            $this->cleanup($relId);
        }
    }
}
