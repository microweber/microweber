<?php

namespace Composer\Installers\Test;

use Composer\Installers\BitrixInstaller;
use Composer\Package\Package;
use Composer\Composer;
use Composer\Package\RootPackage;

/**
 * Tests for the BitrixInstaller Class
 *
 * @coversDefaultClass Composer\Installers\BitrixInstaller
 */
class BitrixInstallerTest extends TestCase
{
    /** @var BitrixInstaller */
    private $installer;

    /** @var Composer */
    private $composer;

    public function setUp(): void
    {
        $this->composer = new Composer();
        $this->composer->setPackage(new RootPackage('foo/bar', '1.0.0', '1.0.0'));
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
        $this->installer = new BitrixInstaller(
            new Package($vars['name'], '4.2', '4.2'),
            $this->composer,
            $this->getMockIO()
        );
        $actual = $this->installer->inflectPackageVars($vars);
        $this->assertEquals($actual, $expectedVars);
    }

    /**
     * Provides various parameters for packages and the expected result after inflection
     */
    public function expectedInflectionResultsProvider(): array
    {
        return array(
            //check bitrix-dir is correct
            array(
                array('name' => 'Nyan/Cat'),
                array('name' => 'Nyan/Cat', 'bitrix_dir' => 'bitrix')
            ),
            array(
                array('name' => 'Nyan/Cat', 'bitrix_dir' => 'bitrix'),
                array('name' => 'Nyan/Cat', 'bitrix_dir' => 'bitrix')
            ),
            array(
                array('name' => 'Nyan/Cat', 'bitrix_dir' => 'local'),
                array('name' => 'Nyan/Cat', 'bitrix_dir' => 'local')
            ),
        );
    }
}
