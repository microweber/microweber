<?php

declare(strict_types=1);

namespace MicroweberPackages\Passport\Tests\Unit;

use MicroweberPackages\Passport\Services\RSAKeyManager;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class RSAKeyManagerTest extends TestCase
{
    private string $tempDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tempDir = sys_get_temp_dir() . '/mw-passport-test-' . uniqid();
        @mkdir($this->tempDir, 0755, true);
    }

    protected function tearDown(): void
    {
        @unlink($this->tempDir . '/oauth-public.key');
        @unlink($this->tempDir . '/oauth-private.key');
        @rmdir($this->tempDir);
        parent::tearDown();
    }

    #[Test]
    public function it_generates_rsa_keys_when_missing(): void
    {
        $this->assertFileDoesNotExist($this->tempDir . '/oauth-public.key');
        $this->assertFileDoesNotExist($this->tempDir . '/oauth-private.key');

        RSAKeyManager::ensureKeys($this->tempDir);

        $this->assertFileExists($this->tempDir . '/oauth-public.key');
        $this->assertFileExists($this->tempDir . '/oauth-private.key');

        $publicKey = file_get_contents($this->tempDir . '/oauth-public.key');
        $privateKey = file_get_contents($this->tempDir . '/oauth-private.key');

        $this->assertStringContainsString('BEGIN PUBLIC KEY', $publicKey);
        $this->assertStringContainsString('BEGIN PRIVATE KEY', $privateKey);
    }

    #[Test]
    public function it_does_not_overwrite_existing_keys(): void
    {
        RSAKeyManager::ensureKeys($this->tempDir);
        $originalPublic = file_get_contents($this->tempDir . '/oauth-public.key');
        $originalPrivate = file_get_contents($this->tempDir . '/oauth-private.key');

        RSAKeyManager::ensureKeys($this->tempDir);

        $this->assertSame($originalPublic, file_get_contents($this->tempDir . '/oauth-public.key'));
        $this->assertSame($originalPrivate, file_get_contents($this->tempDir . '/oauth-private.key'));
    }
}