<?php

declare(strict_types=1);

namespace MicroweberPackages\PackageManagerClient\Tests\Feature;

use MicroweberPackages\PackageManagerClient\PackageManagerClientService;
use MicroweberPackages\PackageManagerClient\Tests\Support\SatisServer;
use MicroweberPackages\PackageManagerClient\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Spins up a temp Satis-compatible packages.json server with sample
 * module / template / nwidart packages and asserts correct install dirs.
 */
class PackageInstallViaSatisTest extends TestCase
{
    private SatisServer $server;
    private string $workDir;
    private string $fixtures;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fixtures = dirname(__DIR__) . '/Fixtures/sample-packages';
        $this->workDir = sys_get_temp_dir() . '/mw-pmc-install-' . uniqid('', true);
        mkdir($this->workDir . '/Modules', 0777, true);
        mkdir($this->workDir . '/Templates', 0777, true);
        mkdir($this->workDir . '/storage/cache/composer-download', 0777, true);
        mkdir($this->workDir . '/storage/logs', 0777, true);

        $this->server = new SatisServer();
        $this->server->addPackageFromDirectory($this->fixtures . '/module-sample');
        $this->server->addPackageFromDirectory($this->fixtures . '/template-sample');
        $this->server->addPackageFromDirectory($this->fixtures . '/nwidart-module-sample');
        $this->server->start();
    }

    protected function tearDown(): void
    {
        $this->server->cleanup();
        $this->removeTree($this->workDir);
        parent::tearDown();
    }

    private function makeClient(): PackageManagerClient
    {
        return new PackageManagerClientService(
            packageServers: [$this->server->packagesJsonUrl()],
            config: [
                'base_path' => $this->workDir,
                'modules_path' => 'Modules',
                'templates_path' => 'Templates',
                'download_path' => $this->workDir . '/storage/cache/composer-download',
                'log_path' => $this->workDir . '/storage/logs/package-install.log',
                'verify_ssl' => false,
                'timeout' => 10,
                'connect_timeout' => 5,
            ],
        );
    }

    #[Test]
    public function it_searches_packages_from_satis(): void
    {
        $client = $this->makeClient();
        $all = $client->search();

        $this->assertArrayHasKey('microweber-modules/sample-hello', $all);
        $this->assertArrayHasKey('microweber-templates/sample-theme', $all);
        $this->assertArrayHasKey('acme/sample-nwidart', $all);
    }

    #[Test]
    public function it_installs_microweber_module_into_modules_dir(): void
    {
        $client = $this->makeClient();
        $this->assertInstallSucceeds($client, 'microweber-modules/sample-hello');

        $dest = $this->workDir . '/Modules/SampleHello';
        $this->assertDirectoryExists($dest);
        $this->assertFileExists($dest . '/composer.json');
        $this->assertFileExists($dest . '/module.json');
        $this->assertFileExists($dest . '/index.php');
    }

    #[Test]
    public function it_installs_template_into_templates_dir(): void
    {
        $client = $this->makeClient();
        $this->assertInstallSucceeds($client, 'microweber-templates/sample-theme');

        $dest = $this->workDir . '/Templates/SampleTheme';
        $this->assertDirectoryExists($dest);
        $this->assertFileExists($dest . '/composer.json');
        $this->assertFileExists($dest . '/config.json');
    }

    #[Test]
    public function it_installs_nwidart_module_into_modules_dir(): void
    {
        $client = $this->makeClient();
        $this->assertInstallSucceeds($client, 'acme/sample-nwidart');

        $dest = $this->workDir . '/Modules/SampleNwidart';
        $this->assertDirectoryExists($dest);
        $this->assertFileExists($dest . '/module.json');
        $this->assertFileExists($dest . '/Providers/SampleNwidartServiceProvider.php');
    }

    #[Test]
    public function it_detects_install_dirs_without_installing(): void
    {
        $client = $this->makeClient();

        $module = $client->getPackageByName('microweber-modules/sample-hello');
        $t = $client->detectInstallDir($module);
        $this->assertSame('SampleHello', $t->directory);
        $this->assertSame('microweber-module', $t->type);

        $tpl = $client->getPackageByName('microweber-templates/sample-theme');
        $t2 = $client->detectInstallDir($tpl);
        $this->assertSame('SampleTheme', $t2->directory);
        $this->assertSame('microweber-template', $t2->type);

        $nw = $client->getPackageByName('acme/sample-nwidart');
        $t3 = $client->detectInstallDir($nw);
        $this->assertSame('SampleNwidart', $t3->directory);
        $this->assertSame('laravel-module', $t3->type);
    }

    #[Test]
    public function it_updates_by_reinstalling_over_existing(): void
    {
        $client = $this->makeClient();
        $this->assertInstallSucceeds($client, 'microweber-modules/sample-hello');
        // second install = update path
        $this->assertInstallSucceeds($client, 'microweber-modules/sample-hello');
        $this->assertDirectoryExists($this->workDir . '/Modules/SampleHello');
    }

    private function assertInstallSucceeds(PackageManagerClientService $client, string $packageName): void
    {
        $step1 = $client->requestInstall([
            'require_name' => $packageName,
            'require_version' => 'latest',
        ]);

        $this->assertArrayHasKey('form_data_module_params', $step1, json_encode($step1) ?: '');
        $this->assertArrayHasKey('confirm_key', $step1['form_data_module_params']);

        $step2 = $client->requestInstall($step1['form_data_module_params']);
        $this->assertIsArray($step2);
        $this->assertArrayHasKey('success', $step2, json_encode($step2) ?: '');
    }

    private function removeTree(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        $items = scandir($dir) ?: [];
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir . '/' . $item;
            is_dir($path) ? $this->removeTree($path) : @unlink($path);
        }
        @rmdir($dir);
    }
}
