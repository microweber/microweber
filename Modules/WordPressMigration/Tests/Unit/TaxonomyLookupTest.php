<?php

namespace Modules\WordPressMigration\Tests\Unit;

use Modules\WordPressMigration\Services\Taxonomy\TaxonomyLookup;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Pure unit coverage for the {@see TaxonomyLookup} value object.
 *
 * The lookup is the contract between the taxonomy-first pass and
 * the content mapper: get a slug in, get null or a local id out.
 * These tests pin the three accessors plus the empty() factory so
 * the mapper can use it without a second null-check.
 */
class TaxonomyLookupTest extends TestCase
{
    #[Test]
    public function known_slugs_resolve_to_local_ids(): void
    {
        $lookup = new TaxonomyLookup(
            categoriesBySlug: ['news' => 42, 'tech' => 43],
            tagsBySlug: ['featured' => 100],
            usersBySlug: ['jane-doe' => 7],
        );

        $this->assertSame(42, $lookup->categoryLocalId('news'));
        $this->assertSame(43, $lookup->categoryLocalId('tech'));
        $this->assertSame(100, $lookup->tagLocalId('featured'));
        $this->assertSame(7, $lookup->userLocalId('jane-doe'));
    }

    #[Test]
    public function unknown_slugs_return_null_on_every_axis(): void
    {
        // The mapper uses null to mean "don't attach" — so a missing
        // slug must NEVER resolve to a zero or a fabricated id,
        // otherwise the mapper would attach a post to a non-existent
        // taxonomy row.
        $lookup = new TaxonomyLookup(
            categoriesBySlug: ['news' => 42],
            tagsBySlug: ['featured' => 100],
            usersBySlug: ['jane-doe' => 7],
        );

        $this->assertNull($lookup->categoryLocalId('missing'));
        $this->assertNull($lookup->tagLocalId('missing'));
        $this->assertNull($lookup->userLocalId('missing'));
    }

    #[Test]
    public function empty_factory_returns_lookup_that_resolves_nothing(): void
    {
        // The runner uses TaxonomyLookup::empty() when it deliberately
        // skips priming (e.g. a dry-run import). All accessors must
        // return null rather than throwing — otherwise the mapper's
        // attach path would need an extra if-set-else branch.
        $lookup = TaxonomyLookup::empty();

        $this->assertNull($lookup->categoryLocalId('anything'));
        $this->assertNull($lookup->tagLocalId('anything'));
        $this->assertNull($lookup->userLocalId('anything'));
        $this->assertSame([], $lookup->categoriesBySlug);
        $this->assertSame([], $lookup->tagsBySlug);
        $this->assertSame([], $lookup->usersBySlug);
    }
}
