<?php

namespace MicroweberPackages\Package;

use Composer\Semver\Comparator;
use MicroweberPackages\App\Models\SystemLicenses;
use MicroweberPackages\ComposerClient\Client;
use MicroweberPackages\Utils\Zip\Unzip;

class MicroweberComposerClient extends Client
{
    public $logfile;

    public function __construct()
    {
        parent::__construct();

        $this->logfile = userfiles_path() . 'install_item_log.txt';

        // Fill the user licenses
        if (mw_is_installed()) {
            $findLicenses = SystemLicenses::all();
            if ($findLicenses !== null) {
                $this->licenses = $findLicenses->toArray();
            }
        }

        if (function_exists('get_white_label_config')) {
            $settings = get_white_label_config();
            if (isset($settings['marketplace_repositories_urls']) && !empty($settings['marketplace_repositories_urls'])) {
                $this->packageServers = $settings['marketplace_repositories_urls'];
            }
        }
    }

    public function countNewUpdatesCached()
    {

        $count = \Cache::tags('updates')->get('countNewUpdates');
        //$count = \Cache::get('countNewUpdates');
        if ($count) {
            return $count;
        }

        return 0;
    }

    public function countNewUpdates()
    {

        $searchPackages = $this->search();

        $allPackages = [];
        $localPackages = mw()->update->collect_local_data();
        foreach ($localPackages['modules'] as $package) {
            $allPackages[] = $package;
        }
        foreach ($localPackages['templates'] as $package) {
            $allPackages[] = $package;
        }

        $readyPackages = [];
        foreach ($searchPackages as $packageName => $versions) {
            foreach ($versions as $version) {
                if (!is_array($version)) {
                    continue;
                }

                $version['latest_version'] = $version;

                if (!empty($allPackages)) {
                    foreach ($allPackages as $module) {

                        if (isset($version['target-dir']) && $module['dir_name'] == $version['target-dir']) {

                            $version['has_update'] = false;

                            $v1 = trim($version['latest_version']['version']);
                            $v2 = trim($module['version']);

                            if ($v1 != $v2) {
                                if (Comparator::greaterThan($v1, $v2)) {
                                    $version['has_update'] = true;
                                }
                            }
                            break;
                        }
                    }
                }
                $readyPackages[$packageName] = $version;
            }
        }

        $newUpdates = 0;

        foreach ($readyPackages as $package) {
            if (isset($package['has_update']) && $package['has_update']) {
                $newUpdates++;
            }
        }

        return $newUpdates;
    }

    public function requestInstall($params)
    {
        if (!isset($params['require_version'])) {
            $params['require_version'] = 'latest';
        }

        $this->newLog('Request install...');

        $this->log('Searching for ' . $params['require_name'] . ' for version ' . $params['require_version']);

        $package = $this->search([
            'require_version' => $params['require_version'],
            'require_name' => $params['require_name'],
        ]);

        if (!$package) {
            return array('error' => 'Error. Cannot find any packages.');
        }

        $confirmKey = 'composer-confirm-key-' . rand();
        if (isset($params['confirm_key'])) {
            $isConfirmed = cache_get($params['confirm_key'], 'composer');
            if ($isConfirmed) {
                $package['unzipped_files_location'] = $isConfirmed['unzipped_files_location'];
                return $this->install($package);
            }
        }

        if ($package['dist']['type'] == 'license_key') {
            return array(
                'error' => _e('You need license key to install this package', true),
                'message' => _e('This package is premium and you must have a license key to install it', true),
                // 'form_data_required' => 'license_key',
                'form_data_module' => 'settings/group/license_edit',
                'form_data_module_params' => array(
                    'require_name' => $params['require_name'],
                    'require_version' => _e('You need license key', true)
                )
            );
        }

        $this->downloadPackage($package, $confirmKey);
        $this->clearLog();

        return array(
            'error' => 'Please confirm installation',
            'form_data_module' => 'admin/developer_tools/package_manager/confirm_install',
            'form_data_module_params' => array(
                'confirm_key' => $confirmKey,
                'require_name' => $params['require_name'],
                'require_version' => $params['require_version']
            )
        );
    }

    public function downloadPackage($package, $confirmKey)
    {
        if (isset($package['dist']['url'])) {

            $distUrl = $package['dist']['url'];

            if (!isset($package['target-dir'])) {
                return false;
            }

            $packageFileName = 'last-package.zip';
            $packageFileDestination = storage_path() . '/cache/composer-download/' . $package['target-dir'] . '/';

            $this->_removeFilesFromPath($packageFileDestination); // remove dir
            mkdir_recursive($packageFileDestination); // make new dir

            $this->log('Downloading the package file..');

            $downloadStatus = $this->downloadBigFile($distUrl, $packageFileDestination . $packageFileName, $this->logfile);

            if ($downloadStatus) {

                $this->log('Extract the package file..');

                $unzip = new Unzip();
                $unzip->extract($packageFileDestination . $packageFileName, $packageFileDestination, true);

                // Delete zip file
                @unlink($packageFileDestination . $packageFileName);

                $scanDestination = $this->recursiveScan($packageFileDestination);
                foreach ($scanDestination as $key => $value) {
                    $this->log('Unzip file: ' . $value);
                }

                $composerConfirm = array();
                $composerConfirm['user'] = $scanDestination;
                $composerConfirm['packages'] = $scanDestination;
                $composerConfirm['unzipped_files_location'] = $packageFileDestination;

                cache_save($composerConfirm, $confirmKey, 'composer');

                return true;
            }
        }

        return false;
    }

    public function recursiveScan($dir)
    {

        $directory = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST);

        $files = array();
        foreach ($directory as $info) {
            $files[] = $info->getFilename();
        }

        return $files;
    }

    public function install($package)
    {
        $type = 'microweber-module';
        if (isset($package['type'])) {
            $type = $package['type'];
        }

        if ($type == 'microweber-module') {
            $packageFileDestination = userfiles_path() . '/modules/' . $package['target-dir'] . '/';
        }

        if ($type == 'microweber-template') {
            $packageFileDestination = userfiles_path() . '/templates/' . $package['target-dir'] . '/';
        }

        if (!isset($package['unzipped_files_location'])) {
            return false;
        }

        $this->_removeFilesFromPath($packageFileDestination); // remove dir

        @rename($package['unzipped_files_location'], $packageFileDestination);

        $moduleName = $package['name'];
        $moduleName = str_replace('microweber-modules/', '', $moduleName);
        $moduleName = str_replace('microweber-templates/', '', $moduleName);

        $moduleLink = module_admin_url($moduleName);

        $response = array();
        $response['success'] = 'Success. You have installed: ' . $moduleName . ' <br /> <a href="'.$moduleLink.'">Visit the module</a>';
        $response['redirect_to'] = admin_url('view:modules/load_module:' . $moduleName);
        $response['log'] = 'Done!';

        if (mw_is_installed()) { // This can make installation without database

            // app()->update->post_update();
            scan_for_modules('skip_cache=1&cleanup_db=1&reload_modules=1');
            scan_for_elements('skip_cache=1&cleanup_db=1&reload_modules=1');


            mw()->cache_manager->delete('db');
            mw()->cache_manager->delete('update');
            mw()->cache_manager->delete('elements');

            mw()->cache_manager->delete('templates');
            mw()->cache_manager->delete('modules');
        }

        return $response;
    }

    /**
     * Remove dir recursive
     * @param string $dir
     */
    private function _removeFilesFromPath($dir)
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

    public function newLog($log)
    {
        @file_put_contents($this->logfile, $log . PHP_EOL);
    }

    public function clearLog()
    {
        @file_put_contents($this->logfile, '');
    }

    public function log($log)
    {
        @file_put_contents($this->logfile, $log . PHP_EOL, FILE_APPEND);
    }
}
