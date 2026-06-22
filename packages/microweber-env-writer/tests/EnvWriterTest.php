<?php

namespace MicroweberPackages\EnvWriter\Tests;

use MicroweberPackages\EnvWriter\EnvWriter;
use PHPUnit\Framework\TestCase;

class EnvWriterTest extends TestCase
{
    private string $envFile;
    private EnvWriter $writer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->envFile = sys_get_temp_dir() . '/test_env_' . uniqid() . '.env';
        $this->writer = new EnvWriter();
    }

    protected function tearDown(): void
    {
        if (file_exists($this->envFile)) {
            unlink($this->envFile);
        }
        parent::tearDown();
    }

    public function test_creates_new_env_file(): void
    {
        $result = $this->writer->save([
            'APP_NAME' => 'TestApp',
            'APP_KEY' => 'base64:abc123',
        ], $this->envFile);

        $this->assertTrue($result);
        $this->assertFileExists($this->envFile);

        $content = file_get_contents($this->envFile);
        $this->assertStringContainsString('APP_NAME=TestApp', $content);
        $this->assertStringContainsString('APP_KEY=base64:abc123', $content);
    }

    public function test_updates_existing_keys(): void
    {
        file_put_contents($this->envFile, "APP_NAME=OldName\nAPP_KEY=old_key\n");

        $this->writer->save([
            'APP_NAME' => 'NewName',
        ], $this->envFile);

        $content = file_get_contents($this->envFile);
        $this->assertStringContainsString('APP_NAME=NewName', $content);
        $this->assertStringContainsString('APP_KEY=old_key', $content);
    }

    public function test_appends_new_keys(): void
    {
        file_put_contents($this->envFile, "APP_NAME=MyApp\n");

        $this->writer->save([
            'DB_HOST' => 'localhost',
        ], $this->envFile);

        $content = file_get_contents($this->envFile);
        $this->assertStringContainsString('APP_NAME=MyApp', $content);
        $this->assertStringContainsString('DB_HOST=localhost', $content);
    }

    public function test_removes_duplicate_keys(): void
    {
        file_put_contents($this->envFile, "APP_KEY=key1\nAPP_NAME=test\nAPP_KEY=key2\nAPP_KEY=key3\n");

        $this->writer->save([
            'APP_NAME' => 'test',
        ], $this->envFile);

        $content = file_get_contents($this->envFile);
        $this->assertEquals(1, substr_count($content, 'APP_KEY='));
        $this->assertStringContainsString('APP_KEY=key1', $content);
    }

    public function test_prevents_duplicate_app_key_on_multiple_saves(): void
    {
        file_put_contents($this->envFile, "APP_NAME=MyApp\nAPP_KEY=\n");

        // Simulate multiple install calls writing APP_KEY
        for ($i = 0; $i < 5; $i++) {
            $this->writer->save([
                'APP_KEY' => 'base64:generated_key_' . $i,
                'APP_NAME' => 'MyApp',
            ], $this->envFile);
        }

        $content = file_get_contents($this->envFile);
        $this->assertEquals(1, substr_count($content, 'APP_KEY='));
        $this->assertStringContainsString('APP_KEY=base64:generated_key_4', $content);
    }

    public function test_preserves_comments(): void
    {
        file_put_contents($this->envFile, "# Database config\nDB_HOST=localhost\n# End\n");

        $this->writer->save([
            'DB_HOST' => '127.0.0.1',
        ], $this->envFile);

        $content = file_get_contents($this->envFile);
        $this->assertStringContainsString('# Database config', $content);
        $this->assertStringContainsString('# End', $content);
        $this->assertStringContainsString('DB_HOST=127.0.0.1', $content);
    }

    public function test_collapses_consecutive_empty_lines(): void
    {
        file_put_contents($this->envFile, "APP_NAME=test\n\n\n\n\nDB_HOST=localhost\n");

        $this->writer->save([
            'APP_NAME' => 'test',
        ], $this->envFile);

        $content = file_get_contents($this->envFile);
        $this->assertDoesNotMatchRegularExpression('/\n{3,}/', $content);
    }

    public function test_handles_boolean_values(): void
    {
        $this->writer->save([
            'APP_DEBUG' => true,
            'FORCE_HTTPS' => false,
        ], $this->envFile);

        $content = file_get_contents($this->envFile);
        $this->assertStringContainsString('APP_DEBUG=true', $content);
        $this->assertStringContainsString('FORCE_HTTPS=false', $content);
    }

    public function test_handles_integer_values(): void
    {
        $this->writer->save([
            'MW_COMPILE_ASSETS' => 1,
            'MW_DISABLE_MODEL_CACHE' => 0,
        ], $this->envFile);

        $content = file_get_contents($this->envFile);
        $this->assertStringContainsString('MW_COMPILE_ASSETS=1', $content);
        $this->assertStringContainsString('MW_DISABLE_MODEL_CACHE=0', $content);
    }

    public function test_quotes_values_with_spaces(): void
    {
        $this->writer->save([
            'APP_NAME' => 'My Application',
        ], $this->envFile);

        $content = file_get_contents($this->envFile);
        $this->assertStringContainsString('APP_NAME="My Application"', $content);
    }

    public function test_quotes_values_with_hash(): void
    {
        $this->writer->save([
            'DB_PASSWORD' => 'pass#word',
        ], $this->envFile);

        $content = file_get_contents($this->envFile);
        $this->assertStringContainsString('DB_PASSWORD="pass#word"', $content);
    }

    public function test_escapes_quotes_in_values(): void
    {
        $this->writer->save([
            'APP_NAME' => 'My "Awesome" App',
        ], $this->envFile);

        $content = file_get_contents($this->envFile);
        $this->assertStringContainsString('APP_NAME="My \\"Awesome\\" App"', $content);
    }

    public function test_handles_null_values(): void
    {
        $this->writer->save([
            'EMPTY_KEY' => null,
        ], $this->envFile);

        $content = file_get_contents($this->envFile);
        $this->assertStringContainsString('EMPTY_KEY=', $content);
    }

    public function test_handles_empty_string_values(): void
    {
        $this->writer->save([
            'DB_PASSWORD' => '',
        ], $this->envFile);

        $content = file_get_contents($this->envFile);
        $this->assertStringContainsString('DB_PASSWORD=', $content);
    }

    public function test_read_parses_env_file(): void
    {
        file_put_contents($this->envFile, "APP_NAME=TestApp\nAPP_KEY=secret\n# comment\nDB_HOST=localhost\n");

        $values = $this->writer->read($this->envFile);

        $this->assertEquals('TestApp', $values['APP_NAME']);
        $this->assertEquals('secret', $values['APP_KEY']);
        $this->assertEquals('localhost', $values['DB_HOST']);
        $this->assertCount(3, $values);
    }

    public function test_read_handles_quoted_values(): void
    {
        file_put_contents($this->envFile, "APP_NAME=\"My App\"\nPASSWORD='secret'\n");

        $values = $this->writer->read($this->envFile);

        $this->assertEquals('My App', $values['APP_NAME']);
        $this->assertEquals('secret', $values['PASSWORD']);
    }

    public function test_read_returns_empty_for_missing_file(): void
    {
        $values = $this->writer->read('/nonexistent/.env');
        $this->assertEmpty($values);
    }

    public function test_full_roundtrip_with_complex_env(): void
    {
        $initial = <<<'ENV'
APP_NAME=Microweber
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000/

# Database Configuration
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

SESSION_DRIVER=database
ENV;
        file_put_contents($this->envFile, $initial);

        $this->writer->save([
            'APP_KEY' => 'base64:newkey123',
            'DB_CONNECTION' => 'mysql',
            'DB_HOST' => 'localhost',
            'DB_DATABASE' => 'mydb',
            'DB_USERNAME' => 'root',
            'DB_PASSWORD' => '',
            'MW_IS_INSTALLED' => 1,
        ], $this->envFile);

        $content = file_get_contents($this->envFile);

        // Original keys should be updated in place
        $this->assertStringContainsString('APP_KEY=base64:newkey123', $content);
        $this->assertStringContainsString('DB_CONNECTION=mysql', $content);
        $this->assertStringContainsString('DB_DATABASE=mydb', $content);

        // Preserved keys
        $this->assertStringContainsString('APP_NAME=Microweber', $content);
        $this->assertStringContainsString('SESSION_DRIVER=database', $content);

        // Comment preserved
        $this->assertStringContainsString('# Database Configuration', $content);

        // New keys appended
        $this->assertStringContainsString('DB_HOST=localhost', $content);
        $this->assertStringContainsString('MW_IS_INSTALLED=1', $content);

        // No duplicates
        $this->assertEquals(1, substr_count($content, 'APP_KEY='));
        $this->assertEquals(1, substr_count($content, 'DB_CONNECTION='));
    }

    public function test_handles_values_with_dollar_signs(): void
    {
        $this->writer->save([
            'PASSWORD' => 'pa$$word',
        ], $this->envFile);

        $content = file_get_contents($this->envFile);
        $this->assertStringContainsString('PASSWORD="pa$$word"', $content);
    }

    public function test_idempotent_write(): void
    {
        file_put_contents($this->envFile, "APP_NAME=Test\nAPP_KEY=key1\n");

        // Write same values twice
        $this->writer->save(['APP_NAME' => 'Test', 'APP_KEY' => 'key1'], $this->envFile);
        $content1 = file_get_contents($this->envFile);

        $this->writer->save(['APP_NAME' => 'Test', 'APP_KEY' => 'key1'], $this->envFile);
        $content2 = file_get_contents($this->envFile);

        $this->assertEquals($content1, $content2);
    }

    public function test_json_encoded_value_is_quoted(): void
    {
        $jsonValue = json_encode(['key' => 'value', 'admin_email' => 'test@test.com']);

        $this->writer->save([
            'MW_PRE_CONFIGURED_INPUT' => $jsonValue,
        ], $this->envFile);

        $content = file_get_contents($this->envFile);
        $this->assertStringContainsString('MW_PRE_CONFIGURED_INPUT="', $content);
    }
}