<?php

namespace Composer\Installers\Test;

use Composer\Installers\MauticInstaller;
use Composer\Package\Package;
use Composer\Composer;

class MauticInstallerTest extends TestCase
{
    /**
     * @var Composer
     */
    protected $composer;

    public function setUp(): void
    {
        $this->composer = new Composer();
    }

    /**
     * @param string[] $vars
     * @param string[] $expectedVars
     *
     * @covers ::inflectPackageVars
     *
     * @dataProvider expectedInflectionResultsProvider
     */
    public function testInflectPackageVars(array $vars, array $expectedVars): void
    {
        $package = new Package($vars['name'], '1.0.0', '1.0.0');
        $package->setType($vars['type']);
        if (isset($vars['extra'])) {
            $package->setExtra((array) $vars['extra']);
        }

        $installer = new MauticInstaller(
            $package,
            $this->composer,
            $this->getMockIO()
        );

        $actual = $installer->inflectPackageVars($vars);
        $this->assertEquals($actual, $expectedVars);
    }

    /**
     * Provides various parameters for packages and the expected result after
     * inflection
     *
     * @return array
     */
    public function expectedInflectionResultsProvider(): array
    {
        return array(
            //check bitrix-dir is correct
            array(
                array(
                    'name' => 'mautic/grapes-js-builder-bundle',
                    'type' => 'mautic-plugin'
                ),
                array(
                    'name' => 'GrapesJsBuilderBundle',
                    'type' => 'mautic-plugin'
                )
            ),
            // Check if composer renames the name based on the given
            // installation directory
            array(
                array(
                    'name' => 'mautic/grapes-js-builder-bundle',
                    'type' => 'mautic-plugin',
                    'extra' => array(
                        'install-directory-name' => 'GrapesJsBuilderPlugin'
                    )
                ),
                array(
                    'name' => 'GrapesJsBuilderPlugin',
                    'type' => 'mautic-plugin',
                    'extra' => array(
                        'install-directory-name' => 'GrapesJsBuilderPlugin'
                    )
                )
            ),
            array(
                array(
                    'name' => 'mautic/theme-blank-grapejs',
                    'type' => 'mautic-theme'
                ),
                array(
                    'name' => 'ThemeBlankGrapejs',
                    'type' => 'mautic-theme'
                )
            ),
            array(
                array(
                    'name' => 'mautic/theme-blank-grapejs',
                    'type' => 'mautic-theme',
                    'extra' => array(
                        'install-directory-name' => 'blank-grapejs'
                    )
                ),
                array(
                    'name' => 'blank-grapejs',
                    'type' => 'mautic-theme',
                    'extra' => array(
                        'install-directory-name' => 'blank-grapejs'
                    )
                )
            )
        );
    }
}
