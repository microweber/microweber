<?php

namespace Microweber\Utils;

use Composer\Console\Application;
use Composer\Command\UpdateCommand;
use Composer\Command\InstallCommand;
use Composer\Command\SearchCommand;
use Composer\Config;
use Symfony\Component\Console\Input\ArrayInput;
use Composer\Factory;
use Composer\IO\ConsoleIO;
use Composer\IO\BufferIO;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\StreamOutput;
use Composer\Installer;
use Composer\Package\CompletePackageInterface;
use Composer\Package\Link;
use Composer\Package\PackageInterface;
use Composer\Repository\CompositeRepository;
use Composer\Repository\PlatformRepository;
use Composer\Repository\RepositoryInterface;
use  Microweber\Utils\Adapters\Packages\ComposerPackagesSearchCommandController;
use Composer\Console\HtmlOutputFormatter;
use Microweber\Utils\Adapters\Packages\Helpers\TemplateInstaller;
use Microweber\Utils\Adapters\Packages\Helpers\ModuleInstaller;
use Microweber\Utils\Adapters\Packages\Helpers\CoreUpdateInstaller;
use Microweber\Utils\Adapters\Packages\Helpers\InstallerIO;

class ComposerUpdate
{
    public $composer_home = null;

    public function __construct($composer_path = false)
    {
        if ($composer_path == false) {
            $composer_path = normalize_path(base_path() . '/', false);
        }
        $this->composer_home = $composer_path;
        putenv('COMPOSER_HOME=' . $composer_path);
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
        ob_end_clean();

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

    public function run_install()
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
        ob_end_clean();

        return $out;
    }

    public function search_packages($keyword = '', $version = false)
    {
        $keyword = strip_tags($keyword);
        $keyword = trim($keyword);

        // $conf = $this->composer_home . '/composer.json';
        $temp_folder = $this->_prepare_composer_workdir($keyword, $version);

        if (!$temp_folder) {
            return array('error' => 'Error preparing installation for ' . $keyword);

        }
        $conf = $temp_folder . '/composer.json';
        $this->composer_temp_folder = $temp_folder;


        $conf_temp = $temp_folder . '/composer.json';
        $composer_temp = @file_get_contents($conf_temp);
        $composer_temp = @json_decode($composer_temp, true);


        chdir($temp_folder);
        // $io = new BufferIO($input, $output, null);


        $io = new InstallerIO('',32, null);
        //$io->setVerbosity(32);

        $config = new Config(false, $temp_folder);

        if ($composer_temp) {
            $config->merge($composer_temp);
        }

        //  dd($config->raw());


        $composer = Factory::create($io);

        $composer->setConfig($config);


        $packages = new ComposerPackagesSearchCommandController();
        $packages->setIo($io);
        $packages->setConfigPathname($conf);
        $packages->setDisableNonActiveReposInComposer(true);
        $packages->setComposer($composer);

        $return = $packages->handle($keyword);


        $local_packages = mw()->update->collect_local_data();


        if ($return) {
            foreach ($return as $pk => $package) {


                if (isset($package['type'])
                    and isset($package['latest_version'])
                    and isset($package['latest_version'])
                    and isset($package['latest_version']['folder'])
                ) {
                    $package_type = $package['type'];

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
                    }


                    if ($package_folder and $local_packages_type) {

                        if (isset($local_packages[$local_packages_type]) and is_array($local_packages[$local_packages_type])) {
                            foreach ($local_packages[$local_packages_type] as $local_package_item) {

                                if (isset($local_package_item['dir_name'])) {
                                    if (strtolower($local_package_item['dir_name']) == strtolower($package_folder)) {
                                        $local_package_item['composer_type'] = $package_type;
                                        $local_package_item['local_type'] = $local_packages_type;
                                        $package['current_install'] = $local_package_item;
                                    }
                                }
                            }
                        }
                    }

                    $return[$pk] = $package;

                }

            }
        }


        return $return;

    }

    private $composer_temp_folder = '';

    public function install_package_by_name($params)
    {


        $params = parse_params($params);
        $install_core_update = false;

        $need_confirm = true;
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
            return;
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


        $return = $this->search_packages($keyword, $version);


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
            'Microweber\Utils\Adapters\Packages\Helpers\TemplateInstaller',
            'Microweber\Utils\Adapters\Packages\Helpers\ModuleInstaller'
        );
        if ($keyword == 'microweber/update') {
            $install_core_update = true;
            $installers = array(
                'Microweber\Utils\Adapters\Packages\Helpers\CoreUpdateInstaller'
            );
        }


        $to_folder = mw_root_path();


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
                return array(
                    'error' => _e('You need license key', true),
                    // 'form_data_required' => 'license_key',
                    'form_data_module' => 'settings/group/license_edit',
                    'form_data_module_params' => array(
                        'require_name' => $params['require_name'],
                        'require_version' => $version
                    )
                );
            }


            // $temp_folder = storage_path('composer/' . url_title($keyword));
            // $temp_folder = $this->_prepare_composer_workdir($keyword);

            if (!$temp_folder) {
                return array('error' => 'Error preparing installation for ' . $keyword);

            }

            $conf_temp = $temp_folder . '/composer.json';
            $composer_temp = @file_get_contents($conf_temp);
            $composer_temp = @json_decode($composer_temp, true);


            chdir($temp_folder);


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
            $io = new InstallerIO('',32, null);

            $composer = Factory::create($io);

            //       $input->setOption('no-plugins',true);


            $installation_manager = $composer->getInstallationManager();


            if ($installers) {
                foreach ($installers as $installer) {
                    $installation_manager->addInstaller(new $installer($io, $composer));
                }
            }

            $conf = $temp_folder . '/composer.json';


            $composer->setConfig($config);
            //$update = new InstallCommand();
            $update = new \Microweber\Utils\Adapters\Packages\InstallCommand();
            $update->setComposer($composer);
            $update->setIO($io);
            $out = $update->run($input, $output);


            if ($install_core_update) {
                $from_folder_cp = $temp_folder . '/microweber-core-update/install-update/update/';
                $from_folder = $from_folder_cp;
            }


            if ($out === 0) {

                $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($from_folder));
                $allFiles = array_filter(iterator_to_array($iterator), function ($file) {
                    return $file->isFile();
                });
                $allFiles = array_keys($allFiles);

                $skip_files = array('composer.json', 'auth.json', 'composer.lock', 'vendor');

                $cp_files = array();
                if ($allFiles) {
                    foreach ($allFiles as $file_to_copy) {
                        $file_to_copy = str_ireplace($from_folder, '', $file_to_copy);

                        $skip = false;


                        foreach ($skip_files as $skip_file) {
                            if (stripos($file_to_copy, $skip_file) === 0) {
                                $skip = true;
                            }
                        }


                        if (!$skip) {
                            $cp_files [] = normalize_path($file_to_copy, false);
                        }


                    }
                }


                if ($need_confirm) {

                    cache_save($cp_files, $confirm_key, 'composer');

                    return array(
                        'error' => _e('Please confirm installation', true),
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
                }

            }

            foreach ($cp_files as $f) {
                $src = $from_folder . DS . $f;
                $dest = $to_folder . DS . $f;
                $src = normalize_path($src, false);
                $dest = normalize_path($dest, false);
                $dest_dn = dirname($dest);
                if (!is_dir($dest_dn)) {
                    mkdir_recursive($dest_dn);
                }
                if (copy($src, $dest)) {
                    //ok
                } else {
                    $cp_files_fails[] = $f;
                }
            }
            $resp = array();
            $resp['success'] = 'Success. You have installed: ' . $keyword . ' .  Total ' . count($cp_files) . ' files installed';
            $resp['log'] = $cp_files;
            if ($cp_files_fails) {
                $resp['errors'] = $cp_files_fails;
            }

            clearcache();

            return $resp;

        }


    }


    public function get_require()
    {
        $conf = $this->composer_home . '/composer.json';

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

    public function save_require($required)
    {
        $conf = $this->composer_home . '/composer.json';

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
        $conf = $this->composer_home . '/composer.json';

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

    public function _get_composer_workdir_path($package_name = '')
    {
        $temp_folder = mw_cache_path() . 'composer/temp';
        if ($package_name) {
            $temp_folder = mw_cache_path() . 'composer/' . url_title($package_name);
        }

        if (!is_dir($temp_folder)) {
            mkdir_recursive($temp_folder);
        }
        return $temp_folder;


    }

    public function _prepare_composer_workdir($package_name = '', $version = false)
    {
        $cache_folder = mw_cache_path() . 'composer/cache';
        $data_folder = mw_cache_path() . 'composer/data';

//        if ($package_name) {
//            $temp_folder = storage_path('composer/' . url_title($package_name));
//        }
//
//        if (!is_dir($temp_folder)) {
//            mkdir_recursive($temp_folder);
//        }
//


        $temp_folder = $this->_get_composer_workdir_path($package_name . '-' . $version);


        $conf = $this->composer_home . '/composer.json';
        $conf_auth = $this->composer_home . '/auth.json';
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
        $system_repos[] = array("type" => "composer", "url" => "https://packages.microweberapi.com/");
        $system_repos[] = array("type" => "composer", "url" => "https://private-packages.microweberapi.com/");

        $composer_orig['repositories'] = array_merge($composer_orig['repositories'], $system_repos);
        $new_composer_config['repositories'] = $composer_orig['repositories'];

        $new_composer_config['repositories']['packagist'] = false;


        $new_composer_config['config'] = $composer_orig['config'];
         $new_composer_config['minimum-stability'] = 'dev';
       // $new_composer_config['minimum-stability'] = 'stable';
        //   $new_composer_config['target-dir'] = 'installed';


        // $new_composer_config['vendor-dir'] = $temp_folder;
        //   $new_composer_config['config']['no-plugins'] = true;
        $new_composer_config['config']['cache-dir'] = $cache_folder;
        $new_composer_config['config']['data-dir'] = $data_folder;
        $new_composer_config['config']['preferred-install'] = 'dist';
        $new_composer_config['config']['discard-changes'] = true;
        $new_composer_config['config']['htaccess-protect'] = true;
        $new_composer_config['config']['store-auths'] = false;
        $new_composer_config['config']['use-include-path'] = false;
        $new_composer_config['config']['discard-changes'] = true;
        $new_composer_config['config']['archive-format'] = 'zip';
        //  $new_composer_config['notify-batch'] = 'https://installreport.services.microweberapi.com/';
        //  $new_composer_config['notification-url'] = 'https://installreport.services.microweberapi.com/';


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

        $lic = mw()->update->get_licenses();

        $lic = json_encode($lic);
        $lic = base64_encode($lic);

        if (isset($composer_auth_temp['http-basic'])) {
            $composer_auth_temp['http-basic']["private-packages.microweberapi.com"] = array(
                "username" => @gethostname(),
                "password" => $lic
            );
            file_put_contents($auth_new, json_encode($composer_auth_temp));
        }


        return $temp_folder;

    }


}
