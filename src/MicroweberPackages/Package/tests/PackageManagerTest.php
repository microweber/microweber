<?php


class PackageManagerTest extends \MicroweberPackages\Core\tests\TestCase
{
    public $repos = [
        ["type" => "composer", "url" => "https://packages-phpunit.microweberapi.com/"]
    ];
    public $skip = false;

    public function __construct()
    {
        parent::__construct();
        if (!$this->isOnline()) {
            $this->skip = true;
        }
    }

    public function testSearchPackage()
    {
        if ($this->skip) {
            $this->markTestSkipped('Skipping package manager test for this server configuration!');
        }
        //http://packages-phpunit.microweberapi.com/packages.json
        $params= [];
        $params['keyword'] = 'dream';

        $runner = new \MicroweberPackages\Package\ComposerUpdate($this->_getComposerPath());

        $runner->setRepos($this->repos);
        $runner->setTargetPath($this->_getTargetPath());
        $runner->setComposerHome(dirname(__DIR__) . '/cache');

        $results = $runner->searchPackages($params);

        $this->assertNotEmpty($results);
    }

    public function testInstallPackage()
    {
        if ($this->skip) {
            $this->markTestSkipped('Skipping package manager test for this server configuration!');
        }

        //   $require_name = 'microweber-modules/multilanguage';
       // $require_name = "microweber-modules/test-module-with-deps";
        $require_name = "microweber-templates/dream";
        $params['require_name'] = $require_name;

        $runner = new \MicroweberPackages\Package\ComposerUpdate($this->_getComposerPath());


        $runner->setRepos($this->repos);
        $runner->setTargetPath($this->_getTargetPath());
        $runner->setComposerHome(dirname(__DIR__) . '/cache');

        $results = $runner->installPackageByName($params);


        $this->assertNotEmpty($results);
        $this->assertEquals($results["error"], "Please confirm installation");
        $this->assertEquals($results["form_data_module_params"]["require_name"], $require_name);
        $this->assertNotEmpty($results["form_data_module_params"]["confirm_key"]);


//        $params['confirm_key'] = $results["form_data_module_params"]["confirm_key"];
//        $results = $runner->installPackageByName($params);
//
//        $this->assertNotEmpty($results["success"]);
//        $this->assertNotEmpty($results["log"]);
//
//        var_dump($results);

    }

    private function _getComposerPath()
    {
        return dirname(__DIR__) . '/';
    }

    private function _getTargetPath()
    {
        return dirname(__DIR__);
    }

    private function isOnline()
    {

        $ch = curl_init('https://packages-phpunit.microweberapi.com/packages.json');
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode == 200 and @json_decode($data)) {
            return true;
        }


    }
}