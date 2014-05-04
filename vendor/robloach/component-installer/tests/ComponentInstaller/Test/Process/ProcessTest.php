<?php

/*
 * This file is part of Component Installer.
 *
 * (c) Rob Loach (http://robloach.net)
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace ComponentInstaller\Test\Process;

use ComponentInstaller\Process\Process;
use Composer\Composer;
use Composer\Config;
use Composer\IO\NullIO;
use Composer\Util\Filesystem;
use Composer\Installer\InstallationManager;
use Composer\Installer\LibraryInstaller;
use ComponentInstaller\Installer;

/**
 * Tests Process.
 */
class ProcessTest extends \PHPUnit_Framework_TestCase
{
    protected $composer;
    protected $config;
    protected $io;
    protected $filesystem;
    protected $componentDir;
    protected $vendorDir;
    protected $binDir;
    protected $installationManager;

    public function setUp()
    {
        $this->filesystem = new Filesystem();
        $this->composer = new Composer();
        $this->config = new Config();
        $this->io = new NullIO();

        $this->componentDir = realpath(sys_get_temp_dir()).DIRECTORY_SEPARATOR.'component-installer-componentDir';
        $this->vendorDir = realpath(sys_get_temp_dir()).DIRECTORY_SEPARATOR.'component-installer-vendorDir';
        $this->binDir = realpath(sys_get_temp_dir()).DIRECTORY_SEPARATOR.'component-installer-binDir';

        foreach (array($this->componentDir, $this->vendorDir, $this->binDir) as $dir) {
            if (is_dir($dir)) {
                $this->filesystem->removeDirectory($dir);
            }
            $this->filesystem->ensureDirectoryExists($dir);
        }
        $this->config->merge(array(
            'config' => array(
                'vendor-dir' => $this->vendorDir,
                'component-dir' => $this->componentDir,
                'bin-dir' => $this->binDir,
            )
        ));
        $this->composer->setConfig($this->config);

        // Set up the Installation Manager.
        $this->installationManager = new InstallationManager();
        $this->installationManager->addInstaller(new LibraryInstaller($this->io, $this->composer));
        $this->installationManager->addInstaller(new Installer($this->io, $this->composer));
        $this->composer->setInstallationManager($this->installationManager);
    }

    protected function tearDown()
    {
        foreach (array($this->componentDir, $this->vendorDir, $this->binDir) as $dir) {
            $this->filesystem->removeDirectory($dir);
        }
    }

    /**
     * testGetComponentName
     *
     * @dataProvider providerGetComponentName
     */
    public function testGetComponentName($prettyName, array $extra, $expected)
    {
        $process = new Process($this->composer, $this->io);
        $result = $process->getComponentName($prettyName, array('component' => $extra));
        $this->assertEquals($result, $expected, sprintf('Fail to get proper component name for %s', $prettyName));
    }

    /**
     * Data provider for testGetComponentName.
     *
     * @see testGetComponentName()
     */
    public function providerGetComponentName()
    {
        return array(
            array('components/jquery', array(), 'jquery'),
            array('components/jquery', array('name' => 'myownjquery'), 'myownjquery'),
            array('jquery', array(), 'jquery'),
        );
    }
}
