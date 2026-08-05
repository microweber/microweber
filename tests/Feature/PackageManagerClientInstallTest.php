<?php

declare(strict_types=1);

namespace Tests\Feature;

use MicroweberPackages\PackageManagerClient\InstallDirDetector;
use MicroweberPackages\PackageManagerClient\PackageManagerClient;
use MicroweberPackages\PackageManagerClient\PackageSignatureVerifier;
use MicroweberPackages\PackageManagerClient\Tests\Support\SatisServer;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * CMS-level coverage for the extracted package manager client:
 * install sample module/template/nwidart packages from a temp Satis server.
 */
class PackageManagerClientInstallTest extends TestCase
{
    private SatisServer $server;
    private string $workDir;

    protected function setUp(): void
    {
        parent::setUp();

        $fixtures = base_path('packages/microweber-package-manager-client/tests/Fixtures/sample-packages');
        $this->workDir = sys_get_temp_dir() . '/mw-cms-pmc-' . uniqid('', true);
        mkdir($this->workDir . '/Modules', 0777, true);
        mkdir($this->workDir . '/Templates', 0777, true);
        mkdir($this->workDir . '/dl', 0777, true);
        mkdir($this->workDir . '/logs', 0777, true);

        $this->server = new SatisServer();
        $this->server->addPackageFromDirectory($fixtures . '/module-sample');
        $this->server->addPackageFromDirectory($fixtures . '/template-sample');
        $this->server->addPackageFromDirectory($fixtures . '/nwidart-module-sample');
        $this->server->start();
    }

    protected function tearDown(): void
    {
        $this->server->cleanup();
        $this->removeTree($this->workDir);
        parent::tearDown();
    }

    #[Test]
    public function package_is_bound_in_container(): void
    {
        $this->assertTrue($this->app->bound(PackageManagerClient::class));
        $this->assertInstanceOf(PackageManagerClient::class, app(PackageManagerClient::class));
        $this->assertInstanceOf(InstallDirDetector::class, app(InstallDirDetector::class));
    }

    #[Test]
    public function installs_sample_packages_from_satis_into_correct_dirs(): void
    {
        $client = new PackageManagerClient(
            packageServers: [$this->server->packagesJsonUrl()],
            config: [
                'base_path' => $this->workDir,
                'modules_path' => 'Modules',
                'templates_path' => 'Templates',
                'download_path' => $this->workDir . '/dl',
                'log_path' => $this->workDir . '/logs/install.log',
                'verify_ssl' => false,
                'timeout' => 10,
                'connect_timeout' => 5,
            ],
        );

        foreach ([
            'microweber-modules/sample-hello' => $this->workDir . '/Modules/SampleHello/module.json',
            'microweber-templates/sample-theme' => $this->workDir . '/Templates/SampleTheme/composer.json',
            'acme/sample-nwidart' => $this->workDir . '/Modules/SampleNwidart/module.json',
        ] as $packageName => $expectedFile) {
            $step1 = $client->requestInstall(['require_name' => $packageName]);
            $this->assertArrayHasKey('form_data_module_params', $step1, json_encode($step1) ?: $packageName);
            $step2 = $client->requestInstall($step1['form_data_module_params']);
            $this->assertArrayHasKey('success', $step2, json_encode($step2) ?: $packageName);
            $this->assertFileExists($expectedFile, "Install path wrong for {$packageName}");
        }
    }

    #[Test]
    public function signature_verifier_is_available(): void
    {
        $this->assertTrue(PackageSignatureVerifier::isHttpsOnly('https://example.com/a.zip'));
        $this->assertFalse(PackageSignatureVerifier::isHttpsOnly('http://example.com/a.zip'));
    }


    private function removeTree(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        foreach (scandir($dir) ?: [] as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir . '/' . $item;
            is_dir($path) ? $this->removeTree($path) : @unlink($path);
        }
        @rmdir($dir);
    }
}
