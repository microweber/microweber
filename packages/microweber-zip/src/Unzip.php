<?php

declare(strict_types=1);

namespace MicroweberPackages\Zip;

use MicroweberPackages\Zip\Contracts\FileAllowanceCheckerInterface;
use MicroweberPackages\Zip\Exceptions\InvalidArchiveException;
use MicroweberPackages\Zip\Exceptions\UnsafePathException;
use MicroweberPackages\Zip\Exceptions\ZipBombException;
use MicroweberPackages\Zip\Exceptions\ZipException;
use MicroweberPackages\Zip\Support\SafePath;
use MicroweberPackages\Zip\Support\ZipBombGuard;

/**
 * High-level unzip helper used by module/template upload and package install.
 *
 * API is compatible with the legacy MicroweberPackages\Utils\Zip\Unzip class:
 *   $unzip = new Unzip();
 *   $files = $unzip->extract($zipFile, $targetDir, $preserveFilepath);
 *
 * Internally uses ZipArchiveExtractor so zip-bomb protection and path safety
 * are always applied.
 */
class Unzip
{
    /** @var list<string> */
    private array $info = [];

    /** @var list<string> */
    private array $error = [];

    private int $applyChmod = 0755;

    /** @var list<string> */
    private array $skipDirs = ['__MACOSX'];

    /** @var list<string>|null */
    private ?array $allowExtensions = null;

    private ZipBombGuard $bombGuard;

    private ?FileAllowanceCheckerInterface $allowanceChecker = null;

    public function __construct(?ZipBombGuard $bombGuard = null)
    {
        $this->bombGuard = $bombGuard ?? $this->resolveDefaultGuard();
    }

    public function setBombGuard(ZipBombGuard $guard): self
    {
        $this->bombGuard = $guard;

        return $this;
    }

    public function setAllowanceChecker(FileAllowanceCheckerInterface $checker): self
    {
        $this->allowanceChecker = $checker;

        return $this;
    }

    /**
     * Restrict extracted files to these extensions (null = all).
     *
     * @param list<string>|null $ext
     */
    public function allow(?array $ext = null): void
    {
        $this->allowExtensions = $ext;
    }

    /**
     * Extract all files from the archive into $targetDir.
     *
     * @return list<string>|array{error: string}|false
     *         List of extracted absolute paths on success,
     *         ['error' => message] on recoverable failure,
     *         false when the archive is empty / unreadable.
     */
    public function extract(string $zipFile, ?string $targetDir = null, bool $preserveFilepath = true): array|false
    {
        $this->info = [];
        $this->error = [];

        if (!is_file($zipFile) || !is_readable($zipFile)) {
            $this->set_error('ZIP file not found or not readable: ' . $zipFile);

            return ['error' => 'ZIP file not found or not readable.'];
        }

        $targetDir = $targetDir !== null && $targetDir !== ''
            ? SafePath::normalize($targetDir, true)
            : SafePath::normalize(dirname($zipFile), true);

        try {
            SafePath::mkdirRecursive(rtrim($targetDir, '/'), $this->applyChmod);
        } catch (\Throwable $e) {
            $this->set_error('Destination path is not writable: ' . $e->getMessage());

            return ['error' => 'Destination path is not writable.'];
        }

        try {
            $extractor = new ZipArchiveExtractor($zipFile, $this->bombGuard, $this->allowanceChecker, true);
        } catch (InvalidArchiveException $e) {
            $this->set_error($e->getMessage());

            return ['error' => $e->getMessage()];
        }

        if (!$extractor->isOpened()) {
            $this->set_error('Unable to open zip archive.');

            return ['error' => 'Unable to open zip archive.'];
        }

        // Extension filter: enable allowed-files check only when extensions are restricted
        if (is_array($this->allowExtensions) && $this->allowExtensions !== []) {
            $allowed = array_map('strtolower', $this->allowExtensions);
            $extractor->setAllowanceChecker(new class ($allowed) implements FileAllowanceCheckerInterface {
                /** @param list<string> $allowed */
                public function __construct(private readonly array $allowed)
                {
                }

                public function isAllowed(string $entryName): bool
                {
                    if (str_ends_with($entryName, '/')) {
                        return true;
                    }
                    $ext = strtolower(pathinfo($entryName, PATHINFO_EXTENSION));

                    return $ext === '' || in_array($ext, $this->allowed, true);
                }
            });
            $extractor->setAllowedFilesCheck(true);
        }

        try {
            $entries = $extractor->listEntries();
            $fileLocations = [];
            $maxPathLength = $this->bombGuard->getMaxPathLength();

            // Pre-validate bomb limits
            $this->bombGuard->validateArchive($extractor->zipInstance);

            foreach ($entries as $entryName) {
                // Detect directories BEFORE normalizing (normalize strips trailing slashes).
                $isDirectory = str_ends_with(str_replace('\\', '/', $entryName), '/');
                $normalized = SafePath::normalize($entryName, false);

                // Skip known junk directories
                $top = explode('/', $normalized)[0] ?? '';
                if (in_array($top, $this->skipDirs, true) || $top === '.DS_Store') {
                    continue;
                }

                if ($isDirectory || $normalized === '') {
                    try {
                        if ($normalized !== '') {
                            SafePath::assertSafeEntry($normalized, $maxPathLength);
                            if ($preserveFilepath) {
                                SafePath::mkdirRecursive($targetDir . $normalized, $this->applyChmod);
                            }
                        }
                    } catch (UnsafePathException) {
                        continue;
                    }
                    continue;
                }

                try {
                    SafePath::assertSafeEntry($normalized, $maxPathLength);
                } catch (UnsafePathException) {
                    $this->set_debug('Skipped unsafe entry: ' . $normalized);
                    continue;
                }

                $extension = strtolower(pathinfo($normalized, PATHINFO_EXTENSION));
                if (is_array($this->allowExtensions)
                    && $this->allowExtensions !== []
                    && $extension !== ''
                    && !in_array($extension, array_map('strtolower', $this->allowExtensions), true)
                ) {
                    continue;
                }

                $relative = $preserveFilepath ? $normalized : basename($normalized);

                try {
                    $targetFile = SafePath::resolveTarget($targetDir, $relative);
                } catch (UnsafePathException) {
                    continue;
                }

                $parent = dirname($targetFile);
                if (!is_dir($parent)) {
                    SafePath::mkdirRecursive($parent, $this->applyChmod);
                }

                $contents = $extractor->zipInstance->getFromName($entryName);
                if ($contents === false) {
                    // try by index walk
                    continue;
                }

                file_put_contents($targetFile, $contents);
                if ($this->applyChmod > 0) {
                    @chmod($targetFile, $this->applyChmod);
                }

                $fileLocations[] = $targetFile;
                $this->set_debug('Extracted: ' . $relative);
            }

            $extractor->close();

            if ($fileLocations === []) {
                $this->set_error('ZIP folder was empty.');

                return false;
            }

            return array_values(array_unique($fileLocations));
        } catch (ZipBombException $e) {
            $extractor->close();
            $this->set_error($e->getMessage());

            return ['error' => $e->getMessage()];
        } catch (ZipException $e) {
            $extractor->close();
            $this->set_error($e->getMessage());

            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Legacy alias used by some callers — native ZipArchive extraction.
     *
     * @return list<string>
     */
    public function native_unzip(string $zipFile, ?string $targetDir = null, bool $preserveFilepath = true): array
    {
        $result = $this->extract($zipFile, $targetDir, $preserveFilepath);

        if ($result === false || isset($result['error'])) {
            return [];
        }

        /** @var list<string> $result */
        return $result;
    }

    public function set_debug(string $string): void
    {
        $this->info[] = $string;
    }

    public function set_error(string $string): void
    {
        $this->error[] = $string;
    }

    public function error_string(string $open = '<p>', string $close = '</p>'): string
    {
        if ($this->error === []) {
            return '';
        }

        return $open . implode($close . $open, $this->error) . $close;
    }

    public function debug_string(string $open = '<p>', string $close = '</p>'): string
    {
        if ($this->info === []) {
            return '';
        }

        return $open . implode($close . $open, $this->info) . $close;
    }

    /**
     * @return list<string>
     */
    public function getErrors(): array
    {
        return $this->error;
    }

    /**
     * @return list<string>
     */
    public function getDebug(): array
    {
        return $this->info;
    }

    public function close(): void
    {
        // No open file handles held on this class after extract() completes.
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
            }
        }

        return new ZipBombGuard();
    }
}
