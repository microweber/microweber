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
        // Return null (Filament's per-driver default) rather than forcing true.
        //
        // Forcing case-insensitivity makes Filament wrap the search column in a
        // RAW `lower(<table>.<col>)` expression. Raw expressions bypass the
        // connection's table prefix, so on a prefixed database (e.g.
        // DB_PREFIX=mwdev_code_) the query becomes `lower(content.title)` while
        // the real table is `mwdev_code_content` — "no such column:
        // content.title" — and MicroweberGlobalSearchProvider silently skips the
        // resource, so NOTHING is ever found. (Filament already prefixes the
        // lower() column for pgsql; the breakage is MySQL/MariaDB + SQLite.)
        //
        // With null, Filament defaults to forced-CI only on pgsql (prefix-safe
        // there) and uses the plain, prefix-wrapped column on MySQL/SQLite,
        // where LIKE is already case-insensitive for ASCII under the default
        // collations.
        return null;
    }
}
