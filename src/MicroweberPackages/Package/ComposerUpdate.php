<?php
namespace MicroweberPackages\Package;

use Composer\Command\UpdateCommand;
use Composer\Command\InstallCommand;
use Composer\Config;
use MicroweberPackages\Package\PackageManagerUnzipOnChunksException;
use Symfony\Component\Console\Input\ArrayInput;
use MicroweberPackages\Package\ComposerFactory as Factory;
use Composer\IO\ConsoleIO;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\ConsoleOutput;
use MicroweberPackages\Package\Helpers\InstallerIO;
use Composer\Semver\Comparator;

class ComposerUpdate
{
    public $licenses = array();
    public $updateChannel = null;
    public $composerPath;
    public $composerHome;
    public $targetPath;

    public function __construct($composerPath = false)
    {
        if ($composerPath == false) {
            if (function_exists('base_path')) {
                $composerPath = normalize_path(base_path() . '/', false);
            } else {
                $composerPath = getcwd();
            }
        }

        $this->composerPath = $composerPath;
    }

    public function setComposerHome($composerHomePath)
    {
        if (php_can_use_func('putenv')) {
            putenv('COMPOSER_HOME=' . $composerHomePath);
            $this->composerHome = $composerHomePath;
        }
    }

    public function setTargetPath($path) {
        $this->targetPath = $path;
    }

    public function run($config_params = array())
    {
        ob_start();
        $input = new ArgvInput(array());
        $output = new ConsoleOutput();
        $helper = new HelperSet();
        $config = new Config();

        if (!empty($config_params)) {
            $config_composer = array('config' => $config_params);
            $config->merge($config_composer);
        }
//        $config_composer = array('config' => array(
//            'prepend-autoloader' => false,
//            'no-install' => true,
//            'no-scripts' => true,
//            'no-plugins' => true,
//            'no-progress' => true,
//            'no-dev' => true,
//            'no-custom-installers' => true,
//            'no-autoloader' => true
//        ));

        $output->setVerbosity(0);
        $io = new ConsoleIO($input, $output, $helper);
        $composer = Factory::create($io);
        $composer->setConfig($config);
        $update = new UpdateCommand();
        $update->setComposer($composer);
        $out = $update->run($input, $output);
        

        return $out;

//        $update = new InstallCommand();
//        $update->setComposer($composer);
//        $out = $update->run($input, $output);


//        $input = new ArrayInput(array('command' => 'update'));
//
////Create the application and run it with the commands
//        $application = new Application();
//        $application->setAutoExit(false);
//        $out = $application->run($input);

//        echo "Done.";
    }

    public function runInstall()
    {
        ob_start();
        $input = new ArgvInput(array());
        $output = new ConsoleOutput();
        $helper = new HelperSet();
        $config = new Config();

        $output->setVerbosity(0);
        $io = new ConsoleIO($input, $output, $helper);
        $composer = Factory::create($io);
        $composer->setConfig($config);
        $update = new InstallCommand();
        $update->setComposer($composer);
        $out = $update->run($input, $output);
        

        return $out;
    }

    public function setUpdateChannel($channel) {
        $this->updateChannel = $channel;
    }

    public function searchPackages($params)
    {
        if ('disabled' == $this->updateChannel) {
            return;
        }
        $params = parse_params($params);

        $version = 'latest';
        $keyword = '';
        $return_only_updates = false;
        $search_by_type = false;

        if (isset($params['require_name']) and $params['require_name']) {
            $keyword = trim($params['require_name']);
        } else if (isset($params['keyword']) and $params['keyword']) {
            $keyword = trim($params['keyword']);
        }

        if (isset($params['require_version']) and $params['require_version']) {
            $version = trim($params['require_version']);
        }
        if (isset($params['return_only_updates']) and $params['return_only_updates']) {
            $return_only_updates = $params['return_only_updates'];
        }


        if (isset($params['search_by_type']) and $params['search_by_type']) {
            $search_by_type = $params['search_by_type'];
        }


        $keyword = strip_tags($keyword);
        $keyword = trim($keyword);

        $temp_folder = $this->_prepareComposerWorkdir($keyword, $version);
        if (!$temp_folder) {
            return array('error' => 'Error preparing installation for ' . $keyword);
        }

        $conf = $temp_folder . '/composer.json';

        $this->composer_temp_folder = $temp_folder;


        $conf_temp = $temp_folder . '/composer.json';

        $composer_temp = file_get_contents($conf_temp);


        $composer_temp = json_decode($composer_temp, true);

         chdir($temp_folder);
        // $io = new BufferIO($input, $output, null);

        $io = new InstallerIO('', 32, null);
        //$io->setVerbosity(32);

        $config = new Config(false, $temp_folder);

        if ($composer_temp) {
            $config->merge($composer_temp);
        }

        ob_start();

        $composer = Factory::create($io,$composer_temp);

        $composer->setConfig($config);
        $repositoryManager = $composer->getRepositoryManager();

        $packages = new ComposerPackagesSearchCommandController();
        $packages->setIo($io);

        $packages->setConfigPathname($conf);
        $packages->setDisableNonActiveReposInComposer(true);
        $packages->setComposer($composer);

        $return = $packages->handle($keyword);




        $return_found = array();
        $return_packages_with_updates = array();

        $local_packages = array(); // TODO mw()->update->collect_local_data();

        $local_packages_found = array();
        if ($return) {
            foreach ($return as $pk => $package) {
                $is_found_on_local = false;

                if (isset($package['type'])
                    and isset($package['latest_version'])
                    and isset($package['latest_version'])

                ) {
                    $package_type = $package['type'];

                    if($search_by_type){
                        if($search_by_type != $package_type){
                            unset($return[$pk]);
                            continue;
                        }
                    }

                    $package_folder = false;
                    if (!$package_folder and isset($package['latest_version']) and isset($package['latest_version']) and isset($package['latest_version']['folder'])) {
                        $package_folder = $package['latest_version']['folder'];
                    }


                    $local_packages_type = false;
                    switch ($package_type) {
                        case 'microweber-template':
                            $local_packages_type = 'templates';
                            break;
                        case 'microweber-module':
                            $local_packages_type = 'modules';
                            break;

                        case 'microweber-core-update':

                            $is_found_on_local = true;
                            $local_packages_type = false;
                            $local_packages_type = 'core';

                            break;
                    }

                    if ($local_packages_type == 'core') {
                        $v1 = trim($package['latest_version']['version']);
                        $v2 = trim(MW_VERSION);
                        $has_update = Comparator::equalTo($v1, $v2);
                        if ($has_update) {
                            $package['has_update'] = true;
                            $return_packages_with_updates[$pk] = $package;

                        }

                    }


                    $package_update_found = false;

                    if ($package_folder and $local_packages_type) {

                        if (isset($local_packages[$local_packages_type]) and is_array($local_packages[$local_packages_type])) {
                            foreach ($local_packages[$local_packages_type] as $lpk => $local_package_item) {

                                if (isset($local_package_item['dir_name'])) {
                                    if ($local_package_item['dir_name'] == $package_folder) {

                                        $is_found_on_local = true;

                                        $local_package_item['composer_type'] = $package_type;
                                        $local_package_item['local_type'] = $local_packages_type;
                                        $package['current_install'] = $local_package_item;
                                        $package['has_update'] = false;

                                        $package_update_found = false;

                                        if (isset($package['latest_version']) and isset($package['latest_version']['version']) and isset($local_package_item['version'])) {
                                            $v1 = trim($package['latest_version']['version']);
                                            $v2 = trim($local_package_item['version']);

                                            if ($v1 != $v2) {

                                                $has_update = Comparator::greaterThan($v1, $v2);

                                                if ($has_update) {
                                                    $package_update_found = true;
                                                    $package['has_update'] = true;
                                                }
                                            }
                                        }


                                        if ($return_only_updates) {
                                            if (!$package_update_found) {
                                                $package = false;
                                            } else {
                                                $return_packages_with_updates[$pk] = $package;
                                            }
                                        }


                                    }


                                }

                            }
                        }
                    }


                    if ($package) {
                        if ($is_found_on_local) {
                            $return_found[$pk] = $package;
                        }

                        $return[$pk] = $package;
                    }
                }

            }
        }


        if (isset($params['return_local_packages']) and $params['return_local_packages']) {
            return $return_found;
        }

        if ($return_only_updates) {
            return $return_packages_with_updates;
        }

        return $return;

    }

    public function installPackageByName($params)
    {

        if ('disabled' == $this->updateChannel) {
            return;
        }

        app()->update->clear_log();

        $params = parse_params($params);
        $install_core_update = false;

        $need_confirm = true;
        $cp_files = array();
        $cp_files_fails = array();


        $confirm_key = 'composer-confirm-key-' . rand();

        if (isset($params['confirm_key'])) {
            $confirm_key_get = $params['confirm_key'];
            $get_existing_files_for_confirm = cache_get($confirm_key_get, 'composer');
            if ($get_existing_files_for_confirm) {
                $cp_files = $get_existing_files_for_confirm;
                $need_confirm = false;
            }
        }


        // if (!$cp_files) {

        if (!isset($params['require_name']) or !$params['require_name']) {
            throw new \Exception('Please set require name.');
        }

        $version = 'latest';
        if (isset($params['require_version']) and $params['require_version']) {
            $version = trim($params['require_version']);
        }

        $keyword = $params['require_name'];
        $keyword = strip_tags($keyword);
        $keyword = trim($keyword);

        $version = strip_tags($version);
        $version = trim($version);

        $return = $this->searchPackages($params);

        if (!$return) {
            return array('error' => 'Error. Cannot find any packages for ' . $keyword);
        }

        if (!isset($return[$keyword])) {
            return array('error' => 'Error. Package not found in repositories ' . $keyword);

        }
        //   }

        $temp_folder = $this->composer_temp_folder;
        $from_folder = normalize_path($temp_folder, true);
        $installers = array(
            'MicroweberPackages\Package\Helpers\TemplateInstaller',
            'MicroweberPackages\Package\Helpers\ModuleInstaller'
        );
        if ($keyword == 'microweber/update') {
            $install_core_update = true;
            $installers = array(
                'MicroweberPackages\Package\Helpers\CoreUpdateInstaller'
            );
        }


        $to_folder = $this->targetPath;
        if (function_exists('mw_root_path')) {
            $to_folder = mw_root_path();
        }

        if (!$cp_files and isset($return[$keyword])) {
            $version_data = false;
            $package_data = $return[$keyword];

            if ($version == 'latest' and isset($package_data['latest_version']) and $package_data['latest_version']) {
                $version_data = $package_data['latest_version'];
            } elseif (isset($package_data['versions']) and isset($package_data['versions'][$version])) {
                $version_data = $package_data['versions'][$version];
            }

            if (!$version_data) {
                return;
            }

            $dryRun = false;
            $need_key = false;
            if (!isset($version_data['dist']) or !isset($version_data['dist'][0])) {
                return array('error' => 'No download source found for ' . $keyword);
            }

            if (isset($version_data['dist_type']) and ($version_data['dist_type']) == 'license_key') {
                $need_key = true;
            }

            if ($need_key) {
                $error_text = 'You need license key';
                if (function_exists('_e')) {
                    $error_text = _e($error_text, true);
                }

                return array(
                    'error' => $error_text,
                    // 'form_data_required' => 'license_key',
                    'form_data_module' => 'settings/group/license_edit',
                    'form_data_module_params' => array(
                        'require_name' => $params['require_name'],
                        'require_version' => $version
                    )
                );
            }

            if (!$temp_folder) {
                return array('error' => 'Error preparing installation for ' . $keyword);

            }

            $conf_temp = getcwd().'/composer.json';
            $composer_temp = file_get_contents($conf_temp);
            $composer_temp = json_decode($composer_temp, true);

            copy($conf_temp,mw_root_path().'/cache/composer.json');

            $current_composer_file = $temp_folder . '/composer.json';
            $current_composer = file_get_contents($current_composer_file);
            $current_composer = json_decode($current_composer, true);
            if (isset($version_data['requires']) && is_array($version_data['requires'])) {
                foreach ($version_data['requires'] as $requirePackage => $requireDetails) {
                    $current_composer['require'][$requirePackage] = $requireDetails->getPrettyConstraint();
                }
            }
            unset($current_composer['repositories']['packagist']);
            $current_composer['require']['spatie/laravel-medialibrary'] = 'dev-master';
            file_put_contents($current_composer_file, json_encode($current_composer));

            $argv = array();
            //  $argv[] = 'dry-run';
            // $argv[] = '--no-plugins';

            $input = new ArgvInput($argv);
            $input = new ArrayInput($argv);
            $output = new ConsoleOutput();
            $helper = new HelperSet();
            $config = new Config(false, $temp_folder);

            if ($composer_temp) {
                $config->merge($composer_temp);
            }

            //$output->setVerbosity(1);
            //$io = new ConsoleIO($input, $output, $helper);
            $io = new InstallerIO('', 32, null);
            // $io = new NullIO('', false, null);

            $composer = Factory::create($io);

            //       $input->setOption('no-plugins',true);

            $installation_manager = $composer->getInstallationManager();


            if ($installers) {
                foreach ($installers as $installer) {
                    $installation_manager->addInstaller(new $installer($io, $composer));
                }
            }

            $composer->setConfig($config);
            $update = new \MicroweberPackages\Package\InstallCommand();
            $update->setComposer($composer);
            $update->setIO($io);

            try {
                $out = $update->run($input, $output);
            } catch (PackageManagerUnzipOnChunksException $e) {
                $cache_key_for_unzip_on_chunks = $e->getMessage();

                return array(
                    'try_again' => true,
                    'error' => 'There was error with unzip',
                    // 'unzip_cache_key' => $cache_key_for_unzip_on_chunks
                );
            }


            if ($install_core_update) {
                $from_folder_cp = $temp_folder . '/microweber-core-update/install-update/update/';
                $from_folder = $from_folder_cp;
                $from_folder = normalize_path($from_folder, true);
            }


            if ($out === 0) {

                $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($from_folder));
                $allFiles = array_filter(iterator_to_array($iterator), function ($file) {
                    return $file->isFile();
                });
                $allFiles = array_keys($allFiles);

                if (!$install_core_update) {
                    $skip_files = array('composer.json', 'auth.json', 'composer.lock', 'vendor', 'packages.json');

                } else {
                    $skip_files = array('composer.json', 'auth.json', 'composer.lock', 'packages.json');

                }

                $from_folder2 = normalize_path($from_folder, true);

                $cp_files = array();
                if ($allFiles) {
                    foreach ($allFiles as $file_to_copy) {
                        $file_to_copy = str_ireplace($from_folder, '', $file_to_copy);
                        $file_to_copy = str_ireplace($from_folder2, '', $file_to_copy);


                        $skip = false;

                        foreach ($skip_files as $skip_file) {
                            if (stripos($file_to_copy, $skip_file) !== false) {
                                $skip = true;
                            }
                            if ((stripos($file_to_copy, 'files\\') !== false) && (stripos($file_to_copy, '.zip') !== false)) {
                                $skip = true;
                            }
                        }

                        if (!$skip) {
                            $cp_files[] = normalize_path($file_to_copy, false);
                        }

                    }
                }

                if ($need_confirm) {

                    if (function_exists('cache_save')) {
                        cache_save($cp_files, $confirm_key, 'composer');
                    }

                    $error = 'Please confirm installation';

                    return array(
                        'error' => $error,
                        //   'form_data_required' => 'confirm_key',
                        'form_data_module' => 'admin/developer_tools/package_manager/confirm_install',
                        'form_data_module_params' => array(
                            'confirm_key' => $confirm_key,
                            'require_name' => $params['require_name'],
                            'require_version' => $version
                        )
                    );

                }

            }

        }

        if ($cp_files and !empty($cp_files)) {

            if ($install_core_update) {
                if ($install_core_update) {
                    $from_folder_cp = $temp_folder . '/microweber-core-update/install-update/update/';
                    $from_folder = $from_folder_cp;
                    $from_folder = normalize_path($from_folder, true);
                }
            }

            foreach ($cp_files as $f) {
                $src = $from_folder . DIRECTORY_SEPARATOR . $f;
                $dest = $to_folder . DIRECTORY_SEPARATOR . $f;
                $src = normalize_path($src, false);
                $dest = normalize_path($dest, false);
                $dest_dn = dirname($dest);

                if (!is_dir($dest_dn)) {
                    mkdir_recursive($dest_dn);
                }

                if (copy($src, $dest)) {
                    //ok
                    unlink($src);
                } else {
                    $cp_files_fails[] = $f;
                    unlink($src);
                }
            }

            $resp = array();
            $resp['success'] = 'Success. You have installed: ' . $keyword . ' .  Total ' . count($cp_files) . ' files installed';
            $resp['log'] = $cp_files;
            if ($cp_files_fails) {
                $resp['errors'] = $cp_files_fails;
            }

            if (function_exists('clearcache')) {
                clearcache();
            }

            if (function_exists('app') && isset(app()->update)) {
                if ($install_core_update) {
                    app()->update->post_update($version);
                } else {
                    app()->update->post_update();
                }
            }

            $s = 'skip_cache=1';
            $s .= '&cleanup_db=1';
            $s .= '&reload_modules=1';

            $mods = scan_for_modules($s);

            
            return $resp;

        }

    }


    public function getRequire()
    {
        $conf = $this->composerPath . '/composer.json';

        $conf_items = array();
        if (is_file($conf)) {
            $existing = file_get_contents($conf);
            if ($existing != false) {
                $conf_items = json_decode($existing, true);
            }
            if (isset($conf_items['require'])) {
                return $conf_items['require'];
            }
        }
    }

    public function saveRequire($required)
    {
        $conf = $this->composerPath . '/composer.json';

        $conf_items = array();
        if (is_file($conf)) {
            $existing = file_get_contents($conf);
            if ($existing != false) {
                $conf_items = json_decode($existing, true);
            }
            if (!empty($required)) {
                $req = $required;

                if (is_array($req) and !empty($req)) {
                    $conf_items['require'] = $req;
                    $conf_items = json_encode($conf_items, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                    $save = file_put_contents($conf, $conf_items);
                }
            }
        }
    }

    public function merge($with_file)
    {
        $conf = $this->composerPath . '/composer.json';

        $conf_items = array();
        if (is_file($conf)) {
            $existing = file_get_contents($conf);
            if ($existing != false) {
                $conf_items = json_decode($existing, true);
            }
            if (!isset($conf_items['require'])) {
                $conf_items['require'] = array();
            }
        }
        if (is_file($with_file)) {
            $new_conf_items = array();
            $new = file_get_contents($with_file);
            if ($new != false) {
                $new_conf_items = json_decode($new, true);
            }

            if (!empty($new_conf_items) and isset($new_conf_items['require'])) {
                $req = $new_conf_items['require'];

                if (is_array($req) and !empty($req)) {
                    $all_reqs = array_merge($conf_items['require'], $req);
                    $conf_items['require'] = $all_reqs;
                    $conf_items = json_encode($conf_items, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                    $save = file_put_contents($conf, $conf_items);
                }
            }
        }

        return $conf_items;
    }

    private function _getComposerWorkdirPath($package_name = '')
    {
        $cache_path = $this->composerPath . '/';
        if (function_exists('mw_cache_path')) {
            $cache_path = mw_cache_path();
        }

        $temp_folder = $cache_path . 'composer/temp';
        if ($package_name) {
            $temp_folder = $cache_path. 'composer/' . md5($package_name);
        }

        if (!is_dir($temp_folder)) {
            mkdir_recursive($temp_folder);
        }

        return $temp_folder;

    }

    public function _prepareComposerWorkdir($package_name = '', $version = false)
    {

        if ('disabled' == $this->updateChannel) {
            return;
        }

        $cache_folder = false;
        $data_folder = false;
        
        if (function_exists('mw_cache_path')) {
            $cache_folder = mw_cache_path() . 'composer/cache';
            $data_folder = mw_cache_path() . 'composer/data';
        }



//        if ($package_name) {
//            $temp_folder = storage_path('composer/' . url_title($package_name));
//        }
//
//        if (!is_dir($temp_folder)) {
//            mkdir_recursive($temp_folder);
//        }
//


        $temp_folder = $this->_getComposerWorkdirPath($package_name . '-' . $version);
        $custom_repos_urls_from_settings = array();

        if ($custom_repos_urls_from_settings) {
            $temp_folder = $this->_getComposerWorkdirPath($package_name . '-' . $version . '-' . md5(@json_encode($custom_repos_urls_from_settings)));
        }

        $conf = $this->composerPath . '/composer.json';
        $conf_auth = $this->composerPath . '/auth.json';

        $conf = normalize_path($conf, false);
        $conf_auth = normalize_path($conf_auth, false);

        $conf_new = $temp_folder . '/composer.json';
        $conf_new = normalize_path($conf_new, false);

        $composer_orig = @file_get_contents($conf);
        $composer_orig = @json_decode($composer_orig, true);


        $auth_new = $temp_folder . '/auth.json';
        $auth_new = normalize_path($auth_new, false);

        $new_composer_config = array();
        if ($package_name) {

            if (!$version or $version == 'latest') {
                $version = '*';
            }
            $new_composer_config = array('require' => array(
                //  $keyword => 'dev-master'
                $package_name => $version
            ));
        } else {
            $new_composer_config = array('repositories' => array());
        }

        if (!isset($composer_orig['repositories']) or !is_array($composer_orig['repositories'])) {
            $composer_orig['repositories'] = array();
        }

        $system_repos = array();

        if ($this->updateChannel == 'dev') {
            $system_repos[] = array("type" => "composer", "url" => "https://packages-dev.microweberapi.com/");
            $system_repos[] = array("type" => "composer", "url" => "https://packages-satis.microweberapi.com/");
        } else {
            $system_repos[] = array("type" => "composer", "url" => "https://packages.microweberapi.com/");
            $system_repos[] = array("type" => "composer", "url" => "https://private-packages.microweberapi.com/");
        }

        $system_repos = array();
        $system_repos[] = array("type" => "composer", "url" => "https://packages-satis.microweberapi.com/");

        $system_repos_custom = array();

        if ($custom_repos_urls_from_settings) {
            $custom_repos_urls = array();
            if (is_string($custom_repos_urls_from_settings)) {
                $custom_repos_urls_from_settings = explode(',', $custom_repos_urls_from_settings);
            }
            if (is_array($custom_repos_urls_from_settings)) {
                foreach ($custom_repos_urls_from_settings as $custom_repos_urls_from_setting) {
                    if (is_string($custom_repos_urls_from_setting)) {
                        $valid_host = parse_url($custom_repos_urls_from_setting);
                        if (isset($valid_host['host']) and isset($valid_host['scheme'])) {
                            $system_repos_custom[] = array("type" => "composer", "url" => $valid_host['scheme'] . "://" . $valid_host['host'] . "/");
                        }
                    }
                }
            }
        }


        if ($system_repos_custom) {
            $system_repos = $system_repos_custom;
            $composer_orig['repositories'] = $system_repos;
            $new_composer_config['repositories'] = $composer_orig['repositories'];
        } else {
            //   $composer_orig['repositories'] = array_merge($composer_orig['repositories'], $system_repos);
            $composer_orig['repositories'] = $system_repos;
            $new_composer_config['repositories'] = $composer_orig['repositories'];
        }

        $new_composer_config['repositories']['packagist'] = false;


        if(!isset( $composer_orig['config'])){
            $composer_orig['config'] = [];
        }


        $new_composer_config['config'] = $composer_orig['config'];
        // $new_composer_config['minimum-stability'] = 'dev';
        $new_composer_config['minimum-stability'] = 'stable';
        $new_composer_config['prefer-stable'] = true;
        //   $new_composer_config['target-dir'] = 'installed';
        // $new_composer_config['vendor-dir'] = $temp_folder;
        //   $new_composer_config['config']['no-plugins'] = true;
        $new_composer_config['config']['cache-dir'] = $cache_folder;
        $new_composer_config['config']['data-dir'] = $data_folder;
        $new_composer_config['config']['preferred-install'] = array("*" => "dist");
        $new_composer_config['config']['cache-files-ttl'] = 900;
        //   $new_composer_config['config']['discard-changes'] = true;
        $new_composer_config['config']['discard-changes'] = false;
        $new_composer_config['config']['htaccess-protect'] = true;
        $new_composer_config['config']['store-auths'] = false;
        $new_composer_config['config']['use-include-path'] = false;
        $new_composer_config['config']['github-protocols'] = array("https", "http");
        //  $new_composer_config['config']['discard-'] = true;
        $new_composer_config['config']['archive-format'] = 'zip';
        $new_composer_config['notify-batch'] = 'https://installreport.services.microweberapi.com/';

        //  $new_composer_config['notification-url'] = 'https://installreport.services.microweberapi.com/';


        //  dd($new_composer_config);

        file_put_contents($conf_new, json_encode($new_composer_config));

        $composer_auth_temp = array();


        if (is_file($conf_auth)) {

            $composer_auth_tempf = @file_get_contents($conf_auth);
            $composer_auth_temp = @json_decode($composer_auth_tempf, true);

            if (!$composer_auth_temp) {
                $composer_auth_temp = array();
            }
        }

        if (!isset($composer_auth_temp['http-basic'])) {
            $composer_auth_temp['http-basic'] = array();
        }

        $lic = $this->licenses;

        $lic = json_encode($lic);
        $lic = base64_encode($lic);

        if (isset($composer_auth_temp['http-basic'])) {
//                    $composer_auth_temp['http-basic']["packages.microweberapi.com"] = array(
//                "username" => @gethostname(),
//                "password" => $lic
//            );


            foreach ($new_composer_config['repositories'] as $repo_auth) {
                if (isset($repo_auth['url'])) {
                    $host = parse_url($repo_auth['url'], PHP_URL_HOST);
                    if ($host) {
                        $composer_auth_temp['http-basic'][$host] = array(
                            "username" => 'license',
                            "password" => $lic
                        );

                    }
                }
            }

            @file_put_contents($auth_new, json_encode($composer_auth_temp));
        }


        return $temp_folder;

    }


    public function setLicenses($licenses) {
        $this->licenses = $licenses;
    }
}
