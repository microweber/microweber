<?php


class PackageManagerTest extends \MicroweberPackages\Core\tests\TestCase
{

    public function testSearchPackage()
    {

       /* $params['keyword'] = 'dream';

        $runner = new \MicroweberPackages\Package\ComposerUpdate($this->_getComposerPath());
        $runner->setTargetPath($this->_getTargetPath());
        $runner->setComposerHome(dirname(__DIR__) . '/cache');

        $results = $runner->searchPackages($params);

        $this->assertNotEmpty($results);*/
    }

    public function testInstallPackage()
    {
//        $params['require_name'] = 'microweber-modules/multilanguage';
//
//        $runner = new \MicroweberPackages\Package\ComposerUpdate($this->_getComposerPath());
//        $runner->setTargetPath($this->_getTargetPath());
//        $runner->setComposerHome(dirname(__DIR__) . '/cache');
//
//        $results = $runner->installPackageByName($params);
//
//        $this->assertNotEmpty($results);
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