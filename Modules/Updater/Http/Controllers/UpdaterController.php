<?php

namespace Modules\Updater\Http\Controllers;

use MicroweberPackages\Admin\Http\Controllers\AdminController;
use Modules\Updater\Services\UpdaterHelper;
use Illuminate\Http\Request;

class UpdaterController extends AdminController
{


    /**
     * Start the update process
     */
    public function updateNow(Request $request)
    {
        $version = $request->get('version', 'master');

        $updaterHelper = app(UpdaterHelper::class);
        $rand = rand(222, 444) . time();
        // Check if we can update
        $updateMessages = $updaterHelper->getCanUpdateMessages();
        if (!empty($updateMessages)) {
            return redirect()->back()->with('error', 'Cannot update: ' . implode(', ', $updateMessages));
        }

        if (is_dir(public_path() . '/standalone-updater')) {
            rmdir_recursive(public_path() . '/standalone-updater', false);
        }

        $updateCacheFolderName = 'standalone-updater/' . rand(222, 444) . time() . '/';
        $updateCacheDir = public_path() . '/' . $updateCacheFolderName;
        $updateCacheDirRedicrect = site_url() . $updateCacheFolderName;


        // Create standalone updater
        $updaterHelper->copyStandaloneUpdater($updateCacheDir);

        // Redirect to the standalone updater
        return redirect($updateCacheDirRedicrect . 'index.php?branch=' . $version . '&installVersion=' . $version . '&install_session_id=' . $rand);
    }


    /**
     * Update from CLI
     *
     * @param string $branch The branch to update from (master, dev, dev_unstable)
     * @return bool
     * @throws \Exception
     */
    public function updateFromCli($branch = 'master')
    {
        if (!function_exists('is_cli') || !is_cli()) {
            return false;
        }

        // Check if we can update
        $updaterHelper = app(UpdaterHelper::class);
        $updateMessages = $updaterHelper->getCanUpdateMessages();
        if (!empty($updateMessages)) {
            throw new \Exception('Cannot update: ' . implode(', ', $updateMessages));
        }

        // Create a temporary folder for the updater
        $rand = rand(222, 444) . time();
        $updateCacheFolderName = 'standalone-updater/' . $rand . '/';
        $updateCacheDir = public_path() . '/' . $updateCacheFolderName;

        if (!is_dir($updateCacheDir)) {
            mkdir_recursive($updateCacheDir);
        }

        // Copy the standalone updater files
        $updaterHelper->copyStandaloneUpdater($updateCacheDir, true);

        // Change to the updater directory
        $currentDir = getcwd();
        chdir($updateCacheDir);

        // Include the necessary files
        require_once $updateCacheDir . 'index.php';

        // Start the update process
        $executor = new \StandaloneUpdateExecutor();
        $executor->branch = $branch;
        $executor->startSession();

        // Set the version based on branch
        if ($branch == 'dev') {
            $_REQUEST['install_session_version'] = 'dev';
            $_COOKIE['install_session_version'] = 'dev';
        } else if ($branch == 'dev_unstable') {
            $_REQUEST['install_session_version'] = 'dev_unstable';
            $_COOKIE['install_session_version'] = 'dev_unstable';
        } elseif ($branch) {

            $_REQUEST['install_session_version'] = $branch;
            $_COOKIE['install_session_version'] = $branch;
        } else {
            $_REQUEST['install_session_version'] = 'master';
            $_COOKIE['install_session_version'] = 'master';
        }

        // Start updating
        $installVersion = $executor->startUpdating();
        if ($installVersion['status'] != 'success') {
            chdir($currentDir);
            throw new \Exception('Error downloading update files');
        }

        // Unzip the app
        $run = $executor->unzippApp();
        if ($run['status'] != 'success') {
            chdir($currentDir);
            throw new \Exception('Error unzipping update files');
        }

        // Replace files
        $run = $executor->replaceFiles();
        if ($run['status'] != 'success') {
            chdir($currentDir);
            throw new \Exception('Error replacing files');
        }

        // Cleanup
        $run = $executor->replaceFilesExecCleanupStep();
        if ($run['status'] != 'success') {
            chdir($currentDir);
            throw new \Exception('Error during cleanup step');
        }

        // Change back to the original directory
        chdir($currentDir);

        @unlink($updateCacheDir . 'index.php');

        return true;
    }
}
