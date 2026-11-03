<?php

namespace Composer\Installers\Test;

use Composer\Composer;
use Composer\Installers\YawikInstaller;
use Composer\Package\Package;
use Composer\Package\PackageInterface;

/**
 * Class YawikInstallerTest
 *
 * @package Composer\Installers\Test
 */
class YawikInstallerTest extends TestCase
{
    /**
     * @var Composer
     */
    private $composer;

    /**
     * @var Package
     */
    private $package;

    /**
     * setUp
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->package = new Package('YawikCompanyRegistration', '1.0', '1.0');
        $this->composer = new Composer();
    }

    /**
     * @dataProvider packageNameProvider
     */
    public function testInflectPackageVars(string $input): void
    {
        $installer = new YawikInstaller($this->package, $this->composer, $this->getMockIO());
        $result = $installer->inflectPackageVars(array('name' => $input));
        $this->assertEquals($result, array('name' => 'YawikCompanyRegistration'));
    }

    public function packageNameProvider(): array
    {
        return array(
            array('yawik-company-registration'),
            array('yawik_company_registration'),
            array('YawikCompanyRegistration')
        );
    }
}
