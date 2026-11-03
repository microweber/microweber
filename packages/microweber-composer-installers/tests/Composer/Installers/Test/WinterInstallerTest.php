<?php

namespace Composer\Installers\Test;

use Composer\Installers\WinterInstaller;
use Composer\Package\Package;
use Composer\Composer;

class WinterInstallerTest extends TestCase
{
    /**
     * @var WinterInstaller
     */
    private $installer;

    public function setUp(): void
    {
        $this->installer = new WinterInstaller(
            new Package('NyanCat', '4.2', '4.2'),
            $this->getComposer(),
            $this->getMockIO()
        );
    }

    /**
     * @dataProvider packageNameInflectionProvider
     */
    public function testInflectPackageVars(string $type, string $vendor, string $name, string $expectedVendor, string $expectedName): void
    {
        $this->assertEquals(
            $this->installer->inflectPackageVars(array(
                'vendor' => $vendor,
                'name' => $name,
                'type' => $type
            )),
            array('vendor' => $expectedVendor, 'name' => $expectedName, 'type' => $type)
        );
    }

    public function packageNameInflectionProvider(): array
    {
        return array(
            array(
                'winter-plugin',
                'acme',
                'subpagelist',
                'acme',
                'subpagelist',
            ),
            array(
                'winter-plugin',
                'acme',
                'subpagelist-plugin',
                'acme',
                'subpagelist',
            ),
            array(
                'winter-plugin',
                'acme',
                'semanticwinter',
                'acme',
                'semanticwinter',
            ),
            // tests vendor name containing a hyphen
            array(
                'winter-plugin',
                'foo-bar-co',
                'blog',
                'foobarco',
                'blog'
            ),
            // tests that exactly one '-theme' is cut off
            array(
                'winter-theme',
                'acme',
                'some-theme-theme',
                'acme',
                'some-theme',
            ),
            // tests that names without '-theme' suffix stay valid
            array(
                'winter-theme',
                'acme',
                'someothertheme',
                'acme',
                'someothertheme',
            ),
            // tests modules
            array(
                'winter-module',
                'winter',
                'wn-system-module',
                'winter',
                'system',
            ),
        );
    }
}
