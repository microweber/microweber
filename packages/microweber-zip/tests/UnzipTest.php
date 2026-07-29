<?php

declare(strict_types=1);

namespace MicroweberPackages\Zip\Tests;

use MicroweberPackages\Zip\Support\ZipBombGuard;
use MicroweberPackages\Zip\Unzip;
use PHPUnit\Framework\Attributes\Test;

class UnzipTest extends TestCase
{
    #[Test]
    public function it_extracts_files(): void
    {
        $zipPath = $this->createSampleZip([
            'readme.txt' => 'hello zip',
            'nested/data.json' => '{"ok":true}',
        ]);

        $target = $this->tempDir . '/out';
        $unzip = new Unzip();
        $result = $unzip->extract($zipPath, $target);

        $this->assertIsArray($result);
        $this->assertArrayNotHasKey('error', $result);
        $this->assertFileExists($target . '/readme.txt');
        $this->assertFileExists($target . '/nested/data.json');
        $this->assertSame('hello zip', file_get_contents($target . '/readme.txt'));
        $this->assertSame('{"ok":true}', file_get_contents($target . '/nested/data.json'));
    }

    #[Test]
    public function it_returns_error_for_missing_archive(): void
    {
        $unzip = new Unzip();
        $result = $unzip->extract($this->tempDir . '/nope.zip', $this->tempDir . '/out');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('error', $result);
    }

    #[Test]
    public function it_blocks_path_traversal_entries(): void
    {
        // Build a zip with a traversal name using ZipArchive (it may store the name literally)
        $zipPath = $this->tempDir . '/trav.zip';
        $zip = new \ZipArchive();
        $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $zip->addFromString('safe.txt', 'ok');
        $zip->addFromString('../evil.txt', 'bad');
        $zip->close();

        $target = $this->tempDir . '/out';
        $unzip = new Unzip();
        $result = $unzip->extract($zipPath, $target);

        $this->assertIsArray($result);
        $this->assertFileExists($target . '/safe.txt');
        $this->assertFileDoesNotExist($this->tempDir . '/evil.txt');
        $this->assertFileDoesNotExist(dirname($target) . '/evil.txt');
    }

    #[Test]
    public function it_respects_extension_allowlist(): void
    {
        $zipPath = $this->createSampleZip([
            'ok.txt' => 'text',
            'bad.php' => '<?php echo 1;',
            'pic.png' => 'fakepng',
        ]);

        $target = $this->tempDir . '/out';
        $unzip = new Unzip();
        $unzip->allow(['txt', 'png']);
        $result = $unzip->extract($zipPath, $target);

        $this->assertIsArray($result);
        $this->assertFileExists($target . '/ok.txt');
        $this->assertFileExists($target . '/pic.png');
        $this->assertFileDoesNotExist($target . '/bad.php');
    }

    #[Test]
    public function it_reports_zip_bomb_as_error(): void
    {
        $files = [];
        for ($i = 0; $i < 10; ++$i) {
            $files["f{$i}.txt"] = 'x';
        }
        $zipPath = $this->createSampleZip($files);

        $unzip = new Unzip(new ZipBombGuard(maxFiles: 3));
        $result = $unzip->extract($zipPath, $this->tempDir . '/out');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('error', $result);
        $this->assertNotEmpty($unzip->getErrors());
    }

    #[Test]
    public function native_unzip_returns_list_or_empty(): void
    {
        $zipPath = $this->createSampleZip(['a.txt' => 'a']);
        $unzip = new Unzip();
        $files = $unzip->native_unzip($zipPath, $this->tempDir . '/native');

        $this->assertIsArray($files);
        $this->assertNotEmpty($files);
    }

    #[Test]
    public function error_and_debug_strings_work(): void
    {
        $unzip = new Unzip();
        $unzip->set_error('boom');
        $unzip->set_debug('trace');

        $this->assertStringContainsString('boom', $unzip->error_string());
        $this->assertStringContainsString('trace', $unzip->debug_string());
    }

    #[Test]
    public function it_skips_macosx_junk(): void
    {
        $zipPath = $this->createSampleZip([
            '__MACOSX/._file' => 'meta',
            'real.txt' => 'data',
        ]);

        $target = $this->tempDir . '/out';
        $unzip = new Unzip();
        $unzip->extract($zipPath, $target);

        $this->assertFileExists($target . '/real.txt');
        $this->assertFileDoesNotExist($target . '/__MACOSX/._file');
    }

    #[Test]
    public function extract_without_preserve_flattens_paths(): void
    {
        $zipPath = $this->createSampleZip([
            'deep/nested/file.txt' => 'flat',
        ]);

        $target = $this->tempDir . '/out';
        $unzip = new Unzip();
        $result = $unzip->extract($zipPath, $target, false);

        $this->assertIsArray($result);
        $this->assertFileExists($target . '/file.txt');
        $this->assertFileDoesNotExist($target . '/deep/nested/file.txt');
    }
}
