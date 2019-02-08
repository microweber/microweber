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

        //  dd($output);

//        $input = new ArrayInput(array('command' => 'update'));
//
////Create the application and run it with the commands
//        $application = new Application();
//        $application->setAutoExit(false);
//        $out = $application->run($input);
//     dd($out);
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

    public function search_packages($keyword = '')
    {
        $keyword = strip_tags($keyword);
        $keyword = trim($keyword);

        // $conf = $this->composer_home . '/composer.json';
        $temp_folder = $this->_prepare_composer_workdir($keyword);

        if (!$temp_folder) {
            return array('error' => 'Error preparing installation for ' . $keyword);

        }
        $conf = $temp_folder . '/composer.json';
        $this->composer_temp_folder = $temp_folder;
        chdir($temp_folder);
        // $io = new BufferIO($input, $output, null);

        $io = new BufferIO('', 1, null);

        $composer = Factory::create($io);

        $packages = new ComposerPackagesSearchCommandController();
        $packages->setConfigPathname($conf);
        $packages->setDisableNonActiveReposInComposer(true);
        $packages->setComposer($composer);
        $return = $packages->handle($keyword);


        $local_packages = mw()->update->collect_local_data();


        if ($return) {
            foreach ($return as $pk => $package) {

                if (isset($package['type'])
                    and isset($package['latest_version'])
                    and isset($package['latest_version']['extra'])
                    and isset($package['latest_version']['extra']['folder'])
                ) {
                    $package_type = $package['type'];
                    $package_folder = $package['latest_version']['extra']['folder'];

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


        if (!isset($params['require_name']) or !$params['require_name']) {
            return;
        }
        $version = 'latest';
        if (isset($params['version']) and $params['version']) {
            $version = trim($params['version']);
        }

        $params = parse_params($params);

        // $io = new BufferIO($input, $output, null);
        $keyword = $params['require_name'];
        $keyword = strip_tags($keyword);
        $keyword = trim($keyword);

        //   $conf = $this->composer_home . '/composer.json';
        //$conf_auth = $this->composer_home . '/auth.json';


        $return = $this->search_packages($keyword);

        if (!$return) {
            return array('error' => 'Error. Cannot find any packages for ' . $keyword);
        }

        //   $composer_orig = @file_get_contents($conf);

        //  $composer_orig = @json_decode($composer_orig, true);

        if (!isset($return[$keyword])) {
            return array('error' => 'Error. Package not found in repositories ' . $keyword);

        }

        if (isset($return[$keyword])) {
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
            if (!isset($version_data['dist']) or !isset($version_data['dist'][0])) {
                return array('error' => 'Error resolving Composer dependencies. No download source found for ' . $keyword);

            }

            $io = new BufferIO('', 1, new HtmlOutputFormatter());

            $composer = Factory::create($io);


            // $temp_folder = storage_path('composer/' . url_title($keyword));
            // $temp_folder = $this->_prepare_composer_workdir($keyword);
            $temp_folder = $this->composer_temp_folder;

            if (!$temp_folder) {
                return array('error' => 'Error preparing installation for ' . $keyword);

            }


//
//            if (!is_dir($temp_folder)) {
//                mkdir_recursive($temp_folder);
//            }
//
//            $conf_new = $temp_folder . '/composer.json';
//            $conf_new = normalize_path($conf_new, false);
//
//            $auth_new = $temp_folder . '/auth.json';
//            $auth_new = normalize_path($conf_new, false);
//
//            $new_composer_config = array('require' => array(
//                //  $keyword => 'dev-master'
//                $keyword => '*'
//            ));
//
//            if (isset($composer_orig['repositories'])) {
//                $new_composer_config['repositories'] = $composer_orig['repositories'];
//                $new_composer_config['config'] = $composer_orig['config'];
//                $new_composer_config['minimum-stability'] = 'dev';
//                $new_composer_config['vendor-dir'] = $temp_folder;
//                $new_composer_config['config']['no-plugins'] = true;
//            }
//
//            file_put_contents($conf_new, json_encode($new_composer_config));
//            if (is_file($conf_auth)) {
//                copy($conf_auth, $auth_new);
//            }


            chdir($temp_folder);


            $argv = array();
            //  $argv[] = 'dry-run';
            // $argv[] = '--no-plugins';


            $input = new ArgvInput($argv);
            $input = new ArrayInput($argv);
            $output = new ConsoleOutput();
            $helper = new HelperSet();
            $config = new Config();


            $output->setVerbosity(0);
            $io = new ConsoleIO($input, $output, $helper);
            $composer = Factory::create($io);

            //       $input->setOption('no-plugins',true);


            $installation_manager = $composer->getInstallationManager();

            $installation_manager->addInstaller(new TemplateInstaller($io, $composer));
            $installation_manager->addInstaller(new ModuleInstaller($io, $composer));


            $composer->setConfig($config);
            //$update = new InstallCommand();
            $update = new \Microweber\Utils\Adapters\Packages\InstallCommand();
            $update->setComposer($composer);
            $update->setIO($io);
            $out = $update->run($input, $output);


            dd($out);


            return $return;


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

    public function _prepare_composer_workdir($package_name = '')
    {
        $temp_folder = storage_path('composer/temp');
        $cache_folder = storage_path('composer/cache');

        if ($package_name) {
            $temp_folder = storage_path('composer/' . url_title($package_name));
        }

        if (!is_dir($temp_folder)) {
            mkdir_recursive($temp_folder);
        }


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
            $new_composer_config = array('require' => array(
                //  $keyword => 'dev-master'
                $package_name => '*'
            ));
        } else {
            $new_composer_config = array();
        }
        if (isset($composer_orig['repositories'])) {

            $new_composer_config['repositories'] = $composer_orig['repositories'];

            $new_composer_config['repositories']['packagist'] = false;


            $new_composer_config['config'] = $composer_orig['config'];
            $new_composer_config['minimum-stability'] = 'dev';
            // $new_composer_config['vendor-dir'] = $temp_folder;
            //   $new_composer_config['config']['no-plugins'] = true;
            $new_composer_config['config']['cache-dir'] = $cache_folder;
            $new_composer_config['config']['preferred-install'] = 'dist';
            $new_composer_config['config']['discard-changes'] = true;
            $new_composer_config['config']['htaccess-protect'] = true;
            $new_composer_config['config']['archive-format'] = 'zip';
            // $new_composer_config['notify-batch'] = 'https://installreport.services.microweberapi.com/';
            //  $new_composer_config['notification-url'] = 'https://installreport.services.microweberapi.com/';

        }

        file_put_contents($conf_new, json_encode($new_composer_config));
        if (is_file($conf_auth)) {

            $composer_auth_temp = @file_get_contents($conf_auth);

            $composer_auth_temp = @json_decode($composer_auth_temp, true);

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

        }
        return $temp_folder;

    }


}
