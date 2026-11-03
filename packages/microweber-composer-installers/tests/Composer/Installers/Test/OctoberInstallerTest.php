<?php

namespace Composer\Installers\Test;

use Composer\Installers\OctoberInstaller;
use Composer\Package\Package;
use Composer\Composer;

class OctoberInstallerTest extends TestCase
{
    /**
     * @var OctoberInstaller
     */
    private $installer;

    public function setUp(): void
    {
        $this->installer = new OctoberInstaller(
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
                'october-plugin',
                'acme',
                'subpagelist',
                'acme',
                'subpagelist',
            ),
            array(
                'october-plugin',
                'acme',
                'subpagelist-plugin',
                'acme',
                'subpagelist',
            ),
            array(
                'october-plugin',
                'acme',
                'semanticoctober',
                'acme',
                'semanticoctober',
            ),
            // tests vendor name containing a hyphen
            array(
                'october-plugin',
                'foo-bar-co',
                'blog',
                'foobarco',
                'blog'
            ),
            // tests that exactly one '-theme' is cut off
            array(
                'october-theme',
                'acme',
                'some-theme-theme',
                'acme',
                'some-theme',
            ),
            // tests that names without '-theme' suffix stay valid
            array(
                'october-theme',
                'acme',
                'someothertheme',
                'acme',
                'someothertheme',
            ),
        );
    }
}
