<?php




use PHPUnit\Framework\Attributes\Test;
class PackageManagerTest extends \MicroweberPackages\Core\tests\TestCase
{
    public $skip = false;

    public function __construct(string $name)
    {
        parent::__construct( $name);
        if (!$this->isOnline()) {
            $this->skip = true;
        }
    }

    #[Test]

    public function it_search_package(): void {
        if ($this->skip) {
            $this->markTestSkipped('Skipping package manager test for this server configuration!');
        }

        $params = [];
        $params['require_name'] = 'microweber-templates/big2';

        $runner = new \MicroweberPackages\Package\MicroweberComposerClient();

        $results = $runner->search($params);

        $this->assertNotEmpty($results);
    }

    #[Test]

    public function it_install_package(): void {
        if ($this->skip) {
            $this->markTestSkipped('Skipping package manager test for this server configuration!');
        }

        $require_name = "microweber-templates/big2";
        $params['require_name'] = $require_name;

        $runner = new \MicroweberPackages\Package\MicroweberComposerClient();

        $results = $runner->requestInstall($params);
        $this->assertNotEmpty($results);
        $okResp=false;
        if (isset($results['error']) and $results['error'] == 'You need license key to install this package') {
            $okResp = true;
        }
        if (isset($results['error']) and $results['error'] == 'Please confirm installation') {
            $okResp = true;
            $this->assertNotEmpty($results["form_data_module_params"]["confirm_key"]);

        }

        $this->assertTrue($okResp);
        $this->assertEquals($results["form_data_module_params"]["require_name"], $require_name);

    }

    private function isOnline()
    {

        $ch = curl_init('https://modules.microweberapi.com/packages.json');
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
