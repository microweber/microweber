<?php

use PHPUnit\Framework\TestCase;

class PackageManagerTest extends TestCase
{

    public function testSearchPackage()
    {

        $params['keyword'] = 'dream';

        $runner = new \MicroweberPackages\PackageManager\ComposerUpdate($this->_getComposerPath());
        $runner->setTargetPath($this->_getTargetPath());
        $runner->setComposerHome(dirname(__DIR__) . '/cache');

        $results = $runner->searchPackages($params);

        $this->assertNotEmpty($results);
    }

    public function testInstallPackage()
    {

        $params['require_name'] = 'microweber-templates/cashy';

        $runner = new \MicroweberPackages\PackageManager\ComposerUpdate($this->_getComposerPath());
        $runner->setTargetPath($this->_getTargetPath());
        $runner->setComposerHome(dirname(__DIR__) . '/cache');

        $results = $runner->installPackageByName($params);

        $this->assertNotEmpty($results);
    }

    private function _getComposerPath()
    {
        return dirname(__DIR__) . '/';
    }

    private function _getTargetPath()
    {
        return dirname(__DIR__);
    }
}