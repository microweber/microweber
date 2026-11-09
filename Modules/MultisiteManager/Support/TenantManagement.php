<?php

declare(strict_types=1);

namespace Modules\MultisiteManager\Support;

/**
 * Feature gate for the tenant/subsite management UI.
 *
 * The whole module (its Filament admin page, routes and actions) is inert
 * unless the operator opts in with the env flag. Creating databases and writing
 * .env files from the admin panel is powerful, so it stays OFF by default and
 * is only exposed on installs that explicitly turn it on.
 *
 * The canonical flag is MW_MULTISITE_ENABLE_TENNANT_MANAGMENT (the spelling the
 * project standardised on). The correctly-spelled
 * MW_MULTISITE_ENABLE_TENANT_MANAGEMENT is accepted as an alias so a corrected
 * .env keeps working.
 */
final class TenantManagement
{
    /** The canonical env flag + its correctly-spelled alias. */
    public const FLAG = 'MW_MULTISITE_ENABLE_TENNANT_MANAGMENT';

    public const FLAG_ALIAS = 'MW_MULTISITE_ENABLE_TENANT_MANAGEMENT';

    /**
     * True when tenant management is enabled via env. Read from the raw
     * environment (not config) so it is resolvable extremely early — including
     * from a service provider's register() before config caching settles.
     */
    public static function enabled(): bool
    {
        $raw = self::readFlag(self::FLAG);
        if ($raw === null) {
            $raw = self::readFlag(self::FLAG_ALIAS);
        }

        return $raw !== null && filter_var($raw, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * True only on the MAIN website (never inside a subsite environment).
     *
     * This matters because the per-domain env loader overlays the base .env on
     * top of .env.<domain> (filling gaps), so the management flag set on the main
     * site would otherwise LEAK into every subsite. Tenant management must stay
     * on the main site only — a subsite's users must never manage tenants.
     */
    public static function onMainSite(): bool
    {
        return !(function_exists('mw_multisite_active') && mw_multisite_active());
    }

    /**
     * The single gate used by both the service provider (whether to register the
     * admin page at all) and the page (whether it is accessible): enabled via env
     * AND we are on the main site. Everything else (admin auth) layers on top.
     */
    public static function availableHere(): bool
    {
        return self::enabled() && self::onMainSite();
    }

    private static function readFlag(string $key): ?string
    {
        $value = getenv($key);
        if ($value === false || $value === '') {
            $value = $_ENV[$key] ?? $_SERVER[$key] ?? null;
        }

        return (is_string($value) && $value !== '') ? $value : null;
    }
}
