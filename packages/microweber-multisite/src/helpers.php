<?php

/**
 * Autoload-time bootstrap for Microweber multisite.
 *
 * This file is listed under composer `autoload.files`, so it runs the moment
 * the autoloader is required — before any service provider and, crucially,
 * before Microweber's src/MicroweberPackages/App/functions/bootstrap.php
 * defines MW_USERFILES_FOLDER_NAME (which it guards with `if (!defined(...))`).
 *
 * By resolving the current site here and defining the userfiles folder as
 * "userfiles/{site}", every downstream path helper (userfiles_path(),
 * media_base_path(), …) transparently points at the per-site tree.
 *
 * Opt out by defining MW_MULTISITE_SKIP before the autoloader loads, or by
 * pre-defining MW_USERFILES_FOLDER_NAME yourself.
 */

use MicroweberPackages\Multisite\MultisiteManager;

if (!defined('MW_MULTISITE_SKIP') && class_exists(MultisiteManager::class)) {
    MultisiteManager::bootstrapConstants();
}

if (!function_exists('mw_multisite_current_site')) {
    /**
     * The current multisite domain/site, or null on the default (single) site.
     *
     * Safe to call extremely early — including while config files are being
     * merged (before the container is ready) — because it reuses the
     * autoload-time decision ($_SERVER['MW_MULTISITE_SITE']) and otherwise
     * recomputes it purely. This is what lets config/modules.php and
     * config/templates.php pick the per-site (database) activator.
     */
    function mw_multisite_current_site(): ?string
    {
        if (!class_exists(MultisiteManager::class)) {
            return null;
        }
        if (!empty($_SERVER['MW_MULTISITE_SITE'])) {
            return $_SERVER['MW_MULTISITE_SITE'];
        }

        $base = MultisiteManager::discoverBasePath();
        if ($base === null) {
            return null;
        }

        return MultisiteManager::detectSiteFromHost(
            MultisiteManager::currentHost(),
            $base . DIRECTORY_SEPARATOR . 'config'
        );
    }
}

if (!function_exists('mw_multisite_active')) {
    /**
     * True when the current request/CLI run resolves to a multisite domain.
     * Distinct from the legacy mw_is_multisite() (MW_IS_MULTISITE constant).
     */
    function mw_multisite_active(): bool
    {
        return mw_multisite_current_site() !== null;
    }
}

if (!function_exists('mw_multisite_statuses_path')) {
    /**
     * Resolve a per-site path for a shared status file (e.g. modules_statuses.json,
     * templates_statuses.json). On a multisite domain this returns
     * storage/sites/<domain>/<file>, seeding it from the shared base file on
     * first use so the new site inherits the current enabled set and then
     * diverges independently. On the default site it returns the shared path
     * unchanged.
     *
     * Called while config/modules.php and config/templates.php are merged
     * (storage_path() is available by then), i.e. before the module activator
     * first reads the file.
     */
    function mw_multisite_statuses_path(string $file): string
    {
        $file = ltrim($file, '/\\');
        $base = function_exists('storage_path')
            ? storage_path($file)
            : (MultisiteManager::discoverBasePath() . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . $file);

        $site = function_exists('mw_multisite_current_site') ? mw_multisite_current_site() : null;
        if ($site === null) {
            return $base;
        }

        $siteFile = function_exists('storage_path')
            ? storage_path('sites' . DIRECTORY_SEPARATOR . $site . DIRECTORY_SEPARATOR . $file)
            : dirname($base) . DIRECTORY_SEPARATOR . 'sites' . DIRECTORY_SEPARATOR . $site . DIRECTORY_SEPARATOR . $file;

        if (!is_file($siteFile)) {
            $dir = dirname($siteFile);
            if (!is_dir($dir)) {
                @mkdir($dir, 0775, true);
            }
            // Seed from the shared base so a new site starts with the current
            // enabled set; if there's no base, leave it absent (the activator
            // then falls back to module.json defaults).
            if (is_file($base)) {
                @copy($base, $siteFile);
            }
        }

        return $siteFile;
    }
}
