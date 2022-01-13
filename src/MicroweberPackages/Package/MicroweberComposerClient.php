<?php

namespace MicroweberPackages\Package;

use Composer\Semver\Comparator;
use Composer\Util\StreamContextFactory;
use MicroweberPackages\App\Models\SystemLicenses;
use MicroweberPackages\Package\Traits\FileDownloader;
use MicroweberPackages\Utils\Zip\Unzip;

class MicroweberComposerClient
{

    use FileDownloader;

    public $logfile = false;
    public $licenses = [];
    public $packageServers = [
        //'https://packages.microweberapi.com/packages.json',
        'https://market.microweberapi.com/packages/microweber/packages.json'
    ];

    public function __construct()
    {
        // Fill the user licenses
        $findLicenses = SystemLicenses::all();
        if ($findLicenses !== null) {
            $this->licenses = $findLicenses->toArray();
        }

        $this->logfile = userfiles_path() . 'install_item_log.txt';

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

    public function search($filter = array())
    {
        $packages = [];
        foreach ($this->packageServers as $package) {

            $getRepositories = $this->getPackageFile($package);

            if (empty($filter)) {
                return $getRepositories;
            }

            foreach ($getRepositories as $packageName => $packageVersions) {

                if (!is_array($packageVersions)) {
                    continue;
                }

                if ((isset($filter['require_name']) && ($filter['require_name'] == $packageName))) {

                    $packageVersions['latest'] = end($packageVersions);

                    foreach ($packageVersions as $packageVersion => $packageVersionData) {
                        if ($filter['require_version'] == $packageVersion) {
                            $packages[] = $packageVersionData;
                            break;
                        }
                    }
                }

            }
        }

        return $packages;
    }

    public function requestInstall($params)
    {

        if (!isset($params['require_version'])) {
            $params['require_version'] = 'latest';
        }

        $this->newLog('Request install...');

        $this->log('Searching for ' . $params['require_name'] . ' for version ' . $params['require_version']);

        $search = $this->search([
            'require_version' => $params['require_version'],
            'require_name' => $params['require_name'],
        ]);

        if (!$search) {
            return array('error' => 'Error. Cannot find any packages.');
        }

        $package = $search[0];

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

        if (isset($package['notification-url'])) {
            $this->_sendNotification($package);
        }

        $response = array();
        $response['success'] = 'Success. You have installed: ' . $moduleName;
        $response['redirect_to'] = admin_url('view:modules/load_module:' . $moduleName);
        $response['log'] = 'Done!';

        // app()->update->post_update();
        scan_for_modules('skip_cache=1&cleanup_db=1&reload_modules=1');
        scan_for_elements('skip_cache=1&cleanup_db=1&reload_modules=1');


        mw()->cache_manager->delete('db');
        mw()->cache_manager->delete('update');
        mw()->cache_manager->delete('elements');

        mw()->cache_manager->delete('templates');
        mw()->cache_manager->delete('modules');

        return $response;
    }

    private function _sendNotification($package) {

        $postData = array('downloads' => array());

        $postData['downloads'][] = array(
            'name' => $package['name'],
            'version' => $package['version_normalized'],
            'license'=>$this->licenses
        );

        $opts = array('http' =>
            array(
                'method' => 'POST',
                'header' => array('Content-Type: application/json'),
                'content' => json_encode($postData),
                'timeout' => 6,
            ),
        );

        if (!empty($this->licenses)) {
            $authHeader = 'Authorization: Basic ' . base64_encode(json_encode($this->licenses));
            $opts['http']['header'][] = $authHeader;
        }

        $context = StreamContextFactory::getContext($package['notification-url'], $opts);
        $output = file_get_contents($package['notification-url'], false, $context);

        dd($output);
         
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

    public function getPackageFile($packageUrl)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $packageUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => [
                "Authorization: Basic " . base64_encode(json_encode($this->licenses))
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return ["error" => "cURL Error #:" . $err];
        } else {
            $getPackages = json_decode($response, true);
            if (isset($getPackages['packages']) && is_array($getPackages['packages'])) {
                return $getPackages['packages'];
            }
            return [];
        }
    }

}
