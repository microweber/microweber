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

//namespace Composer\Installer;
namespace MicroweberPackages\Package\Installer;


use Composer\Installer\InstallationManager as InstallationManagerComposer;


use Composer\IO\ConsoleIO;
use Composer\Repository\RepositoryInterface;
use Composer\DependencyResolver\Operation\OperationInterface;
use React\Promise\PromiseInterface;



/**
 * Package operation manager.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 * @author Jordi Boggiano <j.boggiano@seld.be>
 * @author Nils Adermann <naderman@naderman.de>
 */
class InstallationManager extends InstallationManagerComposer
{

    // @todo  bug in windows sapi_windows_set ctrl handler(): CTRL events can only be received on the main thread

    /**
     * Executes solver operation.
     *
     * @param RepositoryInterface  $repo       repository in which to add/remove/update packages
     * @param OperationInterface[] $operations operations to execute
     * @param bool                 $devMode    whether the install is being run in dev mode
     * @param bool                 $runScripts whether to dispatch script events
     */
    public function execute(RepositoryInterface $repo, array $operations, $devMode = true, $runScripts = true)
    {
        $promises = array();
        $cleanupPromises = array();

        $loop = $this->loop;
        $runCleanup = function () use (&$cleanupPromises, $loop) {
            $promises = array();

            $loop->abortJobs();

            foreach ($cleanupPromises as $cleanup) {
                $promises[] = new \React\Promise\Promise(function ($resolve, $reject) use ($cleanup) {
                    $promise = $cleanup();
                    if (!$promise instanceof PromiseInterface) {
                        $resolve();
                    } else {
                        $promise->then(function () use ($resolve) {
                            $resolve();
                        });
                    }
                });
            }

            if (!empty($promises)) {
                $loop->wait($promises);
            }
        };

        $handleInterruptsUnix = function_exists('pcntl_async_signals') && function_exists('pcntl_signal');
        $handleInterruptsWindows = false; //@todo bug in windows sapi_windows_set ctrl handler(): CTRL events can only be received on the main thread
        $prevHandler = null;
        $windowsHandler = null;
        if ($handleInterruptsUnix) {
            pcntl_async_signals(true);
            $prevHandler = pcntl_signal_get_handler(SIGINT);
            pcntl_signal(SIGINT, function ($sig) use ($runCleanup, $prevHandler) {
                $runCleanup();

                if (!in_array($prevHandler, array(SIG_DFL, SIG_IGN), true)) {
                    call_user_func($prevHandler, $sig);
                }

                exit(130);
            });
        }
        if ($handleInterruptsWindows) {
            $windowsHandler = function () use ($runCleanup) {
                $runCleanup();

                exit(130);
            };
            sapi_windows_set_ctrl_handler($windowsHandler, true);
        }

        try {
            foreach ($operations as $index => $operation) {
                $opType = $operation->getOperationType();

                // ignoring alias ops as they don't need to execute anything at this stage
                if (!in_array($opType, array('update', 'install', 'uninstall'))) {
                    continue;
                }

                if ($opType === 'update') {
                    $package = $operation->getTargetPackage();
                    $initialPackage = $operation->getInitialPackage();
                } else {
                    $package = $operation->getPackage();
                    $initialPackage = null;
                }
                $installer = $this->getInstaller($package->getType());

                $cleanupPromises[$index] = function () use ($opType, $installer, $package, $initialPackage) {
                    // avoid calling cleanup if the download was not even initialized for a package
                    // as without installation source configured nothing will work
                    if (!$package->getInstallationSource()) {
                        return;
                    }

                    return $installer->cleanup($opType, $package, $initialPackage);
                };

                if ($opType !== 'uninstall') {
                    $promise = $installer->download($package, $initialPackage);
                    if ($promise) {
                        $promises[] = $promise;
                    }
                }
            }

            // execute all downloads first
            if (!empty($promises)) {
                $progress = null;
                if ($this->outputProgress && $this->io instanceof ConsoleIO && !$this->io->isDebug() && count($promises) > 1) {
                    $progress = $this->io->getProgressBar();
                }
                $this->loop->wait($promises, $progress);
                if ($progress) {
                    $progress->clear();
                }
            }

            // execute operations in batches to make sure every plugin is installed in the
            // right order and activated before the packages depending on it are installed
            $batches = array();
            $batch = array();
            foreach ($operations as $index => $operation) {
                if (in_array($operation->getOperationType(), array('update', 'install'), true)) {
                    $package = $operation->getOperationType() === 'update' ? $operation->getTargetPackage() : $operation->getPackage();
                    if ($package->getType() === 'composer-plugin' || $package->getType() === 'composer-installer') {
                        if ($batch) {
                            $batches[] = $batch;
                        }
                        $batches[] = array($index => $operation);
                        $batch = array();

                        continue;
                    }
                }
                $batch[$index] = $operation;
            }

            if ($batch) {
                $batches[] = $batch;
            }

            foreach ($batches as $batch) {
                $this->executeBatch($repo, $batch, $cleanupPromises, $devMode, $runScripts, $operations);
            }
        } catch (\Exception $e) {
            $runCleanup();

            if ($handleInterruptsUnix) {
                pcntl_signal(SIGINT, $prevHandler);
            }
            if ($handleInterruptsWindows) {
                sapi_windows_set_ctrl_handler($prevHandler, false);
            }

            throw $e;
        }

        if ($handleInterruptsUnix) {
            pcntl_signal(SIGINT, $prevHandler);
        }
        if ($handleInterruptsWindows) {
            sapi_windows_set_ctrl_handler($prevHandler, false);
        }

        // do a last write so that we write the repository even if nothing changed
        // as that can trigger an update of some files like InstalledVersions.php if
        // running a new composer version
        $repo->write($devMode, $this);
    }


}
