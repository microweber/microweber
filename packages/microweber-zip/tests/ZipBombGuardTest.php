<?php

declare(strict_types=1);

namespace MicroweberPackages\Zip\Tests;

use MicroweberPackages\Zip\Exceptions\ZipBombException;
use MicroweberPackages\Zip\Support\ZipBombGuard;
use PHPUnit\Framework\Attributes\Test;
use ZipArchive;

class ZipBombGuardTest extends TestCase
{
    #[Test]
    public function it_allows_normal_archives(): void
    {
        $zipPath = $this->createSampleZip([
            'a.txt' => 'hello',
            'b.txt' => 'world',
        ]);

        $zip = new ZipArchive();
        $zip->open($zipPath);

        $guard = new ZipBombGuard();
        $guard->validateArchive($zip); // should not throw

        $zip->close();
        $this->assertTrue(true);
    }

    #[Test]
    public function it_rejects_too_many_files(): void
    {
        $files = [];
        for ($i = 0; $i < 5; ++$i) {
            $files["f{$i}.txt"] = 'x';
        }
        $zipPath = $this->createSampleZip($files);

        $zip = new ZipArchive();
        $zip->open($zipPath);

        $guard = new ZipBombGuard(maxFiles: 3);

        $this->expectException(ZipBombException::class);
        $this->expectExceptionMessage('exceeds the limit');
        $guard->validateArchive($zip);
    }

    #[Test]
    public function it_rejects_high_compression_ratio(): void
    {
        // Highly compressible content
        $content = str_repeat('A', 50_000);
        $zipPath = $this->createSampleZip(['bomb.txt' => $content]);

        $zip = new ZipArchive();
        $zip->open($zipPath);

        // Very low ratio limit so any deflate of zeros/As trips it
        $guard = new ZipBombGuard(maxCompressionRatio: 2.0);

        $this->expectException(ZipBombException::class);
        $guard->validateArchive($zip);
    }

    #[Test]
    public function it_rejects_oversized_single_file(): void
    {
        $guard = new ZipBombGuard(maxSingleFileUncompressedBytes: 100);

        $this->expectException(ZipBombException::class);
        $guard->validateEntry([
            'name' => 'big.bin',
            'size' => 200,
            'comp_size' => 200,
        ]);
    }

    #[Test]
    public function it_rejects_total_size_overflow(): void
    {
        $guard = new ZipBombGuard(maxTotalUncompressedBytes: 500);

        $this->expectException(ZipBombException::class);
        $guard->validateEntry([
            'name' => 'chunk.bin',
            'size' => 300,
            'comp_size' => 300,
        ], 300);
    }

    #[Test]
    public function from_config_builds_guard(): void
    {
        $guard = ZipBombGuard::fromConfig([
            'max_files' => 42,
            'max_path_length' => 100,
        ]);

        $this->assertSame(100, $guard->getMaxPathLength());
    }

    #[Test]
    public function zero_limits_disable_checks(): void
    {
        $guard = new ZipBombGuard(
            maxFiles: 0,
            maxTotalUncompressedBytes: 0,
            maxSingleFileUncompressedBytes: 0,
            maxCompressionRatio: 0.0,
            maxPathLength: 0,
        );

        // Should not throw even with huge numbers
        $guard->validateEntry([
            'name' => 'huge.bin',
            'size' => 10_000_000_000,
            'comp_size' => 1,
        ]);

        $this->assertTrue(true);
    }
}
