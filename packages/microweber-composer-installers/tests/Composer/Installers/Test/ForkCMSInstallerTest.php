<?php

namespace Composer\Installers\Test;

use Composer\Installers\ForkCMSInstaller;
use Composer\Package\Package;

class ForkCMSInstallerTest extends TestCase
{
    /**
     * @var ForkCMSInstaller
     */
    private $installer;

    public function setUp(): void
    {
        $this->installer = new ForkCMSInstaller(
            new Package('Knife', '4.2', '4.2'),
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
            $this->installer->inflectPackageVars([
                'vendor' => $vendor,
                'name' => $name,
                'type' => $type
            ]),
            ['vendor' => $expectedVendor, 'name' => $expectedName, 'type' => $type]
        );
    }

    public function packageNameInflectionProvider(): array
    {
        return [
            // module with lowercase name
            [
                'fork-cms-module',
                'pageon',
                'knife',
                'pageon',
                'Knife',
            ],
            // theme with lowercase name
            [
                'fork-cms-theme',
                'pageon',
                'knife',
                'pageon',
                'Knife',
            ],
            // module with lowercase name and module affix
            [
                'fork-cms-module',
                'pageon',
                'knife-module',
                'pageon',
                'Knife',
            ],
            // theme with lowercase name and theme affix
            [
                'fork-cms-theme',
                'pageon',
                'knife-theme',
                'pageon',
                'Knife',
            ],
            // module with lowercase name and module affix and fork-cms prefix
            [
                'fork-cms-module',
                'pageon',
                'fork-cms-knife-module',
                'pageon',
                'Knife',
            ],
            // theme with lowercase name and theme affix and fork-cms prefix
            [
                'fork-cms-theme',
                'pageon',
                'fork-cms-knife-theme',
                'pageon',
                'Knife',
            ],
            // module with lowercase name and fork-cms prefix
            [
                'fork-cms-module',
                'pageon',
                'fork-cms-knife',
                'pageon',
                'Knife',
            ],
            // theme with lowercase name and fork-cms prefix
            [
                'fork-cms-theme',
                'pageon',
                'fork-cms-knife',
                'pageon',
                'Knife',
            ],
            // module with hyphenated name
            [
                'fork-cms-module',
                'pageon',
                'knife-and-spoon',
                'pageon',
                'KnifeAndSpoon',
            ],
            // theme with hyphenated name
            [
                'fork-cms-theme',
                'pageon',
                'knife-and-spoon',
                'pageon',
                'KnifeAndSpoon',
            ],
            // module with hyphenated name and module affix
            [
                'fork-cms-module',
                'pageon',
                'knife-and-spoon-module',
                'pageon',
                'KnifeAndSpoon',
            ],
            // theme with hyphenated name and theme affix
            [
                'fork-cms-theme',
                'pageon',
                'knife-and-spoon-theme',
                'pageon',
                'KnifeAndSpoon',
            ],
            // module with hyphenated name and module affix and fork-cms prefix
            [
                'fork-cms-module',
                'pageon',
                'fork-cms-knife-and-spoon-module',
                'pageon',
                'KnifeAndSpoon',
            ],
            // theme with hyphenated name and theme affix and fork-cms prefix
            [
                'fork-cms-theme',
                'pageon',
                'fork-cms-knife-and-spoon-theme',
                'pageon',
                'KnifeAndSpoon',
            ],
            // module with hyphenated name and fork-cms prefix
            [
                'fork-cms-module',
                'pageon',
                'fork-cms-knife-and-spoon',
                'pageon',
                'KnifeAndSpoon',
            ],
            // theme with hyphenated name and fork-cms prefix
            [
                'fork-cms-theme',
                'pageon',
                'fork-cms-knife-and-spoon',
                'pageon',
                'KnifeAndSpoon',
            ],
            // module with underscored name
            [
                'fork-cms-module',
                'pageon',
                'knife_and_spoon',
                'pageon',
                'KnifeAndSpoon',
            ],
            // theme with underscored name
            [
                'fork-cms-theme',
                'pageon',
                'knife_and_spoon',
                'pageon',
                'KnifeAndSpoon',
            ],
            // module with underscored name and module affix
            [
                'fork-cms-module',
                'pageon',
                'knife_and_spoon-module',
                'pageon',
                'KnifeAndSpoon',
            ],
            // theme with underscored name and theme affix
            [
                'fork-cms-theme',
                'pageon',
                'knife_and_spoon-theme',
                'pageon',
                'KnifeAndSpoon',
            ],
            // module with underscored name and module affix and fork-cms prefix
            [
                'fork-cms-module',
                'pageon',
                'fork-cms-knife_and_spoon-module',
                'pageon',
                'KnifeAndSpoon',
            ],
            // theme with underscored name and theme affix and fork-cms prefix
            [
                'fork-cms-theme',
                'pageon',
                'fork-cms-knife_and_spoon-theme',
                'pageon',
                'KnifeAndSpoon',
            ],
            // module with underscored name and fork-cms prefix
            [
                'fork-cms-module',
                'pageon',
                'fork-cms-knife_and_spoon',
                'pageon',
                'KnifeAndSpoon',
            ],
            // theme with underscored name and fork-cms prefix
            [
                'fork-cms-theme',
                'pageon',
                'fork-cms-knife_and_spoon',
                'pageon',
                'KnifeAndSpoon',
            ],
            // module with camelcased name
            [
                'fork-cms-module',
                'pageon',
                'knifeAndSpoon',
                'pageon',
                'KnifeAndSpoon',
            ],
            // theme with camelcased name
            [
                'fork-cms-theme',
                'pageon',
                'knifeAndSpoon',
                'pageon',
                'KnifeAndSpoon',
            ],
            // module with camelcased name and module affix
            [
                'fork-cms-module',
                'pageon',
                'knifeAndSpoonModule',
                'pageon',
                'KnifeAndSpoon',
            ],
            // theme with camelcased name and theme affix
            [
                'fork-cms-theme',
                'pageon',
                'knifeAndSpoonTheme',
                'pageon',
                'KnifeAndSpoon',
            ],
            // module with camelcased name and module affix and fork-cms prefix
            [
                'fork-cms-module',
                'pageon',
                'ForkCmsKnifeAndSpoonModule',
                'pageon',
                'KnifeAndSpoon',
            ],
            // theme with camelcased name and theme affix and fork-cms prefix
            [
                'fork-cms-theme',
                'pageon',
                'ForkCmsKnifeAndSpoonTheme',
                'pageon',
                'KnifeAndSpoon',
            ],
            // module with camelcased name and fork-cms prefix
            [
                'fork-cms-module',
                'pageon',
                'ForkCmsKnifeAndSpoon',
                'pageon',
                'KnifeAndSpoon',
            ],
            // theme with camelcased name and fork-cms prefix
            [
                'fork-cms-theme',
                'pageon',
                'ForkCmsKnifeAndSpoon',
                'pageon',
                'KnifeAndSpoon',
            ],
        ];
    }
}
