<?php

namespace Modules\Updater\Http\Controllers;

use GrahamCampbell\Markdown\Facades\Markdown;
use MicroweberPackages\Admin\Http\Controllers\AdminController;

class UpdaterController extends AdminController
{
    public function aboutNewVersion()
    {
        $readmeFile = MW_ROOTPATH . 'README.md';
        $changeLogFile = MW_ROOTPATH . 'CHANGELOG.md';

        $aboutFile = MW_ROOTPATH . 'ABOUT.md';
        if (is_file($aboutFile)) {
            $aboutFile = file_get_contents($aboutFile);
            $html = Markdown::convertToHtml($aboutFile);
        } else {
            return redirect(route('updater.index'));
        }

        return $this->view('updater::about', ['about' => $html]);
    }

    public function prepareUpdateTempFolder()
    {
        $updateCacheFolderName = 'standalone-updater' . DS . rand(222, 444) . time() . DS;
        $updateCacheDir = userfiles_path() . $updateCacheFolderName;

        $updaterHelper = app(\Modules\Updater\Services\UpdaterHelper::class);
        $updaterHelper->deleteRecursive(userfiles_path() . 'standalone-updater');
        mkdir_recursive($updateCacheDir);

        $bootstrap_cached_folder = normalize_path(base_path('bootstrap/cache/'), true);
        $updaterHelper->deleteRecursive($bootstrap_cached_folder);

        // Use the new stubs location
        $stubsPath = module_path('Updater') . DS . 'Stubs';

        $sourceActions = file_get_contents($stubsPath . DS . 'actions.source.php.stub');
        $saveActions = file_put_contents($updateCacheDir . DS . 'actions.php', $sourceActions);

        $sourceUpdater = file_get_contents($stubsPath . DS . 'index.source.php.stub');
        $saveIndex = file_put_contents($updateCacheDir . DS . 'index.php', $sourceUpdater);

        $sourceUnzip = file_get_contents($stubsPath . DS . 'Unzip.source.php.stub');
        $saveUnzip = file_put_contents($updateCacheDir . DS . 'Unzip.php', $sourceUnzip);

        $source = file_get_contents($stubsPath . DS . 'StandaloneUpdateExecutor.source.php.stub');
        $save = file_put_contents($updateCacheDir . DS . 'StandaloneUpdateExecutor.php', $source);

        $source = file_get_contents($stubsPath . DS . 'StandaloneUpdateReplacer.source.php.stub');
        $save = file_put_contents($updateCacheDir . DS . 'StandaloneUpdateReplacer.php', $source);

        return $updateCacheFolderName;
    }

    public function updateNow()
    {
        $installVersion = 'latest';
        if (isset($_POST['version']) && $_POST['version'] == 'dev') {
            $installVersion = 'dev';
        }
        if (isset($_POST['version']) && $_POST['version'] == 'dev_unstable') {
            $installVersion = 'dev_unstable';
        }

        setcookie('max_receive_speed_download', get_option('max_receive_speed_download', 'standalone_updater'), time() + (1800 * 5), "/");
        setcookie('admin_url', route('updater.about-new-version') . '?delete_temp=1', time() + (1800 * 5), "/");
        setcookie('site_url', site_url(), time() + (1800 * 5), "/");
        setcookie('install_session_id', false, time() - (1800 * 5), "/");

        $updateCacheFolderName = $this->prepareUpdateTempFolder();

        $redirectLink = site_url() . 'userfiles/' . $updateCacheFolderName . 'index.php?installVersion=' . $installVersion;

        if ($updateCacheFolderName) {
            return redirect($redirectLink);
        }

        return redirect(admin_url('view:modules/load_module:updater?message=Cant create update file.'));
    }

    public function deleteTemp()
    {
        $path = userfiles_path() . 'standalone-updater';
        if (!is_dir($path)) {
            return false;
        }
        $updaterHelper = app(\Modules\Updater\Services\UpdaterHelper::class);
        $updaterHelper->deleteRecursive($path);
    }

    public function updateFromCli($branch = 'master')
    {
        if (!is_cli()) {
            return;
        }
        $folderName = $this->prepareUpdateTempFolder();
        $folder = userfiles_path() . $folderName;

        $_REQUEST['format'] = 'json';

        chdir($folder);

        include $folder . '/StandaloneUpdateExecutor.php';
        include $folder . '/StandaloneUpdateReplacer.php';
        include $folder . '/Unzip.php';

        $executor = new setup\StandaloneUpdateExecutor();

        $executor->startSession();
        if ($branch == 'dev') {
            $_COOKIE['install_session_version'] = 'developer';
            $installVersion = $executor->startUpdating();
        } else if ($branch == 'dev_unstable') {
            $_COOKIE['install_session_version'] = 'dev_unstable';
            $installVersion = $executor->startUpdating();
        } else {
            $installVersion = $executor->startUpdating();
        }
        if ($installVersion['status'] != 'success') {
            throw new \Exception('Error downloading');
        }

        $run = $executor->unzippApp();

        if ($run['status'] != 'success') {
            throw new \Exception('Error unzipping');
        }
        $run = $executor->replaceFiles();
        if ($run['status'] != 'success') {
            throw new \Exception('Error replacing files');
        }

        $run = $executor->replaceFilesExecCleanupStep();
        if ($run['status'] != 'success') {
            throw new \Exception('Error replacing files');
        }
        return true;
    }

}
