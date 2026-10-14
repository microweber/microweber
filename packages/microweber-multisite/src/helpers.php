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
