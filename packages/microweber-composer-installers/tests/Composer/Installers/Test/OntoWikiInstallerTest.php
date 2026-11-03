<?php

namespace Composer\Installers\Test;

use Composer\Composer;
use Composer\Installers\OntoWikiInstaller;
use Composer\Package\Package;

/**
 * Test for the OntoWikiInstaller
 * code was taken from DokuWikiInstaller
 */
class OntoWikiInstallerTest extends TestCase
{
    /**
     * @var OntoWikiInstaller
     */
    private $installer;

    public function setUp(): void
    {
        $package = new Package('ontowiki/some_name', '1.0.9', '1.0');
        $this->installer = new OntoWikiInstaller(
            $package,
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
            $this->installer->inflectPackageVars(array('name' => $name, 'type'=>$type)),
            array('name' => $expected, 'type'=>$type)
        );
    }

    public function packageNameInflectionProvider(): array
    {
        return array(
            array(
                'ontowiki-extension',
                'CSVImport.ontowiki',
                'csvimport',
            ),
            array(
                'ontowiki-extension',
                'csvimport',
                'csvimport',
            ),
            array(
                'ontowiki-extension',
                'some_ontowiki_extension',
                'some_ontowiki_extension',
            ),
            array(
                'ontowiki-extension',
                'some_ontowiki_extension.ontowiki',
                'some_ontowiki_extension',
            ),
            array(
                'ontowiki-translation',
                'de-translation.ontowiki',
                'de',
            ),
            array(
                'ontowiki-translation',
                'en-US-translation.ontowiki',
                'en-us',
            ),
            array(
                'ontowiki-translation',
                'en-US-translation',
                'en-us',
            ),
            array(
                'ontowiki-theme',
                'blue-theme.ontowiki',
                'blue',
            ),
            array(
                'ontowiki-theme',
                'blue-theme',
                'blue',
            ),
        );
    }
}
