<?php

declare(strict_types=1);

namespace MicroweberPackages\FilamentRegistry\GlobalSearch;

use Filament\Facades\Filament;
use Filament\GlobalSearch\GlobalSearchResult;
use Filament\GlobalSearch\GlobalSearchResults;
use Filament\GlobalSearch\Providers\Contracts\GlobalSearchProvider;
use MicroweberPackages\FilamentRegistry\FilamentRegistryManager;

/**
 * Extended global search provider for Microweber.
 *
 * On top of Filament's default resource-based search it adds a second layer of
 * statically registered registry entries (settings pages, admin deep-links)
 * that modules register via FilamentRegistry::registerGlobalSearchEntry().
 * Registry entries are scoped to the current panel, and matching is
 * case-insensitive (works across SQLite, MySQL and PostgreSQL).
 */
class MicroweberGlobalSearchProvider implements GlobalSearchProvider
{
    public function getResults(string $query): ?GlobalSearchResults
    {
        $builder = GlobalSearchResults::make();
        $search = trim($query);

        if (blank($search)) {
            return null;
        }

        // ── Layer 1: Standard Filament resource search ──────────────
        $resources = Filament::getResources();

        usort(
            $resources,
            fn (string $a, string $b): int => ($a::getGlobalSearchSort() ?? 0) <=> ($b::getGlobalSearchSort() ?? 0),
        );

        foreach ($resources as $resource) {
            try {
                if (! $resource::canGloballySearch()) {
                    continue;
                }

                $resourceResults = $resource::getGlobalSearchResults($search);

                if (! $resourceResults->count()) {
                    continue;
                }

                $builder->category($resource::getPluralModelLabel(), $resourceResults);
            } catch (\Throwable $e) {
                // Gracefully skip resources whose tables don't exist
                // (fresh install, partial migration, or volatile modules).
                continue;
            }
        }

        // ── Layer 2: Registry-based static entries (settings, pages) ─
        $this->addRegistryEntries($builder, $search);

        return $builder;
    }

    /**
     * Match the search query against statically registered entries for the
     * current panel (settings pages, admin pages, module configurations).
     */
    protected function addRegistryEntries(GlobalSearchResults $builder, string $search): void
    {
        $registry = app(FilamentRegistryManager::class);
        $entries = $registry->getGlobalSearchEntries($this->currentPanelId());

        if (empty($entries)) {
            return;
        }

        $searchLower = mb_strtolower($search);
        $searchWords = array_filter((array) preg_split('/\s+/', $searchLower));

        $grouped = [];

        foreach ($entries as $entry) {
            if ($this->entryMatches($entry, $searchLower, $searchWords)) {
                $group = $entry['group'];
                $grouped[$group][] = new GlobalSearchResult(
                    title: $entry['title'],
                    url: $entry['url'],
                    details: $entry['details'],
                );
            }
        }

        foreach ($grouped as $group => $results) {
            $builder->category($group, $results);
        }
    }

    /**
     * Check if a registry entry matches the search query.
     * Uses word-level matching: every search word must appear
     * in either the title, keywords, or detail values.
     *
     * @param  array<string, mixed> $entry
     * @param  list<string>         $searchWords
     */
    protected function entryMatches(array $entry, string $searchLower, array $searchWords): bool
    {
        // Build the haystack from title + keywords + detail values
        $haystack = mb_strtolower((string) $entry['title']);

        foreach ($entry['keywords'] as $keyword) {
            $haystack .= ' ' . $keyword;
        }

        foreach ($entry['details'] as $value) {
            $haystack .= ' ' . mb_strtolower((string) $value);
        }

        // Every search word must appear somewhere in the haystack
        foreach ($searchWords as $word) {
            if (mb_strpos($haystack, $word) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * The id of the panel the search is running in (defaults to 'admin').
     */
    protected function currentPanelId(): string
    {
        try {
            return Filament::getCurrentOrDefaultPanel()?->getId() ?? 'admin';
        } catch (\Throwable $e) {
            return 'admin';
        }
    }
}
