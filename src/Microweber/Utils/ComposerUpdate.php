<?php


namespace Microweber\Utils;

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
    public function __construct()
    {
        $composer_path = normalize_path(base_path() . '/', false);
        putenv('COMPOSER_HOME=' . $composer_path);
    }

     public function run()
    {
        //Create the commands


        $input = new ArgvInput(array());
        $output = new ConsoleOutput();
        $helper = new HelperSet();

        $io = new ConsoleIO($input, $output, $helper);
        $composer = Factory::create($io);

        $update = new UpdateCommand();
        $update->setComposer($composer);
        $out = $update->run($input, $output);

//        $update = new InstallCommand();
//        $update->setComposer($composer);
//        $out = $update->run($input, $output);

        d($out);
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
}
