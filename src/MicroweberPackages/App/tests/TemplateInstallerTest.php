<?php
namespace MicroweberPackages\App\tests;

use Illuminate\Support\Facades\Config;
use MicroweberPackages\Config\ConfigSave;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Install\TemplateInstaller;

class TemplateInstallerTest extends TestCase
{
    public function testInstall()
    {
        $logger = new MyCustomLogger();

        Config::set('microweber.install_default_template', 'new-world');
        Config::set('microweber.install_default_template_content', 1);

        $installer = new TemplateInstaller();
        $installer->logger = $logger;
        $status = $installer->run();

    }
}

class MyCustomLogger {

    public function setLogInfo()
    {

    }
    public function clearLog()
    {

    }
}
