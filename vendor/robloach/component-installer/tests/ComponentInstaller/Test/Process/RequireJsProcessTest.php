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
use ComponentInstaller\Process\RequireJsProcess;
use Composer\Config;

/**
 * Tests RequireJsProcess.
 */
class RequireJsProcessTest extends ProcessTest
{
    protected $process;

    public function setUp()
    {
        parent::setUp();
        $this->process = new RequireJsProcess($this->composer, $this->io);
    }

    /**
     * testRequireJs
     *
     * @dataProvider providerRequireJs
     */
    public function testRequireJs(array $json = array(), $expected = '')
    {
        $result = $this->process->requireJs($json);
        $this->assertEquals($expected, $result);
    }

    public function providerRequireJs()
    {
        // Start with a base RequireJS configuration.
        $js = <<<EOT
var components = %s;
if (typeof require !== "undefined" && require.config) {
    require.config(components);
} else {
    var require = components;
}
if (typeof exports !== "undefined" && typeof module !== "undefined") {
    module.exports = components;
}
EOT;

        // Tests a basic configuration.
        $tests[] = array(
            array('foo' => 'bar'),
            sprintf($js, "{\n    \"foo\": \"bar\"\n}"),
        );

        return $tests;
    }

    /**
     * testRequireJson
     *
     * @dataProvider providerRequireJson
     */
    public function testRequireJson(array $packages, array $config, $expected = null)
    {
        $this->composer->getConfig()->merge(array('config' => $config));
        $this->process->init();
        $result = $this->process->requireJson($packages);
        $this->assertEquals($expected, $result);
    }

    public function providerRequireJson()
    {
        // Test a package that doesn't have any extra information.
        $packageWithoutExtra = array(
            'name' => 'components/packagewithoutextra',
            'type' => 'component',
        );
        $packages = array($packageWithoutExtra);
        $expected = array(
            'baseUrl' => 'components',
        );
        $tests[] = array($packages, array(), $expected);

        // Test switching the component directory.
        $packages = array($packageWithoutExtra);
        $expected = array(
            'baseUrl' => 'components',
        );
        $tests[] = array($packages, array('component-dir' => 'otherdir'), $expected);

        // Test switching the baseUrl.
        $packages = array($packageWithoutExtra);
        $expected = array(
            'baseUrl' => '/another/path',
        );
        $tests[] = array($packages, array('component-baseurl' => '/another/path'), $expected);

        // Test a package with just Scripts.
        $jquery = array(
            'name' => 'components/jquery',
            'type' => 'component',
            'extra' => array(
                'component' => array(
                    'scripts' => array(
                        'jquery.js',
                    )
                )
            )
        );
        $packages = array($jquery);
        $expected = array(
            'baseUrl' => 'components',
        );
        $tests[] = array($packages, array(), $expected);

        // Test a pckage with just shim information.
        $underscore = array(
            'name' => 'components/underscore',
            'type' => 'component',
            'extra' => array(
                'component' => array(
                    'shim' => array(
                        'exports' => '_',
                    ),
                ),
            ),
            'baseUrl' => 'components',
        );
        $packages = array($underscore);
        $expected = array(
            'shim' => array(
                'underscore' => array(
                    'exports' => '_',
                ),
            ),
            'baseUrl' => 'components',
        );
        $tests[] = array($packages, array(), $expected);

        // Test a package the has everything.
        $backbone = array(
            'name' => 'components/backbone',
            'type' => 'component',
            'extra' => array(
                'component' => array(
                    'scripts' => array(
                        'backbone.js'
                    ),
                    'shim' => array(
                        'exports' => 'Backbone',
                        'deps' => array(
                            'underscore',
                            'jquery'
                        ),
                    ),
                ),
            ),
        );
        $packages = array($backbone);
        $expected = array(
            'shim' => array(
                'backbone' => array(
                    'exports' => 'Backbone',
                    'deps' => array(
                        'underscore',
                        'jquery'
                    ),
                ),
            ),
            'baseUrl' => 'components',
        );
        $tests[] = array($packages, array(), $expected);

        // Test multiple packages.
        $packages = array($backbone, $jquery);
        $expected = array(
            'shim' => array(
                'backbone' => array(
                    'exports' => 'Backbone',
                    'deps' => array(
                        'underscore',
                        'jquery'
                    ),
                ),
            ),
            'baseUrl' => 'components',
        );
        $tests[] = array($packages, array(), $expected);

        // Package with a config definition.
        $packageWithConfig = array(
            'name' => 'components/packagewithconfig',
            'type' => 'component',
            'extra' => array(
                'component' => array(
                    'config' => array(
                        'color' => 'blue',
                    ),
                ),
            ),
        );
        $packages = array($packageWithConfig);
        $expected = array(
            'config' => array(
                'packagewithconfig' => array(
                    'color' => 'blue',
                ),
            ),
            'baseUrl' => 'components',
        );
        $tests[] = array($packages, array(), $expected);

        // Test building the JavaScript file.
        $packageWithScripts = array(
            'name' => 'components/foobar',
            'type' => 'component',
            'extra' => array(
                'component' => array(
                    'scripts' => array(
                        'tests/ComponentInstaller/Test/Resources/test.js',
                    ),
                ),
            ),
            'is-root' => true,
        );
        $packages = array($packageWithScripts);
        $expected = array(
            'packages' => array(
                array(
                    'name' => 'foobar',
                    'main' => 'foobar-built.js'
                ),
            ),
            'baseUrl' => 'components',
        );
        $tests[] = array($packages, array(), $expected);

        // Test building JavaScript files with glob().
        $packageWithScripts = array(
            'name' => 'components/foobar2',
            'extra' => array(
                'component' => array(
                    'scripts' => array(
                        'tests/ComponentInstaller/Test/Resources/*.js',
                    ),
                ),
            ),
            'is-root' => true,
        );
        $packages = array($packageWithScripts);
        $expected = array(
            'packages' => array(
                array(
                    'name' => 'foobar2',
                    'main' => 'foobar2-built.js'
                ),
            ),
            'baseUrl' => 'components',
        );
        $tests[] = array($packages, array(), $expected);

        // Test bringing in config options from the root package.
        $package = array(
            'name' => 'components/foobar3',
            'extra' => array(
                'component' => array(
                    'config' => array(
                       'color' => 'blue',
                    ),
                ),
            ),
        );
        $packages = array($package);
        $config = array(
            'component' => array(
                'waitSeconds' => 3,
                'baseUrl' => 'public',
            ),
        );
        $expected = array(
            'config' => array(
                'foobar3' => array(
                    'color' => 'blue',
                ),
            ),
            'baseUrl' => 'public',
            'waitSeconds' => 3,
        );
        $tests[] = array($packages, $config, $expected);

        // Test overriding component config from root packages.
        $package = array(
            'name' => 'components/foobar3',
            'extra' => array(
                'component' => array(
                    'config' => array(
                       'color' => 'blue',
                    ),
                ),
            ),
        );
        $packages = array($package);
        $config = array(
            'component' => array(
                'waitSeconds' => 3,
                'baseUrl' => 'public',
                'config' => array(
                    'foobar3' => array(
                        'color' => 'red',
                    ),
                ),
            ),
        );
        $expected = array(
            'config' => array(
                'foobar3' => array(
                    'color' => 'red',
                ),
            ),
            'baseUrl' => 'public',
            'waitSeconds' => 3,
        );
        $tests[] = array($packages, $config, $expected);

        return $tests;
    }

}
