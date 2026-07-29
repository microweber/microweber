<?php

declare(strict_types=1);

namespace MicroweberPackages\Zip\Tests;

use MicroweberPackages\Zip\Unzip;
use MicroweberPackages\Zip\Zip;
use PHPUnit\Framework\Attributes\Test;
use ZipArchive;

class ZipCreatorTest extends TestCase
{
    #[Test]
    public function it_creates_zip_from_string_data(): void
    {
        $zip = new Zip();
        $zip->addFile('hello world', 'hello.txt');
        $zip->addFile('{"a":1}', 'data/config.json');
        $out = $this->tempDir . '/created.zip';
        $this->assertTrue($zip->saveTo($out));
        $this->assertFileExists($out);

        $archive = new ZipArchive();
        $this->assertTrue($archive->open($out));
        $this->assertSame('hello world', $archive->getFromName('hello.txt'));
        $this->assertSame('{"a":1}', $archive->getFromName('data/config.json'));
        $archive->close();
    }

    #[Test]
    public function it_adds_directory_content(): void
    {
        $src = $this->tempDir . '/src';
        mkdir($src . '/sub', 0755, true);
        file_put_contents($src . '/root.txt', 'root');
        file_put_contents($src . '/sub/child.txt', 'child');

        $zip = new Zip();
        $zip->addDirectoryContent($src, 'bundle');
        $out = $this->tempDir . '/dir.zip';
        $zip->saveTo($out);

        $target = $this->tempDir . '/extracted';
        $unzip = new Unzip();
        $result = $unzip->extract($out, $target);

        $this->assertIsArray($result);
        $this->assertArrayNotHasKey('error', $result);
        $this->assertFileExists($target . '/bundle/root.txt');
        $this->assertFileExists($target . '/bundle/sub/child.txt');
        $this->assertSame('root', file_get_contents($target . '/bundle/root.txt'));
    }

    #[Test]
    public function it_sets_comment(): void
    {
        $zip = new Zip();
        $this->assertTrue($zip->setComment('my archive'));
        $zip->addFile('x', 'x.txt');
        $out = $this->tempDir . '/comment.zip';
        $zip->saveTo($out);

        $archive = new ZipArchive();
        $archive->open($out);
        $this->assertSame('my archive', $archive->getArchiveComment());
        $archive->close();
    }

    #[Test]
    public function it_returns_zip_data_bytes(): void
    {
        $zip = new Zip();
        $zip->addFile('payload', 'p.txt');
        $data = $zip->getZipData();

        $this->assertNotEmpty($data);
        $this->assertSame("PK", substr($data, 0, 2));
        $this->assertGreaterThan(0, $zip->getArchiveSize());
    }

    #[Test]
    public function path_join_works(): void
    {
        $this->assertSame('a/b', Zip::pathJoin('a', 'b'));
        $this->assertSame('a/b', Zip::pathJoin('a/', '/b'));
    }

    #[Test]
    public function finalize_is_idempotent(): void
    {
        $zip = new Zip();
        $zip->addFile('x', 'x.txt');
        $this->assertTrue($zip->finalize());
        $this->assertFalse($zip->finalize());
        $this->assertFalse($zip->addFile('y', 'y.txt'));
    }

    #[Test]
    public function roundtrip_with_unzip(): void
    {
        $zip = new Zip();
        $zip->addDirectory('folder');
        $zip->addFile("line1\nline2", 'folder/notes.txt');
        $out = $this->tempDir . '/roundtrip.zip';
        $zip->saveTo($out);

        $target = $this->tempDir . '/rt';
        $files = (new Unzip())->extract($out, $target);

        $this->assertIsArray($files);
        $this->assertFileExists($target . '/folder/notes.txt');
        $this->assertSame("line1\nline2", file_get_contents($target . '/folder/notes.txt'));
    }
}
