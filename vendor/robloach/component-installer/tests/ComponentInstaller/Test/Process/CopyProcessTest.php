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
use ComponentInstaller\Process\CopyProcess;

/**
 * Tests CopyProcess.
 */
class CopyProcessTest extends ProcessTest
{
    protected $process;

    public function setUp()
    {
        parent::setUp();
        $this->process = new CopyProcess($this->composer, $this->io);
    }

    /**
     * testCopy
     *
     * @dataProvider providerCopyStyles
     */
    public function testCopyStyles($packages, $files)
    {
        // Initialize the process.
        $this->process->init();
        $result = $this->process->copy($packages);
        foreach ($files as $file) {
            $this->assertFileExists($this->componentDir.'/' . $file, sprintf('Failed to find the destination file: %s', $file));
        }
    }

    public function providerCopyStyles()
    {
        // Test collecting one style.
        $package = array(
            'name' => 'components/package',
            'version' => '1.2.3',
            'is-root' => true, // Set the root so that it knows to use the cwd.
            'extra' => array(
                'component' => array(
                    'styles' => array(
                        'tests/ComponentInstaller/Test/Resources/test.css',
                    ),
                ),
            ),
        );
        $packages = array($package);
        $tests[] = array($packages, array(
            'package/tests/ComponentInstaller/Test/Resources/test.css',
        ));

        // Test collecting two styles.
        $package = array(
            'name' => 'components/packagewithtwostyles',
            'version' => '1.2.3',
            'is-root' => true, // Set the root so that it knows to use the cwd.
            'extra' => array(
                'component' => array(
                    'styles' => array(
                        'tests/ComponentInstaller/Test/Resources/test.css',
                        'tests/ComponentInstaller/Test/Resources/test2.css',
                    ),
                ),
            ),
        );
        $packages = array($package);
        $tests[] = array($packages, array(
            'packagewithtwostyles/tests/ComponentInstaller/Test/Resources/test.css',
            'packagewithtwostyles/tests/ComponentInstaller/Test/Resources/test2.css',
        ));

        // Test collecting a style that doesn't exist.
        $package = array(
            'name' => 'components/stylethatdoesnotexist',
            'version' => '1.2.3',
            'is-root' => true, // Set the root so that it knows to use the cwd.
            'extra' => array(
                'component' => array(
                    'styles' => array(
                        'tests/ComponentInstaller/Test/Resources/test.css',
                        'tests/ComponentInstaller/Test/Resources/test-not-found.css',
                    ),
                ),
            ),
        );
        $packages = array($package);
        $tests[] = array($packages, array(
            'stylethatdoesnotexist/tests/ComponentInstaller/Test/Resources/test.css',
        ));

        // Test collecting all styles, files and scripts.
        $package = array(
            'name' => 'components/allassets',
            'version' => '1.2.3',
            'is-root' => true, // Set the root so that it knows to use the cwd.
            'extra' => array(
                'component' => array(
                    'styles' => array(
                        'tests/ComponentInstaller/Test/Resources/test.css',
                    ),
                    'files' => array(
                        'tests/ComponentInstaller/Test/Resources/img.jpg',
                        'tests/ComponentInstaller/Test/Resources/img2.jpg',
                    ),
                    'scripts' => array(
                        'tests/ComponentInstaller/Test/Resources/test.js'
                    ),
                ),
            ),
        );
        $packages = array($package);
        $tests[] = array($packages, array(
            'allassets/tests/ComponentInstaller/Test/Resources/test.css',
            'allassets/tests/ComponentInstaller/Test/Resources/img.jpg',
            'allassets/tests/ComponentInstaller/Test/Resources/img2.jpg',
            'allassets/tests/ComponentInstaller/Test/Resources/test.js',
        ));

        // Test copying a different component name.
        $package = array(
            'name' => 'components/differentcomponentname',
            'version' => '1.2.3',
            'is-root' => true, // Set the root so that it knows to use the cwd.
            'extra' => array(
                'component' => array(
                    'name' => 'diffname',
                    'files' => array(
                        'tests/ComponentInstaller/Test/Resources/img.jpg',
                    ),
                ),
            ),
        );
        $packages = array($package);
        $tests[] = array($packages, array(
            'diffname/tests/ComponentInstaller/Test/Resources/img.jpg',
        ));

        // Test two different packages.
        $package = array(
            'name' => 'components/twopackages1',
            'version' => '1.2.3',
            'is-root' => true, // Set the root so that it knows to use the cwd.
            'extra' => array(
                'component' => array(
                    'files' => array(
                        'tests/ComponentInstaller/Test/Resources/img.jpg',
                    ),
                ),
            ),
        );
        $package2 = array(
            'name' => 'components/twopackages2',
            'version' => '1.2.3',
            'is-root' => true, // Set the root so that it knows to use the cwd.
            'extra' => array(
                'component' => array(
                    'name' => 'twopackages2-diff',
                    'files' => array(
                        'tests/ComponentInstaller/Test/Resources/img2.jpg',
                    ),
                ),
            ),
        );
        $packages = array($package, $package2);
        $tests[] = array($packages, array(
            'twopackages1/tests/ComponentInstaller/Test/Resources/img.jpg',
            'twopackages2-diff/tests/ComponentInstaller/Test/Resources/img2.jpg',
        ));

        // Test copying an asset with a glob().
        $package = array(
            'name' => 'components/differentcomponentname',
            'version' => '1.2.3',
            'is-root' => true, // Set the root so that it knows to use the cwd.
            'extra' => array(
                'component' => array(
                    'name' => 'diffname',
                    'files' => array(
                        'tests/ComponentInstaller/Test/Resources/*.jpg',
                    ),
                ),
            ),
        );
        $packages = array($package);
        $tests[] = array($packages, array(
            'diffname/tests/ComponentInstaller/Test/Resources/img.jpg',
            'diffname/tests/ComponentInstaller/Test/Resources/img2.jpg',
        ));

        return $tests;
    }
}
