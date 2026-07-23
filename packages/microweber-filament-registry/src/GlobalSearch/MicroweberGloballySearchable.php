<?php

declare(strict_types=1);

namespace MicroweberPackages\FilamentRegistry\GlobalSearch;

/**
 * Opts a Filament resource into Microweber's global search with
 * case-insensitive matching (works across SQLite / MySQL / PostgreSQL).
 *
 * Replaces the per-resource duplication of:
 *
 *     protected static bool $isGloballySearchable = true;
 *     protected static ?bool $isGlobalSearchForcedCaseInsensitive = true;
 *
 * Why one is a property and the other a method:
 *  - $isGloballySearchable keeps the same default (true) as Filament's own
 *    HasGlobalSearch concern, so redeclaring it here is compatible (no trait
 *    conflict). Declaring it via this trait — which flattens into the class —
 *    also keeps it opt-in-safe: canGloballySearch() checks
 *    ReflectionProperty::getDeclaringClass() === static::class under a panel's
 *    globalSearchResourceOptIn() mode.
 *  - $isGlobalSearchForcedCaseInsensitive defaults to null in Filament, so a
 *    trait property with a different initial value (true) is incompatible and
 *    fatals. We override the accessor method instead.
 *
 * Resources still declare their own getGloballySearchableAttributes(),
 * modifyGlobalSearchQuery(), getGlobalSearchResultUrl(), etc. as needed.
 */
trait MicroweberGloballySearchable
{
    protected static bool $isGloballySearchable = true;

    public static function isGlobalSearchForcedCaseInsensitive(): ?bool
    {
        return true;
    }
}
