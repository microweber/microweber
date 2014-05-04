<?php

/*
 * This file is part of Component Installer.
 *
 * (c) Rob Loach (http://robloach.net)
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace ComponentInstaller;

use Composer\Composer;
use Composer\Installer\LibraryInstaller;
use Composer\Script\Event;
use Composer\Package\PackageInterface;
use Composer\Package\AliasPackage;
use Composer\Util\Filesystem;

/**
 * Component Installer for Composer.
 */
class Installer extends LibraryInstaller
{

    /**
     * The location where Components are to be installed.
     */
    protected $componentDir;

    /**
     * {@inheritDoc}
     *
     * Components are supported by all packages. This checks wheteher or not the
     * entire package is a "component", as well as injects the script to act
     * on components embedded in packages that are not just "component" types.
     */
    public function supports($packageType)
    {
        // Components are supported by all package types. We will just act on
        // the root package's scripts if available.
        $rootPackage = isset($this->composer) ? $this->composer->getPackage() : null;
        if (isset($rootPackage)) {
            // Ensure we get the root package rather than its alias.
            while ($rootPackage instanceof AliasPackage) {
                $rootPackage = $rootPackage->getAliasOf();
            }

            // Make sure the root package can override the available scripts.
            if (method_exists($rootPackage, 'setScripts')) {
                $scripts = $rootPackage->getScripts();
                // Act on the "post-autoload-dump" command so that we can act on all
                // the installed packages.
                $scripts['post-autoload-dump']['component-installer'] = 'ComponentInstaller\\Installer::postAutoloadDump';
                $rootPackage->setScripts($scripts);
            }
        }

        // Explicitly state support of "component" packages.
        return $packageType === 'component';
    }

    /**
     * Gets the destination Component directory.
     *
     * @return string
     *   The path to where the final Component should be installed.
     */
    public function getComponentPath(PackageInterface $package)
    {
        // Parse the pretty name for the vendor and package name.
        $name = $prettyName = $package->getPrettyName();
        if (strpos($prettyName, '/') !== false) {
            list($vendor, $name) = explode('/', $prettyName);
        }

        // Allow the component to define its own name.
        $extra = $package->getExtra();
        $component = isset($extra['component']) ? $extra['component'] : array();
        if (isset($component['name'])) {
            $name = $component['name'];
        }

        // Find where the package should be located.
        return $this->getComponentDir() . DIRECTORY_SEPARATOR . $name;
    }

    /**
     * Initialize the Component directory, as well as the vendor directory.
     */
    protected function initializeVendorDir()
    {
        $this->componentDir = $this->getComponentDir();
        $this->filesystem->ensureDirectoryExists($this->componentDir);
        return parent::initializeVendorDir();
    }

    /**
     * Retrieves the Installer's provided component directory.
     */
    public function getComponentDir()
    {
        $config = $this->composer->getConfig();
        return $config->has('component-dir') ? $config->get('component-dir') : 'components';
    }

    /**
     * Remove both the installed code and files from the Component directory.
     */
    public function removeCode(PackageInterface $package)
    {
        $this->removeComponent($package);
        return parent::removeCode($package);
    }

    /**
     * Remove a Component's files from the Component directory.
     */
    public function removeComponent(PackageInterface $package)
    {
        $path = $this->getComponentPath($package);
        return $this->filesystem->remove($path);
    }

    /**
     * Before installing the Component, be sure its destination is clear first.
     */
    public function installCode(PackageInterface $package)
    {
        $this->removeComponent($package);
        return parent::installCode($package);
    }

    /**
     * Script callback; Acted on after the autoloader is dumped.
     */
    public static function postAutoloadDump(Event $event)
    {
        // Retrieve basic information about the environment and present a
        // message to the user.
        $composer = $event->getComposer();
        $io = $event->getIO();
        $io->write('<info>Compiling component files</info>');

        // Set up all the processes.
        $processes = array(
            // Copy the assets to the Components directory.
            "ComponentInstaller\\Process\\CopyProcess",
            // Build the require.js file.
            "ComponentInstaller\\Process\\RequireJsProcess",
            // Build the require.css file.
            "ComponentInstaller\\Process\\RequireCssProcess",
            // Compile the require-built.js file.
            "ComponentInstaller\\Process\\BuildJsProcess",
        );

        // Initialize and execute each process in sequence.
        foreach ($processes as $class) {
            $process = new $class($composer, $io);
            // When an error occurs during initialization, end the process.
            if (!$process->init()) {
                $io->write('<error>An error occurred while initializing the process.</info>');
                break;
            }
            $process->process();
        }
    }
}
