<?php


namespace Microweber\Utils;


spl_autoload_register(function ($class) {
// fallback autoloader if some composer class is missing

    $prefix = 'Composer\\';
    if (!substr($class, 0, 10) === $prefix) {
        return;
    }
    $class = substr($class, strlen($prefix));
    $location_fallback = __DIR__ . '/lib/composer/src/Composer/' . str_replace('\\', '/', $class) . '.php';
    $location_fallback = normalize_path($location_fallback, false);
    if (is_file($location_fallback)) {
        require_once($location_fallback);
    }
});


use Composer\Console\Application;
use Composer\Command\UpdateCommand;
use Composer\Command\InstallCommand;
use Symfony\Component\Console\Input\ArrayInput;

use Composer\Factory;
use Composer\IO\ConsoleIO;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\ConsoleOutput;


class ComposerUpdate
{

    public $composer_home = null;

    public function __construct()
    {


        $composer_path = normalize_path(base_path() . '/', false);
        $this->composer_home = $composer_path;
        putenv('COMPOSER_HOME=' . $composer_path);
    }

    public function run()
    {
        //Create the commands


        $input = new ArgvInput(array());
        $output = new ConsoleOutput();
        $helper = new HelperSet();
        $output->setVerbosity(3);
        $io = new ConsoleIO($input, $output, $helper);
        $composer = Factory::create($io);

        $update = new UpdateCommand();
        $update->setComposer($composer);
        $out = $update->run($input, $output);
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
}
