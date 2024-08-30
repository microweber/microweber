<?php

namespace MicroweberPackages\Modules\StandaloneUpdater\Http\Controllers;

use GrahamCampbell\Markdown\Facades\Markdown;

class StandaloneUpdaterController extends \MicroweberPackages\Admin\Http\Controllers\AdminController
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
            return redirect(module_admin_url('standalone-updater'));
        }

        return $this->view('standalone-updater::about', ['about' => $html]);
    }

    public function prepareUpdateTempFolder()
    {
        $updateCacheFolderName = 'standalone-update' . DS . rand(222, 444) . time() . DS;
        $updateCacheDir = userfiles_path() . $updateCacheFolderName;

        mw_standalone_updater_delete_recursive(userfiles_path() . 'standalone-update');
        mkdir_recursive($updateCacheDir);

        $bootstrap_cached_folder = normalize_path(base_path('bootstrap/cache/'), true);
        mw_standalone_updater_delete_recursive($bootstrap_cached_folder);


        $standaloneUpdaterMainPath = modules_path() . 'standalone-updater' . DS . 'src';

        $sourceActions = file_get_contents($standaloneUpdaterMainPath . '/standalone-installation-setup/actions.source.phps');
        $saveActions = file_put_contents($updateCacheDir . DS . 'actions.php', $sourceActions);

        $sourceUpdater = file_get_contents($standaloneUpdaterMainPath . '/standalone-installation-setup/index.source.phps');
        $saveIndex = file_put_contents($updateCacheDir . DS . 'index.php', $sourceUpdater);

        $sourceUnzip = file_get_contents($standaloneUpdaterMainPath . '/standalone-installation-setup/Unzip.source.phps');
        $saveUnzip = file_put_contents($updateCacheDir . DS . 'Unzip.php', $sourceUnzip);


        $source = file_get_contents($standaloneUpdaterMainPath . '/standalone-installation-setup/StandaloneUpdateExecutor.source.phps');
        $save = file_put_contents($updateCacheDir . DS . 'StandaloneUpdateExecutor.php', $source);


        $source = file_get_contents($standaloneUpdaterMainPath . '/standalone-installation-setup/StandaloneUpdateReplacer.source.phps');
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
        setcookie('admin_url', route('standalone-updater.about-new-version') . '?delete_temp=1', time() + (1800 * 5), "/");
        setcookie('site_url', site_url(), time() + (1800 * 5), "/");
        setcookie('install_session_id', false, time() - (1800 * 5), "/");

        $updateCacheFolderName = $this->prepareUpdateTempFolder();


        $redirectLink = site_url() . 'userfiles/' . $updateCacheFolderName . 'index.php?installVersion=' . $installVersion;


        if ($updateCacheFolderName) {
            return redirect($redirectLink);
        }

        return redirect(admin_url('view:modules/load_module:standalone-updater?message=Cant create update file.'));
    }

    public function deleteTemp()
    {
        $path = userfiles_path() . 'standalone-update';
        if (!is_dir($path)) {
            return false;
        }
        try {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST);
            foreach ($files as $fileinfo) {
                $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
                @$todo($fileinfo->getRealPath());
            }
            @rmdir($path);
        } catch (\Exception $e) {
            //
        }
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

        $executor = new  \StandaloneUpdateExecutor();

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
