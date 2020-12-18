<?php

/*
 * This file is part of Composer.
 *
 * (c) Nils Adermann <naderman@naderman.de>
 *     Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Microweber\Utils\Adapters\Packages;

use Composer\Installer;
use Composer\Installer\InstallationManager;
use Composer\IO\NullIO;
use Composer\Plugin\CommandEvent;
use Composer\Plugin\PluginEvents;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Composer\Command\BaseCommand;
use Microweber\Utils\Adapters\Packages\Helpers\TemplateInstaller;

/**
 * @author Jordi Boggiano <j.boggiano@seld.be>
 * @author Ryan Weaver <ryan@knplabs.com>
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 * @author Nils Adermann <naderman@naderman.de>
 */
class InstallCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('install')
            ->setDescription('Installs the project dependencies from the composer.lock file if present, or falls back on the composer.json.')
            ->setDefinition(array(
                new InputOption('prefer-source', null, InputOption::VALUE_NONE, 'Forces installation from package sources when possible, including VCS information.'),
                new InputOption('prefer-dist', null, InputOption::VALUE_NONE, 'Forces installation from package dist even for dev versions.'),
                new InputOption('dry-run', null, InputOption::VALUE_NONE, 'Outputs the operations but will not execute anything (implicitly enables --verbose).'),
                new InputOption('dev', null, InputOption::VALUE_NONE, 'Enables installation of require-dev packages (enabled by default, only present for BC).'),
                new InputOption('no-dev', null, InputOption::VALUE_NONE, 'Disables installation of require-dev packages.'),
                new InputOption('no-custom-installers', null, InputOption::VALUE_NONE, 'DEPRECATED: Use no-plugins instead.'),
                new InputOption('no-autoloader', null, InputOption::VALUE_NONE, 'Skips autoloader generation'),
                new InputOption('no-scripts', null, InputOption::VALUE_NONE, 'Skips the execution of all scripts defined in composer.json file.'),
                new InputOption('no-progress', null, InputOption::VALUE_NONE, 'Do not output download progress.'),
                new InputOption('no-suggest', null, InputOption::VALUE_NONE, 'Do not show package suggestions.'),
                new InputOption('verbose', 'v|vv|vvv', InputOption::VALUE_NONE, 'Shows more details including new commits pulled in when updating packages.'),
                new InputOption('optimize-autoloader', 'o', InputOption::VALUE_NONE, 'Optimize autoloader during autoloader dump'),
                new InputOption('classmap-authoritative', 'a', InputOption::VALUE_NONE, 'Autoload classes from the classmap only. Implicitly enables `--optimize-autoloader`.'),
                new InputOption('ignore-platform-reqs', null, InputOption::VALUE_NONE, 'Ignore platform requirements (php & ext- packages).'),
                new InputArgument('packages', InputArgument::IS_ARRAY | InputArgument::OPTIONAL, 'Should not be provided, use composer require instead to add a given package to composer.json.'),
            ))
            ->setHelp(<<<EOT
The <info>install</info> command reads the composer.lock file from
the current directory, processes it, and downloads and installs all the
libraries and dependencies outlined in that file. If the file does not
exist it will look for composer.json and do the same.

<info>php composer.phar install</info>

EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = $this->getIO();
        if ($args = $input->getArgument('packages')) {
            $io->writeError('<error>Invalid argument ' . implode(' ', $args) . '. Use "composer require ' . implode(' ', $args) . '" instead to add packages to your composer.json.</error>');

            return 1;
        }


        $composer = $this->getComposer(true, true);
        $composer->getDownloadManager()->setOutputProgress(false);
        $composer->getDownloadManager()->setPreferDist(true);
        $composer->getDownloadManager()->setPreferSource(false);

        $commandEvent = new CommandEvent(PluginEvents::COMMAND, 'install', $input, $output);
        $composer->getEventDispatcher()->dispatch($commandEvent->getName(), $commandEvent);


        $install = Installer::create($io, $composer);

        $preferSource = false;
        $preferDist = false;

        $config = $composer->getConfig();
        // set_time_limit(0);

        if (php_can_use_func('ini_set')) {
            ini_set('max_execution_time', 300);
            ini_set('memory_limit', '4777M');
        }

        $install
            //  ->setDryRun(0)
            ->setVerbose(1)
            ->setPreferSource(0)
            ->setPreferDist(1)
            ->setDevMode(false)
            ->setDumpAutoloader(false)
            ->setRunScripts(false)
            ->setSkipSuggest(true)
            ->setOptimizeAutoloader(false)
            ->setPreferStable(true)
            ->setClassMapAuthoritative(false)
            ->setIgnorePlatformRequirements(true);

        // if ($input->getOption('no-plugins')) {
        //$install->disablePlugins();
        //}

        return $install->run();
    }
}
