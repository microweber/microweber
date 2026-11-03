<?php

namespace Composer\Installers\Test;

use Composer\Composer;
use Composer\Installers\GravInstaller;

class GravInstallerTest extends TestCase
{
    /** @var Composer */
    protected $composer;

    public function setUp(): void
    {
        $this->composer = new Composer();
    }

    public function testInflectPackageVars(): void
    {
        $package     = $this->getPackage('vendor/name', '0.0.0');
        $installer   = new GravInstaller($package, $this->composer, $this->getMockIO());
        $packageVars = $this->getPackageVars($package);

        $result = $installer->inflectPackageVars(array_merge($packageVars, array('name' => 'test')));
        $this->assertEquals('test', $result['name']);

        foreach ($installer->getLocations('grav') as $name => $location) {
            $result = $installer->inflectPackageVars(array_merge($packageVars, array('name' => "$name-test")));
            $this->assertEquals('test', $result['name']);
            $result = $installer->inflectPackageVars(array_merge($packageVars, array('name' => "test-$name")));
            $this->assertEquals('test', $result['name']);
            $result = $installer->inflectPackageVars(array_merge($packageVars, array('name' => "$name-test-test")));
            $this->assertEquals('test-test', $result['name']);
            $result = $installer->inflectPackageVars(array_merge($packageVars, array('name' => "test-test-$name")));
            $this->assertEquals('test-test', $result['name']);
            $result = $installer->inflectPackageVars(array_merge($packageVars, array('name' => "grav-$name-test")));
            $this->assertEquals('test', $result['name']);
            $result = $installer->inflectPackageVars(array_merge($packageVars, array('name' => "grav-test-$name")));
            $this->assertEquals('test', $result['name']);
            $result = $installer->inflectPackageVars(array_merge($packageVars, array('name' => "grav-$name-test-test")));
            $this->assertEquals('test-test', $result['name']);
            $result = $installer->inflectPackageVars(array_merge($packageVars, array('name' => "grav-test-test-$name")));
            $this->assertEquals('test-test', $result['name']);
        }
    }

    /**
     * @param \Composer\Package\PackageInterface $package
     * @return string[]
     */
    public function getPackageVars($package): array
    {
        $type = $package->getType();

        $prettyName = $package->getPrettyName();
        if (strpos($prettyName, '/') !== false) {
            list($vendor, $name) = explode('/', $prettyName);
        } else {
            $vendor = '';
            $name   = $prettyName;
        }

        return compact('name', 'vendor', 'type');
    }
}
