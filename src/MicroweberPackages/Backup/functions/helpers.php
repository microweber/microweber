<?php

/**
 * Get backup location path.
 * @return string
 */
function backup_location()
{
    $backupContent = storage_path() . '/backup_content/' . \App::environment() . '/';
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
