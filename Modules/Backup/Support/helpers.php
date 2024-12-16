<?php


if (!function_exists('backup_location')) {

    /**
     * Get backup location path.
     * @return string
     */
    function backup_location()
    {
        $backupContent = storage_path() . '/backup_content/' . app()->environment() . '/';
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
}

if (!function_exists('backup_cache_location')) {

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
}
if (!function_exists('format_bytes')) {
    function format_bytes($bytes, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
