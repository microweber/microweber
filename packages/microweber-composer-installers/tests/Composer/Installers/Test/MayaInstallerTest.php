<?php

namespace Composer\Installers\Test;

use Composer\Installers\MayaInstaller;
use Composer\Package\Package;
use Composer\Composer;

class MayaInstallerTest extends TestCase
{
    /**
     * @var MayaInstaller
     */
    private $installer;

    public function setUp(): void
    {
        $this->installer = new MayaInstaller(
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
                'maya-module',
                'user-profile',
                'UserProfile'
            ),
            array(
                'maya-module',
                'maya-module',
                'Maya'
            ),
            array(
                'maya-module',
                'blog',
                'Blog'
            ),
            // tests that exactly one '-module' is cut off
            array(
                'maya-module',
                'some-module-module',
                'SomeModule',
            ),
        );
    }
}
