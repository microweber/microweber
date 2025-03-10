<?php

namespace Modules\Updater\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

class UpdaterPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';
    protected static ?string $navigationLabel = 'Updater';
    protected static ?string $navigationGroup = 'System';
    protected static ?int $navigationSort = 100;
    protected static ?string $slug = 'updater';
    protected static string $view = 'modules.updater::filament.pages.updater';

    public $currentVersion;
    public $latestVersion;
    public $updateAvailable = false;
    public $canUpdate = true;
    public $updateMessages = [];

    public function mount(): void
    {
        $this->currentVersion = MW_VERSION;
        $this->latestVersion = $this->getLatestVersion();

        if (\Composer\Semver\Comparator::greaterThan($this->latestVersion, $this->currentVersion)) {
            $this->updateAvailable = true;
        }

        $this->updateMessages = $this->getCanUpdateMessages();
        if (!empty($this->updateMessages)) {
            $this->canUpdate = false;
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('check_for_updates')
                ->label('Check for Updates')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    // Clear the cache to force a fresh check
                    cache()->forget('standalone_updater_latest_version');
                    cache()->forget('standalone_updater_latest_version_composer_json');

                    // Refresh the data
                    $this->latestVersion = $this->getLatestVersion();

                    if (\Composer\Semver\Comparator::greaterThan($this->latestVersion, $this->currentVersion)) {
                        $this->updateAvailable = true;
                        Notification::make()
                            ->title('Update available')
                            ->body("A new version ({$this->latestVersion}) is available for download.")
                            ->success()
                            ->send();
                    } else {
                        $this->updateAvailable = false;
                        Notification::make()
                            ->title('No updates available')
                            ->body('You are running the latest version.')
                            ->success()
                            ->send();
                    }
                }),

            Action::make('update_now')
                ->label('Update Now')
                ->icon('heroicon-o-arrow-path')
                ->color('primary')
                ->url(fn () => route('api.updater.update-now'))
                ->visible(fn () => $this->updateAvailable && $this->canUpdate),

            Action::make('reinstall')
                ->label('Reinstall')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->url(fn () => route('api.updater.update-now'))
                ->visible(fn () => !$this->updateAvailable && $this->canUpdate),

            Action::make('copy_standalone')
                ->label('Copy Standalone Updater')
                ->icon('heroicon-o-document-duplicate')
                ->color('success')
                ->action(function () {
                    $this->copyStandaloneUpdater();

                    Notification::make()
                        ->title('Standalone updater copied')
                        ->body('The standalone updater has been copied to the public directory.')
                        ->success()
                        ->send();
                }),
        ];
    }

    /**
     * Get the latest version from the update server
     */
    private function getLatestVersion()
    {
        return cache()->remember('standalone_updater_latest_version', 1440, function () {
            $updateApi = 'http://updater.microweberapi.com/builds/master/version.txt';
            $version = app()->url_manager->download($updateApi);
            if ($version) {
                $version = trim($version);
                return $version;
            }
            return MW_VERSION;
        });
    }

    /**
     * Get messages about why the system can't be updated
     */
    private function getCanUpdateMessages(): array
    {
        $messages = [];
        $projectMainDir = dirname(dirname(dirname(dirname(__DIR__))));

        if (is_dir($projectMainDir . DS . '.git')) {
            $messages[] = 'The git repository is recognized on your server.';
        }

        if (!class_exists('ZipArchive')) {
            $messages[] = 'ZipArchive PHP extension is required auto updater.';
        }

        if (!function_exists('curl_init')) {
            $messages[] = 'The Curl PHP extension is required auto updater.';
        }

        if (!function_exists('json_decode')) {
            $messages[] = 'The JSON PHP extension is required auto updater.';
        }

        if (!is_writable($projectMainDir . DS . 'src')) {
            $messages[] = 'The src folder must be writable.';
        }

        if (!is_writable($projectMainDir . DS . 'userfiles')) {
            $messages[] = 'The userfiles folder must be writable.';
        }

        if (!is_writable($projectMainDir . DS . 'storage')) {
            $messages[] = 'The storage folder must be writable.';
        }

        if (is_link($projectMainDir . DS . 'vendor')) {
            $messages[] = 'The vendor folder must not be a symlink.';
        }

        if (!is_writable($projectMainDir . DS . 'vendor')) {
            $messages[] = 'The vendor folder must be writable.';
        }

        if (function_exists('disk_free_space')) {
            $bytes = disk_free_space($projectMainDir);
            $si_prefix = array('B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB');
            $base = 1024;
            $class = min((int)log($bytes, $base), count($si_prefix) - 1);

            if (($bytes / pow($base, $class)) < 1) {
                $messages[] = 'The minimum required free disk space is 1GB, you have ' . sprintf('%1.2f', $bytes / pow($base, $class)) . ' ' . $si_prefix[$class] . ' on your server.';
            }
        }

        return $messages;
    }

    /**
     * Copy the standalone updater files to the public directory
     */
    private function copyStandaloneUpdater()
    {
        $updateCacheFolderName = 'standalone-updater' . DS . rand(222, 444) . time() . DS;
        $updateCacheDir = userfiles_path() . $updateCacheFolderName;

        // Delete existing standalone updater directory if it exists
        $this->deleteRecursive(userfiles_path() . 'standalone-updater');
        mkdir_recursive($updateCacheDir);

        // Clear bootstrap cache
        $bootstrap_cached_folder = normalize_path(base_path('bootstrap/cache/'), true);
        $this->deleteRecursive($bootstrap_cached_folder);

        // Copy files from the new stubs location
        $stubsPath = module_path('Updater') . DS . 'Stubs';

        $sourceActions = file_get_contents($stubsPath . DS . 'actions.source.php.stub');
        file_put_contents($updateCacheDir . DS . 'actions.php', $sourceActions);

        $sourceUpdater = file_get_contents($stubsPath . DS . 'index.source.php.stub');
        file_put_contents($updateCacheDir . DS . 'index.php', $sourceUpdater);

        $sourceUnzip = file_get_contents($stubsPath . DS . 'Unzip.source.php.stub');
        file_put_contents($updateCacheDir . DS . 'Unzip.php', $sourceUnzip);

        $source = file_get_contents($stubsPath . DS . 'StandaloneUpdateExecutor.source.php.stub');
        file_put_contents($updateCacheDir . DS . 'StandaloneUpdateExecutor.php', $source);

        $source = file_get_contents($stubsPath . DS . 'StandaloneUpdateReplacer.source.php.stub');
        file_put_contents($updateCacheDir . DS . 'StandaloneUpdateReplacer.php', $source);

        // Create the standalone-updater.php file in the public directory
        $standaloneUpdaterContent = $this->generateStandaloneUpdaterFile($stubsPath);
        file_put_contents(public_path() . DS . 'standalone-updater.php', $standaloneUpdaterContent);

        return true;
    }

    /**
     * Generate the standalone updater file content
     */
    private function generateStandaloneUpdaterFile($stubsPath)
    {
        // Read the source files
        $actionsContent = file_get_contents($stubsPath . DS . 'actions.source.php.stub');
        $indexContent = file_get_contents($stubsPath . DS . 'index.source.php.stub');
        $unzipContent = file_get_contents($stubsPath . DS . 'Unzip.source.php.stub');
        $executorContent = file_get_contents($stubsPath . DS . 'StandaloneUpdateExecutor.source.php.stub');
        $replacerContent = file_get_contents($stubsPath . DS . 'StandaloneUpdateReplacer.source.php.stub');




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
    date_default_timezone_set('UTC');
}

// Define the Unzip class
{$unzipContent}

// Define the StandaloneUpdateExecutor class
{$executorContent}

// Define the StandaloneUpdateReplacer class
{$replacerContent}

// Handle actions
{$actionsContent}

// Display the UI
?>

{$indexContent}
EOT;

        return $standaloneUpdaterContent;
    }

    /**
     * Delete a directory recursively
     */
    private function deleteRecursive($dir)
    {
        if (!is_dir($dir)) {
            return;
        }

        try {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST);
            foreach ($files as $fileinfo) {
                $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
                @$todo($fileinfo->getRealPath());
            }
        } catch (\Exception $e) {
            // Cant remove files from this path
        }

        @rmdir($dir);
    }
}
