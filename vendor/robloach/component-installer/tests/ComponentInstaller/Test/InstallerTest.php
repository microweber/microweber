<?php

/*
 * This file is part of Component Installer.
 *
 * (c) Rob Loach (http://robloach.net)
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Composer\Test;

use Composer\Test\Installer\LibraryInstallerTest;
use ComponentInstaller\Installer;
use Composer\Package\Loader\ArrayLoader;
use Composer\Package\Package;
use Composer\Composer;
use Composer\Config;

/**
 * Tests registering Component Installer with Composer.
 */
class InstallerTest extends LibraryInstallerTest
{
    protected $componentDir = 'components';

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        // Run through the Library Installer Test set up.
        parent::setUp();

        // Also be sure to set up the Component directory.
        $this->componentDir = realpath(sys_get_temp_dir()).DIRECTORY_SEPARATOR.'composer-test-component';
        $this->ensureDirectoryExistsAndClear($this->componentDir);

        // Merge the component-dir setting in so that it applies correctly.
        $this->config->merge(array(
            'config' => array(
                'component-dir' => $this->componentDir,
            ),
        ));
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        $this->fs->removeDirectory($this->componentDir);

        return parent::tearDown();
    }

    /**
     * Tests that the Installer doesn't create the Component directory.
     */
    public function testInstallerCreationShouldNotCreateComponentDirectory()
    {
        $this->fs->removeDirectory($this->componentDir);
        new Installer($this->io, $this->composer);
        $this->assertFileNotExists($this->componentDir);
    }

    /**
     * Test the Installer's support() function.
     *
     * @param $type
     *   The type of library.
     * @param $expected
     *   Whether or not the given type is supported by Component Installer.
     *
     * @return void
     *
     * @dataProvider providerComponentSupports
     */
    public function testComponentSupports($type, $expected)
    {
        $installer = new Installer($this->io, $this->composer, 'component');
        $this->assertSame($expected, $installer->supports($type), sprintf('Failed to show support for %s', $type));
    }

    /**
     * Data provider for testComponentSupports().
     *
     * @see testComponentSupports()
     */
    public function providerComponentSupports()
    {
        // All package types support having Components.
        $tests[] = array('component', true);
        $tests[] = array('not-a-component', false);
        $tests[] = array('library', false);

        return $tests;
    }

    /**
     * Tests the Installer's getComponentPath function.
     *
     * @param $expected
     *   The expected install path for the package.
     * @param $package
     *   The package to test upon.
     *
     * @dataProvider providerGetComponentPath
     *
     * @see \ComponentInstaller\Installer::getComponentPath()
     */
    public function testGetComponentPath($expected, $package) {
        // Construct the mock objects.
        $installer = new Installer($this->io, $this->composer, 'component');
        $loader = new ArrayLoader();

        // Test the results.
        $result = $installer->getComponentPath($loader->load($package));
        $this->assertEquals($this->componentDir . '/' . $expected, $result);
    }

    /**
     * Data provider for testGetComponentPath().
     *
     * @see testGetComponentPath()
     */
    public function providerGetComponentPath()
    {
        $package = array(
            'name' => 'foo/bar',
            'type' => 'component',
            'version' => '1.0.0',
        );
        $tests[] = array('bar', $package);

        $package = array(
            'name' => 'foo/bar2',
            'version' => '1.0.0',
            'type' => 'component',
            'extra' => array(
                'component' => array(
                    'name' => 'foo',
                ),
            ),
        );
        $tests[] = array('foo', $package);

        return $tests;
    }
}
