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

use ComponentInstaller\Util\Filesystem;

/**
 * Tests Process.
 */
class FilesystemTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the recursiveGlob function.
     *
     * @dataProvider providerRecursiveGlobFiles
     */
    public function testRecursiveGlobFiles($expected, $pattern, $flags = 0)
    {
        $fs = new Filesystem();
        $result = $fs->recursiveGlobFiles($pattern, $flags);

        // Sort the arrays so that the indexes don't matter.
        sort($expected);
        sort($result);

        $this->assertEquals($expected, $result);
    }

    public function providerRecursiveGlobFiles()
    {
        $tests[] = array(
            array(
                'tests/ComponentInstaller/Test/Resources/img.jpg',
                'tests/ComponentInstaller/Test/Resources/img2.jpg',
                'tests/ComponentInstaller/Test/Resources/test.css',
                'tests/ComponentInstaller/Test/Resources/test.js',
                'tests/ComponentInstaller/Test/Resources/test2.css',
                'tests/ComponentInstaller/Test/Resources/test2.js',
            ),
            'tests/ComponentInstaller/Test/Resources/*',
        );

        $tests[] = array(
            array(
                'tests/ComponentInstaller/Test/Resources/test.css',
                'tests/ComponentInstaller/Test/Resources/test2.css',
            ),
            'tests/ComponentInstaller/Test/Resources/*.css',
        );

        $tests[] = array(
            array(
                'tests/ComponentInstaller/Test/Resources/img.jpg',
                'tests/ComponentInstaller/Test/Resources/img2.jpg',
            ),
            'tests/ComponentInstaller/Test/Resources/*.jpg',
        );

        $tests[] = array(
            array(
                'tests/ComponentInstaller/Test/Resources/img.jpg',
                'tests/ComponentInstaller/Test/Resources/img2.jpg',
            ),
            'tests/ComponentInstaller/Test/Resources/*img*',
        );

        $tests[] = array(
            array(
                'tests/ComponentInstaller/Test/Resources/img.jpg',
            ),
            'tests/ComponentInstaller/Test/Resources/img.jpg',
        );

        $tests[] = array(
            array(
                'tests/ComponentInstaller/Test/Resources/img.jpg',
                'tests/ComponentInstaller/Test/Resources/img2.jpg',
                'tests/ComponentInstaller/Test/Resources/subdir/img.jpg',
                'tests/ComponentInstaller/Test/Resources/subdir/img3.jpg',
                'tests/ComponentInstaller/Test/Resources/subdir/subdir2/img4.jpg',
            ),
            'tests/ComponentInstaller/Test/Resources/**img*.jpg',
        );

        return $tests;
    }

    /**
     * Tests the recursiveGlob function.
     *
     * @dataProvider providerRecursiveGlob
     */
    public function testRecursiveGlob($expected, $pattern, $flags = 0)
    {
        $fs = new Filesystem();
        $result = $fs->recursiveGlob($pattern, $flags);

        // Sort the arrays so that the indexes don't matter.
        sort($expected);
        sort($result);
        $this->assertEquals($expected, $result);
    }

    public function providerRecursiveGlob()
    {
        $tests[] = array(
            array(
                'tests/ComponentInstaller/Test/Resources/img.jpg',
                'tests/ComponentInstaller/Test/Resources/img2.jpg',
                'tests/ComponentInstaller/Test/Resources/test.css',
                'tests/ComponentInstaller/Test/Resources/test.js',
                'tests/ComponentInstaller/Test/Resources/test2.css',
                'tests/ComponentInstaller/Test/Resources/test2.js',
                'tests/ComponentInstaller/Test/Resources/subdir',
            ),
            'tests/ComponentInstaller/Test/Resources/*',
        );

        $tests[] = array(
            array(
                'tests/ComponentInstaller/Test/Resources/test.css',
                'tests/ComponentInstaller/Test/Resources/test2.css',
            ),
            'tests/ComponentInstaller/Test/Resources/*.css',
        );

        $tests[] = array(
            array(
                'tests/ComponentInstaller/Test/Resources/img.jpg',
                'tests/ComponentInstaller/Test/Resources/img2.jpg',
            ),
            'tests/ComponentInstaller/Test/Resources/*.jpg',
        );

        $tests[] = array(
            array(
                'tests/ComponentInstaller/Test/Resources/img.jpg',
            ),
            'tests/ComponentInstaller/Test/Resources/img.jpg',
        );

        $tests[] = array(
            array(
                'tests/ComponentInstaller/Test/Resources/subdir/img.jpg',
                'tests/ComponentInstaller/Test/Resources/subdir/img3.jpg',
                'tests/ComponentInstaller/Test/Resources/subdir/subdir2/img4.jpg',
                'tests/ComponentInstaller/Test/Resources/subdir/subdir2',
            ),
            'tests/ComponentInstaller/Test/Resources/subdir/**',
        );

        return $tests;
    }
}
