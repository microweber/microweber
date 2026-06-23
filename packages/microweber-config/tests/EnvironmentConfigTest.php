<?php

namespace MicroweberPackages\Config\Tests;

use MicroweberPackages\Config\ConfigRepository;
use PHPUnit\Framework\Attributes\Test;

class EnvironmentConfigTest extends TestCase
{
    private string $envDir;

    protected function setUp(): void
    {
        // Create an environment-specific config directory before the app boots
        $configPath = __DIR__ . '/../vendor/orchestra/testbench-core/laravel/config';
        if (!is_dir($configPath)) {
            $configPath = $this->getBasePath() . '/config';
        }

        $this->envDir = $configPath . '/testing';
        if (!is_dir($this->envDir)) {
            @mkdir($this->envDir, 0755, true);
        }

        // Write a test config to the env directory
        file_put_contents($this->envDir . '/envtest.php', '<?php return ["site_name" => "Testing Site", "debug_mode" => true];');

        parent::setUp();
    }

    protected function tearDown(): void
    {
        // Clean up
        if (is_file($this->envDir . '/envtest.php')) {
            @unlink($this->envDir . '/envtest.php');
        }
        if (is_dir($this->envDir)) {
            $files = glob($this->envDir . '/*');
            foreach ($files as $f) {
                @unlink($f);
            }
            @rmdir($this->envDir);
        }

        parent::tearDown();
    }

    #[Test]
    public function it_loads_config_from_environment_directory(): void
    {
        // Re-instantiate config to pick up the env directory
        $config = new ConfigRepository($this->app);
        $this->app->instance('config', $config);

        $this->assertEquals('Testing Site', $config->get('envtest.site_name'));
        $this->assertTrue($config->get('envtest.debug_mode'));
    }

    #[Test]
    public function it_detects_multisite_when_env_dir_exists(): void
    {
        $config = new ConfigRepository($this->app);
        $this->assertTrue($config->isMultisite());
    }

    #[Test]
    public function it_overrides_base_config_with_env_config(): void
    {
        // Write a base config
        $baseConfigPath = $this->app->configPath() . '/envtest.php';
        file_put_contents($baseConfigPath, '<?php return ["site_name" => "Base Site", "base_only" => true];');

        // Re-instantiate
        $config = new ConfigRepository($this->app);
        $this->app->instance('config', $config);

        // The env config should override base
        $this->assertEquals('Testing Site', $config->get('envtest.site_name'));
        // But base_only should not exist since the env file replaced the entire key
        // (this is the expected behavior - env directory files fully replace the base file)

        @unlink($baseConfigPath);
    }
}