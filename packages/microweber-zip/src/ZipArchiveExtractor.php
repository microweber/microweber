<?php

declare(strict_types=1);

namespace MicroweberPackages\Zip;

use MicroweberPackages\Zip\Contracts\FileAllowanceCheckerInterface;
use MicroweberPackages\Zip\Contracts\ZipLoggerInterface;
use MicroweberPackages\Zip\Exceptions\InvalidArchiveException;
use MicroweberPackages\Zip\Exceptions\UnsafePathException;
use MicroweberPackages\Zip\Exceptions\ZipBombException;
use MicroweberPackages\Zip\Exceptions\ZipException;
use MicroweberPackages\Zip\Support\ClassZipLogger;
use MicroweberPackages\Zip\Support\FilesystemAllowanceChecker;
use MicroweberPackages\Zip\Support\SafePath;
use MicroweberPackages\Zip\Support\ZipBombGuard;
use ZipArchive;

/**
 * Safe ZipArchive-based extractor with zip-bomb protection and path guards.
 *
 * Public API is kept compatible with the legacy MicroweberPackages\Utils\Zip\ZipArchiveExtractor
 * so CMS call sites (Restore, install) need only a namespace change.
 */
class ZipArchiveExtractor
{
    public bool $allowedFilesCheck = false;

    public ZipArchive $zipInstance;

    public ZipLoggerInterface|string|false $logger = false;

    private ZipBombGuard $bombGuard;

    private ?FileAllowanceCheckerInterface $allowanceChecker = null;

    private bool $closed = false;

    private bool $openedSuccessfully = false;

    /**
     * @param string                   $zipFile   Path to the zip archive
     * @param ZipBombGuard|null        $bombGuard Optional custom guard; defaults from config when available
     * @param FileAllowanceCheckerInterface|null $allowanceChecker Optional allowed-file checker
     * @param bool                     $throwOnOpenFailure When true, throws if the archive cannot be opened
     *
     * @throws InvalidArchiveException When $throwOnOpenFailure is true and open fails
     */
    public function __construct(
        string $zipFile,
        ?ZipBombGuard $bombGuard = null,
        ?FileAllowanceCheckerInterface $allowanceChecker = null,
        bool $throwOnOpenFailure = false,
    ) {
        $this->zipInstance = new ZipArchive();
        $this->bombGuard = $bombGuard ?? $this->resolveDefaultGuard();
        $this->allowanceChecker = $allowanceChecker;

        if (!is_file($zipFile) || !is_readable($zipFile)) {
            if ($throwOnOpenFailure) {
                throw new InvalidArchiveException(sprintf('Zip file not found or not readable: %s', $zipFile));
            }
            $this->log('Zip file not found or not readable: ' . $zipFile);

            return;
        }

        $opened = $this->zipInstance->open($zipFile);
        if ($opened !== true) {
            // Legacy CMS behaviour: silent open failure so non-zip files
            // (csv/json samples routed through ZipReader) degrade gracefully.
            if ($throwOnOpenFailure) {
                throw new InvalidArchiveException(
                    sprintf('Unable to open zip archive "%s" (code %s).', $zipFile, (string) $opened)
                );
            }
            $this->log(sprintf('Unable to open zip archive "%s" (code %s).', $zipFile, (string) $opened));

            return;
        }

        $this->openedSuccessfully = true;
    }

    public function isOpened(): bool
    {
        return $this->openedSuccessfully && !$this->closed;
    }

    public function setLogger(ZipLoggerInterface|string|false $logger): void
    {
        $this->logger = $logger;
    }

    public function setAllowedFilesCheck(bool $check): void
    {
        $this->allowedFilesCheck = $check;
    }

    public function setBombGuard(ZipBombGuard $guard): void
    {
        $this->bombGuard = $guard;
    }

    public function setAllowanceChecker(FileAllowanceCheckerInterface $checker): void
    {
        $this->allowanceChecker = $checker;
    }

    /**
     * Extract the archive into $path.
     *
     * @return bool True when at least one file was extracted
     *
     * @throws ZipBombException
     * @throws ZipException
     */
    public function extractTo(string $path): bool
    {
        if ($this->closed) {
            throw new ZipException('Archive has already been closed.');
        }

        if (!$this->openedSuccessfully) {
            $this->log('The zip file has no files.');

            return false;
        }

        $path = SafePath::normalize($path, true);
        SafePath::mkdirRecursive(rtrim($path, '/'));

        $this->bombGuard->validateArchive($this->zipInstance);

        $selectedFilesForUnzip = [];
        $maxPathLength = $this->bombGuard->getMaxPathLength();

        for ($i = 0; $i < $this->zipInstance->numFiles; ++$i) {
            $stat = $this->zipInstance->statIndex($i);
            if ($stat === false) {
                continue;
            }

            $rawName = (string) ($stat['name'] ?? '');
            $isDirectory = str_ends_with(str_replace('\\', '/', $rawName), '/');
            $entryName = SafePath::normalize($rawName, false);
            if ($entryName === '' || $isDirectory) {
                // Directory entries: create if safe, otherwise skip
                try {
                    if ($entryName !== '') {
                        SafePath::assertSafeEntry($entryName, $maxPathLength);
                        SafePath::mkdirRecursive($path . $entryName);
                    }
                } catch (UnsafePathException) {
                    $this->log('Skipped unsafe directory entry: ' . $entryName);
                }
                continue;
            }

            try {
                SafePath::assertSafeEntry($entryName, $maxPathLength);
            } catch (UnsafePathException $e) {
                $this->log('Skipped unsafe entry: ' . $entryName . ' (' . $e->getMessage() . ')');
                continue;
            }

            if ($this->allowedFilesCheck) {
                $checker = $this->allowanceChecker ?? new FilesystemAllowanceChecker();
                if (!$checker->isAllowed($entryName)) {
                    $this->log('Skipped disallowed file: ' . $entryName);
                    continue;
                }
            }

            $selectedFilesForUnzip[] = $entryName;
            $this->log('Unzipping queue ' . $entryName . '...');

            try {
                $targetFileSave = SafePath::resolveTarget($path, $entryName);
            } catch (UnsafePathException $e) {
                $this->log('Skipped unsafe target: ' . $entryName . ' (' . $e->getMessage() . ')');
                continue;
            }

            $parent = dirname($targetFileSave);
            if (!is_dir($parent)) {
                SafePath::mkdirRecursive($parent);
            }

            $contents = $this->zipInstance->getFromIndex($i);
            if ($contents === false) {
                $this->log('Failed to read entry: ' . $entryName);
                continue;
            }

            // Defence in depth: refuse writing more bytes than declared uncompressed size + small slack
            $declared = (int) ($stat['size'] ?? 0);
            if ($declared > 0 && strlen($contents) > $declared * 2 + 1024) {
                throw new ZipBombException(
                    sprintf(
                        'Entry "%s" expanded larger than declared size (declared %d, got %d).',
                        $entryName,
                        $declared,
                        strlen($contents)
                    )
                );
            }

            file_put_contents($targetFileSave, $contents);
        }

        if ($selectedFilesForUnzip === []) {
            $this->log('The zip file has no files.');
            $this->close();

            return false;
        }

        $this->close();

        return true;
    }

    /**
     * @return list<string>
     */
    public function listEntries(): array
    {
        if (!$this->openedSuccessfully) {
            return [];
        }

        $entries = [];
        for ($i = 0; $i < $this->zipInstance->numFiles; ++$i) {
            $stat = $this->zipInstance->statIndex($i);
            if ($stat !== false && isset($stat['name'])) {
                $entries[] = (string) $stat['name'];
            }
        }

        return $entries;
    }

    public function close(): void
    {
        if (!$this->closed && $this->openedSuccessfully) {
            $this->zipInstance->close();
        }
        $this->closed = true;
    }

    public function __destruct()
    {
        if (!$this->closed && $this->openedSuccessfully) {
            @$this->zipInstance->close();
        }
        $this->closed = true;
    }

    private function log(string $message): void
    {
        if ($this->logger instanceof ZipLoggerInterface) {
            $this->logger->info($message);

            return;
        }

        if (is_string($this->logger) && $this->logger !== '' && class_exists($this->logger)) {
            /** @var class-string $loggerClass */
            $loggerClass = $this->logger;
            (new ClassZipLogger($loggerClass))->info($message);
        }
    }

    private function resolveDefaultGuard(): ZipBombGuard
    {
        if (function_exists('config')) {
            try {
                $raw = config('zip');
                if (is_array($raw)) {
                    /** @var array<string, int|float|string|null> $config */
                    $config = $raw;

                    return ZipBombGuard::fromConfig($config);
                }
            } catch (\Throwable) {
                // config helper may not be available outside Laravel
            }
        }

        return new ZipBombGuard();
    }
}
