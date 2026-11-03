<?php

namespace Composer\Installers\Test;

use Composer\Installers\TastyIgniterInstaller;
use Composer\Package\Package;

class TastyIgniterInstallerTest extends TestCase
{
    /**
     * @var TastyIgniterInstaller
     */
    private $installer;

    public function setUp(): void
    {
        $this->installer = new TastyIgniterInstaller(
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
                'type' => $type,
            )),
            array(
                'vendor' => $expectedVendor,
                'name' => $expectedName,
                'type' => $type
            )
        );
    }

    public function packageNameInflectionProvider(): array
    {
        return array(
            array(
                'tastyigniter-extension',
                'acme',
                'pages',
                'acme',
                'pages',
            ),
            array(
                'tastyigniter-extension',
                'acme',
                'ti-ext-pages',
                'acme',
                'pages',
            ),
            // tests vendor name containing a hyphen
            array(
                'tastyigniter-extension',
                'foo-bar-co',
                'blog',
                'foobarco',
                'blog',
            ),
            // tests that exactly one '-theme' is cut off
            array(
                'tastyigniter-theme',
                'acme',
                'ti-theme-theme',
                'acme',
                'theme',
            ),
            // tests that names without '-theme' suffix stay valid
            array(
                'tastyigniter-theme',
                'acme',
                'someothertheme',
                'acme',
                'someothertheme',
            ),
            array(
                'tastyigniter-module',
                'tastyigniter',
                'ti-module-system',
                'tastyigniter',
                'system',
            ),
        );
    }
}
