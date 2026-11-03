<?php

namespace Composer\Installers\Test;

use Composer\Installers\CiviCrmInstaller;
use Composer\Package\Package;
use Composer\Composer;

class CiviCrmInstallerTest extends TestCase
{
    /**
     * @var CiviCrmInstaller
     */
    private $installer;

    public function setUp(): void
    {
        $this->installer = new CiviCrmInstaller(
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
            array(
                'civicrm-ext',
                'org.civicrm.shoreditch',
                'org.civicrm.shoreditch'
            ),
            array(
                'civicrm-ext',
                'org.civicrm.flexmailer',
                'org.civicrm.flexmailer'
            ),
            array(
                'civicrm-ext',
                'uk.co.vedaconsulting.mosaico',
                'uk.co.vedaconsulting.mosaico'
            )
        );
    }
}
