<?php

namespace Modules\WordPressMigration\Services\Taxonomy;

/**
 * Result of {@see TaxonomyIndex::prime()}: a pure value object
 * holding WP-slug → Microweber-local-id maps for categories, tags,
 * and users.
 *
 * The mapper consumes these three maps on every post insert so it
 * can attach `categories_items` / `tagging_tagged` rows (and set
 * `content.created_by`) without issuing another round of lookups —
 * which is the whole point of a taxonomy-first pass.
 *
 * Slug, not name, is the canonical key: WP emits both for every
 * term and slug is unique within a taxonomy. Matching on titles
 * would fail on rename or on diacritic-sensitive collations.
 *
 * A missing entry returns null. Callers must handle that case —
 * it means either the origin site returned a term that wasn't in
 * the primed set (rare, possible on mid-run taxonomy edits) or
 * the prime step was given the wrong collection.
 */
final class TaxonomyLookup
{
    /**
     * @param array<string, int> $categoriesBySlug
     * @param array<string, int> $tagsBySlug
     * @param array<string, int> $usersBySlug
     */
    public function __construct(
        public readonly array $categoriesBySlug = [],
        public readonly array $tagsBySlug = [],
        public readonly array $usersBySlug = [],
    ) {}

    public function categoryLocalId(string $slug): ?int
    {
        return $this->categoriesBySlug[$slug] ?? null;
    }

    public function tagLocalId(string $slug): ?int
    {
        return $this->tagsBySlug[$slug] ?? null;
    }

    public function userLocalId(string $slug): ?int
    {
        return $this->usersBySlug[$slug] ?? null;
    }

    public static function empty(): self
    {
        return new self();
    }
}
