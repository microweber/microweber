<?php

namespace Composer\Installers\Test;

use Composer\Installers\ProcessWireInstaller;
use Composer\Package\Package;
use Composer\Composer;

class ProcessWireInstallerTest extends TestCase
{
    /** @var Composer */
    private $composer;
    /** @var Package */
    private $package;

    public function setUp(): void
    {
        $this->package = new Package('CamelCased', '1.0', '1.0');
        $this->composer = new Composer();
    }

    public function testInflectPackageVars(): void
    {
        $installer = new ProcessWireInstaller($this->package, $this->composer, $this->getMockIO());
        $result = $installer->inflectPackageVars(array('name' => 'CamelCased'));
        $this->assertEquals($result, array('name' => 'CamelCased'));

        $installer = new ProcessWireInstaller($this->package, $this->composer, $this->getMockIO());
        $result = $installer->inflectPackageVars(array('name' => 'with-dash'));
        $this->assertEquals($result, array('name' => 'WithDash'));

        $installer = new ProcessWireInstaller($this->package, $this->composer, $this->getMockIO());
        $result = $installer->inflectPackageVars(array('name' => 'with_underscore'));
        $this->assertEquals($result, array('name' => 'WithUnderscore'));
    }
}
