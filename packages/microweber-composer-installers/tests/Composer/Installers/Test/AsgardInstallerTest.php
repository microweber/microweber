<?php

namespace Composer\Installers\Test;

use Composer\Installers\AsgardInstaller;
use Composer\Package\Package;
use Composer\Composer;

class AsgardInstallerTest extends TestCase
{
    /**
     * @var AsgardInstaller
     */
    private $installer;

    public function setUp(): void
    {
        $this->installer = new AsgardInstaller(
            new Package('NyanCat', '4.2', '4.2'),
            $this->getComposer(),
            $this->getMockIO()
        );
    }

    /**
     * @dataProvider packageNameInflectionProvider
     */
    public function testInflectPackageVars(string $type, string $name, string $expected): void
    {
        $this->assertEquals(
            array('name' => $expected, 'type' => $type),
            $this->installer->inflectPackageVars(array('name' => $name, 'type' => $type))
        );
    }

    public function packageNameInflectionProvider(): array
    {
        return array(
            // Should keep module name StudlyCase
            array(
                'asgard-module',
                'user-profile',
                'UserProfile'
            ),
            array(
                'asgard-module',
                'asgard-module',
                'Asgard'
            ),
            array(
                'asgard-module',
                'blog',
                'Blog'
            ),
            // tests that exactly one '-module' is cut off
            array(
                'asgard-module',
                'some-module-module',
                'SomeModule',
            ),
            // tests that exactly one '-theme' is cut off
            array(
                'asgard-theme',
                'some-theme-theme',
                'SomeTheme',
            ),
            // tests that names without '-theme' suffix stay valid
            array(
                'asgard-theme',
                'someothertheme',
                'Someothertheme',
            ),
            // Should keep theme name StudlyCase
            array(
                'asgard-theme',
                'adminlte-advanced',
                'AdminlteAdvanced'
            ),
        );
    }
}
