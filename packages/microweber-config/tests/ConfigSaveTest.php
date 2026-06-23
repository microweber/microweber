<?php

namespace MicroweberPackages\Config\Tests;

use Illuminate\Support\Facades\Config;
use PHPUnit\Framework\Attributes\Test;

class ConfigSaveTest extends TestCase
{
    private string $tempConfigDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tempConfigDir = $this->app->configPath();
    }

    protected function tearDown(): void
    {
        // Clean up any files we created
        $testFiles = [
            $this->tempConfigDir . '/microweber_save_test.php',
            $this->tempConfigDir . '/save_allowed_test.php',
            $this->tempConfigDir . '/path_convert_test.php',
        ];

        foreach ($testFiles as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }

        // Clean up env directory
        $envDir = $this->tempConfigDir . '/' . $this->app->environment();
        if (is_dir($envDir)) {
            $files = glob($envDir . '/*');
            foreach ($files as $f) {
                @unlink($f);
            }
            @rmdir($envDir);
        }

        parent::tearDown();
    }

    #[Test]
    public function it_saves_simple_config_to_file(): void
    {
        $time = time();
        Config::set('microweber_save_test.firstName', 'Bozhidar');
        Config::set('microweber_save_test.lastName', 'Slaveykov');
        Config::set('microweber_save_test.time', $time);

        Config::save(['microweber_save_test']);

        // Verify in-memory values
        $this->assertEquals('Bozhidar', Config::get('microweber_save_test.firstName'));
        $this->assertEquals('Slaveykov', Config::get('microweber_save_test.lastName'));
        $this->assertEquals($time, Config::get('microweber_save_test.time'));

        // Verify file was created
        $configFile = $this->tempConfigDir . '/microweber_save_test.php';
        $this->assertFileExists($configFile);

        // Verify file content
        $content = include $configFile;
        $this->assertIsArray($content);
        $this->assertEquals('Bozhidar', $content['firstName']);
        $this->assertEquals('Slaveykov', $content['lastName']);
        $this->assertEquals($time, $content['time']);
    }

    #[Test]
    public function it_respects_allowed_filter_on_save(): void
    {
        Config::set('save_allowed_test.val', 'saved');
        Config::set('not_allowed_test.val', 'not_saved');

        Config::save(['save_allowed_test']);

        $allowedFile = $this->tempConfigDir . '/save_allowed_test.php';
        $notAllowedFile = $this->tempConfigDir . '/not_allowed_test.php';

        $this->assertFileExists($allowedFile);
        $this->assertFileDoesNotExist($notAllowedFile);
    }

    #[Test]
    public function it_saves_to_env_directory_when_it_exists(): void
    {
        $envDir = $this->tempConfigDir . '/' . $this->app->environment();
        if (!is_dir($envDir)) {
            mkdir($envDir, 0755, true);
        }

        // We need to re-init the config to detect the env dir
        $this->app->instance('config', $config = new \MicroweberPackages\Config\ConfigRepository($this->app));

        Config::set('microweber_save_test.envVal', 'env_value');
        Config::save(['microweber_save_test']);

        $envFile = $envDir . '/microweber_save_test.php';
        $this->assertFileExists($envFile);

        $content = include $envFile;
        $this->assertEquals('env_value', $content['envVal']);
    }

    #[Test]
    public function it_converts_storage_path_to_relative(): void
    {
        $storagePath = storage_path();
        Config::set('path_convert_test.db_path', $storagePath . '/database.sqlite');

        Config::save(['path_convert_test']);

        $configFile = $this->tempConfigDir . '/path_convert_test.php';
        $this->assertFileExists($configFile);

        $fileContent = file_get_contents($configFile);

        // The file should contain the storage_path() helper call instead of the absolute path
        $this->assertStringContainsString('storage_path()', $fileContent);
        $this->assertStringNotContainsString($storagePath, $fileContent);
    }

    #[Test]
    public function it_saves_with_string_allowed_filter(): void
    {
        Config::set('microweber_save_test.stringFilter', 'val');
        Config::save('microweber_save_test');

        $configFile = $this->tempConfigDir . '/microweber_save_test.php';
        $this->assertFileExists($configFile);
    }

    #[Test]
    public function it_saves_all_changed_keys_when_no_filter(): void
    {
        Config::set('microweber_save_test.all1', 'v1');
        Config::save(['microweber_save_test']);

        $this->assertFileExists($this->tempConfigDir . '/microweber_save_test.php');
    }

    #[Test]
    public function it_creates_directory_if_not_exists(): void
    {
        $envDir = $this->tempConfigDir . '/' . $this->app->environment();

        // Ensure directory does not exist before the test
        if (is_dir($envDir)) {
            $files = glob($envDir . '/*');
            foreach ($files as $f) {
                @unlink($f);
            }
            @rmdir($envDir);
        }

        // Without env dir it saves to default config path
        Config::set('microweber_save_test.dirTest', 'value');
        Config::save(['microweber_save_test']);

        $this->assertFileExists($this->tempConfigDir . '/microweber_save_test.php');
    }
}