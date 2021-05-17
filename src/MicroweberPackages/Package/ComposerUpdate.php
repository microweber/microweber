<?php

namespace MicroweberPackages\Package;

use Composer\Command\UpdateCommand;
use Composer\Command\InstallCommand;
use Composer\Console\Application;
use Composer\Installers\Installer;
use Composer\Plugin\PluginManager;
use MicroweberPackages\App\Models\SystemLicenses;
use MicroweberPackages\Package\Installer\InstallationManager;
use MicroweberPackages\Package\PackageManagerUnzipOnChunksException;
use MicroweberPackages\Utils\System\Files;
use Symfony\Component\Console\Input\ArrayInput;
use MicroweberPackages\Package\ComposerFactory as Factory;
use Composer\IO\ConsoleIO;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\ConsoleOutput;
use MicroweberPackages\Package\Helpers\InstallerIO;
use Composer\Semver\Comparator;
use Illuminate\Support\Str;


class ComposerUpdate
{
    public $repos = [
        ["type" => "composer", "url" => "https://packages.microweberapi.com/"]
    ];

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

        // Fill the user licenses
        $findLicenses = SystemLicenses::all();
        if ($findLicenses !== null) {
            $this->licenses = $findLicenses->toArray();
        }
    }

    public function setComposerHome($composerHomePath)
    {
        if (php_can_use_func('putenv')) {
            putenv('COMPOSER_HOME=' . $composerHomePath);
            $this->composerHome = $composerHomePath;
        }
    }

    public function setTargetPath($path)
    {
        $this->targetPath = $path;
    }

    public function setRepos($repos)
    {
        $this->repos = $repos;
    }

    public function run($config_params = array())
    {
        ob_start();
        $input = new ArgvInput(array());
        $output = new ConsoleOutput();
        $helper = new HelperSet();
        $config = new ComposerConfig();

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
        $config = new ComposerConfig();

        $output->setVerbosity(0);
        $io = new ConsoleIO($input, $output, $helper);
        $composer = Factory::create($io);
        $composer->setConfig($config);
        $update = new InstallCommand();
        $update->setComposer($composer);
        $out = $update->run($input, $output);


        return $out;
    }

    public function setUpdateChannel($channel)
    {
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

        //   dd($conf_temp);
        $composer_temp = file_get_contents($conf_temp);


        $composer_temp = json_decode($composer_temp, true);

        chdir($temp_folder);
        // $io = new BufferIO($input, $output, null);

        $io = new InstallerIO('', 32, null);
        //$io->setVerbosity(32);

        $config = new ComposerConfig(false, $temp_folder);

        if ($composer_temp) {
            $config->merge($composer_temp);
        }

        // ob_start();


        //  $manager = new InstallationManager($loop,  $io, $eventDispatcher );
        // $composer = Factory::create($io, $composer_temp);
        $composer = ComposerFactory::create($io, $composer_temp);
        //  $composer->setInstallationManager($manager);
        $composer->setConfig($config);

        $pm = new PluginManager($io, $composer, $globalComposer = null, $disablePlugins = true);
        $composer->setPluginManager($pm);

        $repositoryManager = $composer->getRepositoryManager();

        $packages = new ComposerPackagesSearchCommandController();
        $packages->setIo($io);

        $packages->setConfigPathname($conf);
        $packages->setDisableNonActiveReposInComposer(true);
        $packages->setComposer($composer);

        $return = $packages->handle($keyword);


        $return_found = array();
        $return_packages_with_updates = array();

        //     $local_packages = array(); // TODO mw()->update->collect_local_data();
        $local_packages = mw()->update->collect_local_data();

        $local_packages_found = array();
        if ($return) {
            foreach ($return as $pk => $package) {
                $is_found_on_local = false;

                if (isset($package['type'])
                    and isset($package['latest_version'])
                    and isset($package['latest_version'])

                ) {
                    $package_type = $package['type'];

                    if ($search_by_type) {
                        if ($search_by_type != $package_type) {
                            unset($return[$pk]);
                            continue;
                        }
                    }

                    $package_folder = false;
                    if (!$package_folder and isset($package['latest_version']) and isset($package['latest_version']) and isset($package['latest_version']['folder'])) {
                        $package_folder = $package['latest_version']['folder'];
                    }


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

                                        if ($local_packages_type == 'modules') {
                                            $local_dir = modules_path() . $local_package_item['dir_name'];
                                            if (!is_dir($local_dir)) {
                                                $is_found_on_local = false;
                                            }
                                        }

                                        $local_package_item['composer_type'] = $package_type;
                                        $local_package_item['local_type'] = $local_packages_type;

                                        $package['current_install'] = false;

                                        if ($is_found_on_local) {
                                            $package['current_install'] = $local_package_item;
                                        }

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
        $cp_files_user = array();
        $cp_files_fails = array();
        $cp_packages_files = array();
        $out = 0;


        $confirm_key = 'composer-confirm-key-' . rand();

        if (isset($params['confirm_key'])) {
            $confirm_key_get = $params['confirm_key'];
            $get_existing_files_for_confirm = cache_get($confirm_key_get, 'composer');
            if ($get_existing_files_for_confirm) {
                if (isset($get_existing_files_for_confirm['user'])) {
                    $cp_files_user = $get_existing_files_for_confirm['user'];
                }

                if (isset($get_existing_files_for_confirm['packages'])) {
                    $cp_packages_files = $get_existing_files_for_confirm['packages'];
                }

                $need_confirm = false;
            }
        }


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


        //  $temp_folder = $this->composer_temp_folder;


        $temp_folder = $this->_prepareComposerWorkdir($keyword, $version, true);
        if (!$temp_folder) {
            return array('error' => 'Error preparing installation for ' . $keyword);
        }

        //  $conf = $temp_folder . '/composer.json';

        //   $this->composer_temp_folder = $temp_folder;


        //   $conf_temp = $temp_folder . '/composer.json';

        //  $composer_temp = file_get_contents($conf_temp);


        //   $composer_temp = json_decode($composer_temp, true);

        chdir($temp_folder);
        $this->composerPath = $temp_folder;

        $installers = [];
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
        // $installers[]='MicroweberPackages\Package\Helpers\PackageDependenciesInstaller';


        $to_folder = $this->targetPath;
        if (function_exists('mw_root_path')) {
            $to_folder = mw_root_path();
        }

        if (!$cp_files_user and isset($return[$keyword])) {
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


            $conf_temp = $temp_folder . '/composer.json';


            //$temp_folder
            $composer_temp = file_get_contents($conf_temp);
            $composer_temp = json_decode($composer_temp, true);


            $current_composer_file = $temp_folder . '/composer.json';
            $current_composer = file_get_contents($current_composer_file);
            $current_composer = json_decode($current_composer, true);

//                   if (isset($version_data['requires']) && is_array($version_data['requires'])) {
//                foreach ($version_data['requires'] as $requirePackage => $requireDetails) {
//                    $current_composer['require'][$requirePackage] = $requireDetails->getPrettyConstraint();
//                }
//            }
//


            if (isset($current_composer['repositories']) and isset($current_composer['repositories']['packagist'])) {
                unset($current_composer['repositories']['packagist']);
            }

            if (!isset($current_composer['extra'])) {
                $current_composer['extra'] = [];
            }

            if (!isset($current_composer['require'])) {
                $current_composer['require'] = [];
            }


            file_put_contents($current_composer_file, json_encode($current_composer, JSON_PRETTY_PRINT));

            $composer_temp = file_get_contents($conf_temp);
            $composer_temp = json_decode($composer_temp, true);


            $argv = array();
            //  $argv[] = 'dry-run';
            // $argv[] = '--no-plugins';
            //    $argv[] = '--working-dir=' . escapeshellarg($temp_folder);


            //   $input = new ArgvInput($argv);
            $input = new ArrayInput($argv);
            $output = new ConsoleOutput();
            $helper = new HelperSet();
            $config = new ComposerConfig(false, $temp_folder);
//dd($temp_folder);
            if ($composer_temp) {
                $config->merge($composer_temp);
            }


            //$output->setVerbosity(1);
            //$io = new ConsoleIO($input, $output, $helper);
            $io = new InstallerIO($input, 32, null);
            // $io = new NullIO('', false, null);

            $composer = Factory::create($io);
            //$loop, IOInterface $io, EventDispatcher $eventDispatcher

            //       $input->setOption('no-plugins',true);

            $installation_manager = $composer->getInstallationManager();


            if ($installers) {
                foreach ($installers as $installer) {
                    $installation_manager->addInstaller(new $installer($io, $composer));
                }
            }
            $composer->setConfig($config);


            //add shared installer here


//            $shared_data_manager = new SharedPackageDataManager($composer);
//            $sym_fs = new SymlinkFilesystem();
//           // $sym_conf = new SharedPackageInstallerConfig(mw_root_path().'/vendor','vendor-shared' , $composer_temp['extra']);
//            $sym_conf = new SharedPackageInstallerConfig('','vendor-shared' , $composer_temp['extra']);
//            //$sym_conf = new SharedPackageInstallerConfig('vendor-shared', mw_root_path().'/vendor', $composer_temp['extra']);
//
//
//             $shared_insaller = new SharedPackageInstaller($io,$composer,$sym_fs,$shared_data_manager,$sym_conf);
//            $installation_manager->addInstaller($shared_insaller);

            $update = new \MicroweberPackages\Package\InstallCommand();
            $update->setComposer($composer);


            $update->setIO($io);
            //  dd($update);
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
            $skip_files = [];
            $copy_to_packages_folder = array('composer.json', 'composer.lock', 'vendor');
            if (!$install_core_update) {

                $skip_files = array('composer.json', 'auth.json', 'composer.lock', 'vendor', 'packages.json');

            } else {
                $skip_files = array('composer.json', 'auth.json', 'composer.lock', 'packages.json');

            }

            $from_folder2 = normalize_path($from_folder, true);

            $cp_files_user = array();
            $cp_packages_files = array();
            if ($allFiles) {

                foreach ($allFiles as $file_to_copy) {
                    $file_to_copy = str_ireplace($from_folder, '', $file_to_copy);
                    $file_to_copy = str_ireplace($from_folder2, '', $file_to_copy);


                    $skip = false;

                    foreach ($skip_files as $skip_file) {
                        if (Str::startsWith($file_to_copy, $skip_file)) {
                            $skip = true;
                        }


//                            if (stripos($file_to_copy, $skip_file) !== false) {
//                                $skip = true;
//                            }
//                            if ((stripos($file_to_copy, 'files\\') !== false) && (stripos($file_to_copy, '.zip') !== false)) {
//                                $skip = true;
//                            }
                    }

                    if (!$skip) {
                        $cp_files_user[] = normalize_path($file_to_copy, false);
                    }


                }
                /*   foreach ($allFiles as $file_to_copy) {
                       foreach ($copy_to_packages_folder as $packages_file) {
                           if (stripos($file_to_copy, $packages_file) !== false) {
                               $cp_packages_files[] = normalize_path($packages_file, false);
                           }
                       }

                       if (!empty($cp_packages_files)) {
                           array_unique($cp_packages_files);
                       }

                   }*/


            }

            if ($need_confirm) {

                if (function_exists('cache_save')) {

                    $cp_files_cache = array();
                    $cp_files_cache['user'] = $cp_files_user;
                    $cp_files_cache['packages'] = $cp_packages_files;
                    cache_save($cp_files_cache, $confirm_key, 'composer');
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


        /* if (!$install_core_update) {

             if (!$need_confirm) {
                 if ($cp_packages_files) {
                     $cp_packages_files = array_unique($cp_packages_files);
                     if($cp_packages_files){
                         foreach ($cp_packages_files as $f) {
                             $src = $from_folder . DIRECTORY_SEPARATOR . $f;
                             $dest = $to_folder .DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . $f;
                             $src = normalize_path($src, false);
                             $dest = normalize_path($dest, false);
                             $dest_dn = dirname($dest);

                             if (!is_dir($dest_dn)) {
                                 mkdir_recursive($dest_dn);
                             }
                             if(is_dir($src)){
                                 $files_manager = new Files();
                                 $files_manager->copy_directory($src, $dest);
                             } else if(is_file($src)){
                                 copy($src,$dest);
                             }

                          }
                     }

                 }
             }
         }*/

        if ($cp_files_user and !empty($cp_files_user)) {

            if ($install_core_update) {
                if ($install_core_update) {
                    $from_folder_cp = $temp_folder . '/microweber-core-update/install-update/update/';
                    $from_folder = $from_folder_cp;
                    $from_folder = normalize_path($from_folder, true);
                }
            }

            foreach ($cp_files_user as $f) {
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
            $resp['success'] = 'Success. You have installed: ' . $keyword . ' .  Total ' . count($cp_files_user) . ' files installed';
            $resp['log'] = $cp_files_user;
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
            $temp_folder = $cache_path . 'composer/' . md5($package_name);
        }

        if (!is_dir($temp_folder)) {
            mkdir_recursive($temp_folder);
        }

        return $temp_folder;

    }

    public function _prepareComposerWorkdir($package_name = '', $version = false, $prepare_for_install = false)
    {

        if ('disabled' == $this->updateChannel) {
            return;
        }

        $cache_folder = false;
        $data_folder = false;

//        if (function_exists('mw_cache_path')) {
//            $cache_folder = mw_cache_path() . 'composer/cache';
//            $data_folder = mw_cache_path() . 'composer/data';
//        }

        $cache_folder = '../composer/cache';
        $data_folder = '../composer/data';


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


        $packages_folder_name = 'packages';
        $packages_folder = $this->composerPath . '/' . $packages_folder_name . '/';
        $files_manager = new Files();
        $composer_json_file_path = $this->composerPath . '/composer.json';
        $conf_auth = $this->composerPath . '/auth.json';

        $composer_json_file_path = normalize_path($composer_json_file_path, false);
        $conf_auth = normalize_path($conf_auth, false);

        $conf_new = $temp_folder . '/composer.json';
        $conf_new = normalize_path($conf_new, false);

        $composer_orig = @file_get_contents($composer_json_file_path);
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


        if (!isset($new_composer_config['require'])) {
            $new_composer_config['require'] = [];
        }


        /*       if ($prepare_for_install) {

                   $composer_json_packages_folder = [];
                   // copy packages folder
                   $user_installed_composer_json_file_path = normalize_path($packages_folder . 'composer.json', false);
                   $user_installed_json_file_path = normalize_path($packages_folder . 'vendor/composer/installed.json', false);


                   if (is_file($user_installed_composer_json_file_path) and is_file($user_installed_json_file_path)) {

                       $files_manager->copy_directory($packages_folder, $temp_folder);


                       $composer_json_packages_folder = @file_get_contents($user_installed_composer_json_file_path);
                       $composer_json_packages_folder = json_decode($composer_json_packages_folder, true);
                   }

                   if (isset($composer_json_packages_folder) and isset($composer_json_packages_folder['require']) and $composer_json_packages_folder['require']) {
                       $new_composer_config['require'] = array_merge($new_composer_config['require'], $composer_json_packages_folder['require']);
                       array_unique($new_composer_config['require']);
                   }

               }*/


        if (isset($new_composer_config['repositories'])) {
            //$new_composer_config['repositories'] = array_unique($new_composer_config['repositories']);
        }


        $system_repos = $this->repos;


//        $system_repos = array();
//
//        if ($this->updateChannel == 'dev') {
//            $system_repos[] = array("type" => "composer", "url" => "https://packages-dev.microweberapi.com/");
//            $system_repos[] = array("type" => "composer", "url" => "https://packages.microweberapi.com/");
//        } else {
//            $system_repos[] = array("type" => "composer", "url" => "https://packages.microweberapi.com/");
//            //  $system_repos[] = array("type" => "composer", "url" => "https://packages.microweberapi.com/");
//            // $system_repos[] = array("type" => "composer", "url" => "https://private-packages.microweberapi.com/");
//        }


        //  $system_repos = array();
        //   $system_repos[] = array("type" => "composer", "url" => "https://packages-satis.microweberapi.com/");

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


        if ($prepare_for_install) {

            //   $new_composer_config['repositories']['packagist'] = true;

        }

        $new_composer_config['repositories']['packagist'] = false;
        $new_composer_config['repositories']['packagist.org'] = false;

        if (!isset($composer_orig['config'])) {
            $composer_orig['config'] = [];
        }


        $new_composer_config['config'] = $composer_orig['config'];
        // $new_composer_config['minimum-stability'] = 'dev';
        $new_composer_config['minimum-stability'] = 'stable';
        $new_composer_config['prefer-stable'] = true;
        //   $new_composer_config['target-dir'] = 'installed';
        // $new_composer_config['vendor-dir'] = $temp_folder;
        $new_composer_config['config']['no-plugins'] = true;
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


        file_put_contents($conf_new, json_encode($new_composer_config, JSON_PRETTY_PRINT));

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

            @file_put_contents($auth_new, json_encode($composer_auth_temp, JSON_PRETTY_PRINT));
        }


        return $temp_folder;

    }
}
