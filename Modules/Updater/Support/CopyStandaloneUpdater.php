<?php

namespace Modules\Updater\Support;

class CopyStandaloneUpdater
{
    /**
     * Copy the standalone updater files to the public directory
     */
    public static function copy()
    {
        $sourcePath = module_path('Updater') . DS . 'src' . DS . 'standalone-installation-setup';
        $destinationPath = public_path();

        // Create the standalone-updater.php file in the public directory
        $standaloneUpdaterContent = self::generateStandaloneUpdaterFile();
        file_put_contents($destinationPath . DS . 'standalone-updater.php', $standaloneUpdaterContent);

        return true;
    }

    /**
     * Generate the standalone updater file content
     */
    private static function generateStandaloneUpdaterFile()
    {
        $sourcePath = module_path('Updater') . DS . 'src' . DS . 'standalone-installation-setup';

        // Read the source files
        $actionsContent = file_get_contents($sourcePath . DS . 'actions.source.phps');
        $indexContent = file_get_contents($sourcePath . DS . 'index.source.phps');
        $unzipContent = file_get_contents($sourcePath . DS . 'Modules\Updater\resources\stubs\standalone-installation-setup\Unzip.source.phps');
        $executorContent = file_get_contents($sourcePath . DS . 'Modules\Updater\resources\stubs\standalone-installation-setup\StandaloneUpdateExecutor.source.phps');
        $replacerContent = file_get_contents($sourcePath . DS . 'Modules\Updater\resources\stubs\standalone-installation-setup\StandaloneUpdateReplacer.source.phps');

        // Create the standalone updater file
        $standaloneUpdaterContent = <<<EOT
<?php
/**
 * Standalone Updater for Microweber
 *
 * This file is used to update Microweber without the need for the admin panel.
 * Just place this file in the root directory of your Microweber installation and run it.
 */

// Set error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
define("INI_SYSTEM_CHECK_DISABLED", ini_get('disable_functions'));

if (!strstr(INI_SYSTEM_CHECK_DISABLED, 'ini_set')) {
    ini_set("memory_limit", "512M");
    ini_set("set_time_limit", 0);
}

if (!strstr(INI_SYSTEM_CHECK_DISABLED, 'date_default_timezone_set')) {
    date_default_timezone_set('America/Los_Angeles');
}

// Define the Modules\Updater\resources\stubs\standalone-installation-setup\Unzip class
{$unzipContent}

// Define the Modules\Updater\resources\stubs\standalone-installation-setup\StandaloneUpdateExecutor class
{$executorContent}

// Define the Modules\Updater\resources\stubs\standalone-installation-setup\StandaloneUpdateReplacer class
{$replacerContent}

// Handle actions
{$actionsContent}

// Display the UI
{$indexContent}
EOT;

        return $standaloneUpdaterContent;
    }
}
