<?php

/*
 * This file is part of Component Installer.
 *
 * (c) Rob Loach (http://robloach.net)
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace ComponentInstaller\Process;

use Composer\IO\IOInterface;
use Composer\Composer;

interface ProcessInterface
{
    /**
     * Create a new Process.
     *
     * @param Composer $composer
     *   The Composer object to act on.
     * @param IOInterface $io
     *   Input/Output object to act on.
     */
    public function __construct(Composer $composer, IOInterface $io);

    /**
     * Initialize the process before its run.
     *
     * @return boolean
     *   Whether or not the process should continue after initialization.
     */
    public function init();

    /**
     * Called when running through the process.
     *
     * @return boolean
     *   True or false depending on whether the process was successful.
     */
    public function process();
}
