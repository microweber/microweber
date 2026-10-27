<?php

/**
 * Per-site path segment for backup storage under multisite.
 *
 * Empty on the default (single) site, so paths stay byte-for-byte unchanged
 * there. On a multisite domain it returns "sites/<site>/", keyed off the SAME
 * site identifier the userfiles tree uses (MW_USERFILES_FOLDER_NAME ->
 * userfiles/<site>). Because that identifier is disk-agnostic, a site's backups
 * stay isolated whether its userfiles live on the local disk or on S3
 * (MEDIA_UPLOAD_DISK=s3, where userfiles_base_relpath keeps the same per-site
 * subfolder). Mirrors the storage/sites/<site> tree already used for per-site
 * *_statuses.json.
 *
 * @return string e.g. "sites/example.com/", or "" on the default site.
 */
function backup_site_segment()
{
    if (function_exists('mw_multisite_current_site')) {
        $site = mw_multisite_current_site();
        if (!empty($site)) {
            return 'sites/' . trim((string) $site, '/\\') . '/';
        }
    }

    return '';
}

/**
 * Get backup location path.
 * @return string
 */
function backup_location()
{
    $backupContent = storage_path() . '/backup_content/' . \App::environment() . '/' . backup_site_segment();
    $backupContent = normalize_path($backupContent, true);
    if (!is_dir($backupContent)) {
        mkdir_recursive($backupContent);
        $htaccess = $backupContent . '.htaccess';
        if (!is_file($htaccess)) {
            touch($htaccess);
            file_put_contents($htaccess, 'Deny from all');
        }
    }

    return $backupContent;
}

/**
 * Get backup cache location path.
 * @return string
 */
function backup_cache_location()
{
    $backupContent = backup_location() . 'cache_backup_zip/';

    if (!is_dir($backupContent)) {
        mkdir_recursive($backupContent);
    }

    return $backupContent;
}
