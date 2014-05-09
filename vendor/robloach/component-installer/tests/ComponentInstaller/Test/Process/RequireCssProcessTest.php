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
use ComponentInstaller\Process\RequireCssProcess;
use Composer\Config;

/**
 * Tests RequireCssProcess.
 */
class RequireCssProcessTest extends ProcessTest
{
    protected $process;

    public function setUp()
    {
        parent::setUp();
        $this->process = new RequireCssProcess($this->composer, $this->io);
    }
    /**
     * testPackageStyles
     *
     * @dataProvider providerPackageStyles
     */
    public function testPackageStyles(array $packages, array $config, $expected = null)
    {
        $this->composer->getConfig()->merge(array('config' => $config));
        $this->process->init();
        $result = $this->process->packageStyles($packages);
        $this->assertEquals($expected, $result);
    }

    public function providerPackageStyles()
    {
        // Test collecting one style.
        $package = array(
            'name' => 'components/package',
            'type' => 'component',
            'extra' => array(
                'component' => array(
                    'styles' => array(
                        'tests/ComponentInstaller/Test/Resources/test.css',
                    ),
                ),
            ),
            'is-root' => true,
        );
        $packages = array($package);
        $expected = array(
            'package' => array(
                'tests/ComponentInstaller/Test/Resources/test.css' => array(
                    realpath('tests/ComponentInstaller/Test/Resources/test.css'),
                ),
            ),
        );
        $tests[] = array($packages, array(), $expected);

        // Test collecting a style that doesn't exist.
        $package2 = array(
            'name' => 'components/package2',
            'type' => 'component',
            'extra' => array(
                'component' => array(
                    'styles' => array(
                        'tests/ComponentInstaller/Test/Resources/test-not-found.css',
                    ),
                ),
            ),
            'is-root' => true,
        );
        $packages = array($package, $package2);
        $expected = array(
            'package' => array(
                'tests/ComponentInstaller/Test/Resources/test.css' => array(
                    realpath('tests/ComponentInstaller/Test/Resources/test.css'),
                )
            )
        );
        $tests[] = array($packages, array(), $expected);

        // Test collecting a style that doesn't exist.
        $package3 = array(
            'name' => 'components/package3',
            'type' => 'component',
            'extra' => array(
                'component' => array(
                    'styles' => array(
                        'tests/ComponentInstaller/Test/Resources/test2.css',
                    ),
                ),
            ),
            'is-root' => true,
        );
        $packages = array($package, $package3);
        $expected = array(
            'package' => array(
                'tests/ComponentInstaller/Test/Resources/test.css' => array(
                    realpath('tests/ComponentInstaller/Test/Resources/test.css'),
                ),
            ),
            'package3' => array(
                'tests/ComponentInstaller/Test/Resources/test2.css' => array(
                    realpath('tests/ComponentInstaller/Test/Resources/test2.css'),
                ),
            )
        );
        $tests[] = array($packages, array(), $expected);

        // Test collecting a style that uses glob().
        $package = array(
            'name' => 'components/package4',
            'type' => 'component',
            'extra' => array(
                'component' => array(
                    'styles' => array(
                        'tests/ComponentInstaller/Test/Resources/*.css',
                    ),
                ),
            ),
            'is-root' => true,
        );
        $packages = array($package);
        $expected = array(
            'package4' => array(
                'tests/ComponentInstaller/Test/Resources/*.css' => array(
                    realpath('tests/ComponentInstaller/Test/Resources/test.css'),
                    realpath('tests/ComponentInstaller/Test/Resources/test2.css'),
                )
            )
        );
        $tests[] = array($packages, array(), $expected);

        return $tests;
    }
}
