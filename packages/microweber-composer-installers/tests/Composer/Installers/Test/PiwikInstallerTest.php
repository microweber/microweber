<?php

namespace Composer\Installers\Test;

use Composer\Composer;
use Composer\Installers\PiwikInstaller;
use Composer\Package\Package;
use Composer\Package\PackageInterface;

/**
 * Class PiwikInstallerTest
 *
 * @package Composer\Installers\Test
 */
class PiwikInstallerTest extends TestCase
{
    /**
     * @var Composer
     */
    private $composer;

    /**
     * @var Package
     */
    private $package;

    public function setUp(): void
    {
        $this->package = new Package('VisitSummary', '1.0', '1.0');
        $this->composer = new Composer();
    }

    public function testInflectPackageVars(): void
    {
        $installer = new PiwikInstaller($this->package, $this->composer, $this->getMockIO());
        $result = $installer->inflectPackageVars(array('name' => 'VisitSummary'));
        $this->assertEquals($result, array('name' => 'VisitSummary'));

        $installer = new PiwikInstaller($this->package, $this->composer, $this->getMockIO());
        $result = $installer->inflectPackageVars(array('name' => 'visit-summary'));
        $this->assertEquals($result, array('name' => 'VisitSummary'));

        $installer = new PiwikInstaller($this->package, $this->composer, $this->getMockIO());
        $result = $installer->inflectPackageVars(array('name' => 'visit_summary'));
        $this->assertEquals($result, array('name' => 'VisitSummary'));
    }
}
