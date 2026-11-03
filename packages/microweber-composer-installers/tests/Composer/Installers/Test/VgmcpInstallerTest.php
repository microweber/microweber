<?php

namespace Composer\Installers\Test;

use Composer\Installers\VgmcpInstaller;
use Composer\Package\Package;
use Composer\Composer;

class VgmcpInstallerTest extends TestCase
{
    /**
     * @var VgmcpInstaller
     */
    private $installer;

    public function setUp(): void
    {
        $this->installer = new VgmcpInstaller(
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
            // Should keep bundle name StudlyCase
            array(
                'vgmcp-bundle',
                'user-profile',
                'UserProfile'
            ),
            array(
                'vgmcp-bundle',
                'vgmcp-bundle',
                'Vgmcp'
            ),
            array(
                'vgmcp-bundle',
                'blog',
                'Blog'
            ),
            // tests that exactly one '-bundle' is cut off
            array(
                'vgmcp-bundle',
                'some-bundle-bundle',
                'SomeBundle',
            ),
            // tests that exactly one '-theme' is cut off
            array(
                'vgmcp-theme',
                'some-theme-theme',
                'SomeTheme',
            ),
            // tests that names without '-theme' suffix stay valid
            array(
                'vgmcp-theme',
                'someothertheme',
                'Someothertheme',
            ),
            // Should keep theme name StudlyCase
            array(
                'vgmcp-theme',
                'adminlte-advanced',
                'AdminlteAdvanced'
            ),
        );
    }
}
