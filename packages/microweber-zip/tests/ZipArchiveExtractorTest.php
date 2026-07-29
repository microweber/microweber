<?php

declare(strict_types=1);

namespace MicroweberPackages\Zip\Tests;

use MicroweberPackages\Zip\Contracts\FileAllowanceCheckerInterface;
use MicroweberPackages\Zip\Contracts\ZipLoggerInterface;
use MicroweberPackages\Zip\Exceptions\InvalidArchiveException;
use MicroweberPackages\Zip\Exceptions\ZipBombException;
use MicroweberPackages\Zip\Support\ZipBombGuard;
use MicroweberPackages\Zip\ZipArchiveExtractor;
use PHPUnit\Framework\Attributes\Test;

class ZipArchiveExtractorTest extends TestCase
{
    #[Test]
    public function it_extracts_to_target(): void
    {
        $zipPath = $this->createSampleZip([
            'one.txt' => '1',
            'dir/two.txt' => '2',
        ]);

        $target = $this->tempDir . '/extract/';
        $extractor = new ZipArchiveExtractor($zipPath);
        $ok = $extractor->extractTo($target);

        $this->assertTrue($ok);
        $this->assertFileExists($target . 'one.txt');
        $this->assertFileExists($target . 'dir/two.txt');
        $this->assertSame('1', file_get_contents($target . 'one.txt'));
    }

    #[Test]
    public function it_throws_on_missing_file_when_strict(): void
    {
        $this->expectException(InvalidArchiveException::class);
        new ZipArchiveExtractor($this->tempDir . '/missing.zip', null, null, true);
    }

    #[Test]
    public function it_silently_fails_open_for_non_zip_legacy_compat(): void
    {
        $notZip = $this->tempDir . '/plain.txt';
        file_put_contents($notZip, 'not a zip');

        $extractor = new ZipArchiveExtractor($notZip);
        $this->assertFalse($extractor->isOpened());
        $this->assertFalse($extractor->extractTo($this->tempDir . '/out/'));
    }

    #[Test]
    public function it_filters_with_allowance_checker(): void
    {
        $zipPath = $this->createSampleZip([
            'keep.txt' => 'yes',
            'drop.exe' => 'no',
        ]);

        $checker = new class implements FileAllowanceCheckerInterface {
            public function isAllowed(string $entryName): bool
            {
                return !str_ends_with($entryName, '.exe');
            }
        };

        $target = $this->tempDir . '/filtered/';
        $extractor = new ZipArchiveExtractor($zipPath, null, $checker);
        $extractor->setAllowedFilesCheck(true);
        $extractor->extractTo($target);

        $this->assertFileExists($target . 'keep.txt');
        $this->assertFileDoesNotExist($target . 'drop.exe');
    }

    #[Test]
    public function it_logs_via_logger_interface(): void
    {
        $zipPath = $this->createSampleZip(['a.txt' => 'a']);
        $messages = [];

        $logger = new class ($messages) implements ZipLoggerInterface {
            /** @param list<string> $messages */
            public function __construct(private array &$messages)
            {
            }

            public function info(string $message): void
            {
                $this->messages[] = $message;
            }
        };

        $extractor = new ZipArchiveExtractor($zipPath);
        $extractor->setLogger($logger);
        $extractor->extractTo($this->tempDir . '/logged/');

        $this->assertNotEmpty($messages);
        $this->assertStringContainsString('Unzipping queue', $messages[0]);
    }

    #[Test]
    public function it_logs_via_class_string_adapter(): void
    {
        $zipPath = $this->createSampleZip(['a.txt' => 'a']);

        FakeStaticZipLogger::reset();

        $extractor = new ZipArchiveExtractor($zipPath);
        $extractor->setLogger(FakeStaticZipLogger::class);
        $extractor->extractTo($this->tempDir . '/classlog/');

        $this->assertNotEmpty(FakeStaticZipLogger::$logs);
    }

    #[Test]
    public function it_throws_zip_bomb_on_too_many_files(): void
    {
        $files = [];
        for ($i = 0; $i < 8; ++$i) {
            $files["n{$i}.txt"] = 'x';
        }
        $zipPath = $this->createSampleZip($files);

        $extractor = new ZipArchiveExtractor($zipPath, new ZipBombGuard(maxFiles: 3));

        $this->expectException(ZipBombException::class);
        $extractor->extractTo($this->tempDir . '/bomb/');
    }

    #[Test]
    public function list_entries_returns_names(): void
    {
        $zipPath = $this->createSampleZip([
            'a.txt' => 'a',
            'b/c.txt' => 'c',
        ]);

        $extractor = new ZipArchiveExtractor($zipPath);
        $entries = $extractor->listEntries();
        $extractor->close();

        $this->assertContains('a.txt', $entries);
        $this->assertContains('b/c.txt', $entries);
    }

    #[Test]
    public function it_skips_traversal_entries_without_failing(): void
    {
        $zipPath = $this->tempDir . '/mix.zip';
        $zip = new \ZipArchive();
        $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $zip->addFromString('ok.txt', 'ok');
        $zip->addFromString('../escape.txt', 'bad');
        $zip->close();

        $target = $this->tempDir . '/safe/';
        $extractor = new ZipArchiveExtractor($zipPath);
        $ok = $extractor->extractTo($target);

        $this->assertTrue($ok);
        $this->assertFileExists($target . 'ok.txt');
        $this->assertFileDoesNotExist($this->tempDir . '/escape.txt');
    }
}
