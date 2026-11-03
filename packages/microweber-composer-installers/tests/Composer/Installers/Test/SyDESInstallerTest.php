<?php

namespace Composer\Installers\Test;

use Composer\Installers\SyDESInstaller;
use Composer\Package\Package;
use Composer\Composer;

class SyDESInstallerTest extends TestCase
{
    /**
     * @var SyDESInstaller
     */
    private $installer;

    public function setUp(): void
    {
        $this->installer = new SyDESInstaller(
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
            // modules
            array(
                'sydes-module',
                'name',
                'Name'
            ),
            array(
                'sydes-module',
                'sample-name',
                'SampleName'
            ),
            array(
                'sydes-module',
                'sydes-name',
                'Name'
            ),
            array(
                'sydes-module',
                'sample-name-module',
                'SampleName',
            ),
            array(
                'sydes-module',
                'sydes-sample-name-module',
                'SampleName'
            ),
            // themes
            array(
                'sydes-theme',
                'some-theme-theme',
                'some-theme',
            ),
            array(
                'sydes-theme',
                'sydes-sometheme',
                'sometheme',
            ),
            array(
                'sydes-theme',
                'Sample-Name',
                'sample-name'
            ),
        );
    }
}
