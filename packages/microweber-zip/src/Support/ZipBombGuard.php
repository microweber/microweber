<?php

declare(strict_types=1);

namespace MicroweberPackages\Zip\Support;

use MicroweberPackages\Zip\Exceptions\ZipBombException;
use ZipArchive;

/**
 * Zip-bomb protection: file count, total uncompressed size, per-file size,
 * and compression ratio checks against a ZipArchive before extraction.
 *
 * All limits of 0 mean "disabled" for that individual check.
 */
final class ZipBombGuard
{
    public function __construct(
        private readonly int $maxFiles = 10_000,
        private readonly int $maxTotalUncompressedBytes = 1_073_741_824,
        private readonly int $maxSingleFileUncompressedBytes = 536_870_912,
        private readonly float $maxCompressionRatio = 100.0,
        private readonly int $maxPathLength = 512,
    ) {
    }

    /**
     * Build a guard from a config array (config/zip.php shape).
     *
     * @param array<string, int|float|string|null> $config
     */
    public static function fromConfig(array $config): self
    {
        return new self(
            maxFiles: self::toInt($config['max_files'] ?? 10_000, 10_000),
            maxTotalUncompressedBytes: self::toInt($config['max_total_uncompressed_bytes'] ?? 1_073_741_824, 1_073_741_824),
            maxSingleFileUncompressedBytes: self::toInt($config['max_single_file_uncompressed_bytes'] ?? 536_870_912, 536_870_912),
            maxCompressionRatio: self::toFloat($config['max_compression_ratio'] ?? 100.0, 100.0),
            maxPathLength: self::toInt($config['max_path_length'] ?? 512, 512),
        );
    }

    private static function toInt(int|float|string|null $value, int $default): int
    {
        if ($value === null || $value === '') {
            return $default;
        }

        return (int) $value;
    }

    private static function toFloat(int|float|string|null $value, float $default): float
    {
        if ($value === null || $value === '') {
            return $default;
        }

        return (float) $value;
    }

    public function getMaxPathLength(): int
    {
        return $this->maxPathLength;
    }

    /**
     * Scan the whole archive and throw if any limit is exceeded.
     *
     * @throws ZipBombException
     */
    public function validateArchive(ZipArchive $zip): void
    {
        $numFiles = $zip->numFiles;

        if ($this->maxFiles > 0 && $numFiles > $this->maxFiles) {
            throw new ZipBombException(
                sprintf(
                    'Archive has %d files which exceeds the limit of %d.',
                    $numFiles,
                    $this->maxFiles
                )
            );
        }

        $totalUncompressed = 0;

        for ($i = 0; $i < $numFiles; ++$i) {
            $stat = $zip->statIndex($i);
            if ($stat === false) {
                continue;
            }

            $this->validateEntry($stat, $totalUncompressed);

            $totalUncompressed += (int) ($stat['size'] ?? 0);
        }

        if ($this->maxTotalUncompressedBytes > 0 && $totalUncompressed > $this->maxTotalUncompressedBytes) {
            throw new ZipBombException(
                sprintf(
                    'Archive total uncompressed size %d bytes exceeds the limit of %d bytes.',
                    $totalUncompressed,
                    $this->maxTotalUncompressedBytes
                )
            );
        }
    }

    /**
     * Validate a single ZipArchive::statIndex() result.
     *
     * @param array{name?: string, size?: int, comp_size?: int, ...} $stat
     *
     * @throws ZipBombException
     */
    public function validateEntry(array $stat, int $runningTotalUncompressed = 0): void
    {
        $name = (string) ($stat['name'] ?? '');
        $uncompressed = (int) ($stat['size'] ?? 0);
        $compressed = (int) ($stat['comp_size'] ?? 0);

        if ($this->maxPathLength > 0 && strlen($name) > $this->maxPathLength) {
            throw new ZipBombException(
                sprintf('Archive entry path length exceeds limit of %d.', $this->maxPathLength)
            );
        }

        if ($this->maxSingleFileUncompressedBytes > 0 && $uncompressed > $this->maxSingleFileUncompressedBytes) {
            throw new ZipBombException(
                sprintf(
                    'Archive entry "%s" uncompressed size %d exceeds the single-file limit of %d bytes.',
                    $name,
                    $uncompressed,
                    $this->maxSingleFileUncompressedBytes
                )
            );
        }

        if ($this->maxCompressionRatio > 0.0 && $compressed > 0 && $uncompressed > 0) {
            $ratio = $uncompressed / $compressed;
            if ($ratio > $this->maxCompressionRatio) {
                throw new ZipBombException(
                    sprintf(
                        'Archive entry "%s" compression ratio %.1f:1 exceeds the limit of %.1f:1.',
                        $name,
                        $ratio,
                        $this->maxCompressionRatio
                    )
                );
            }
        }

        if ($this->maxTotalUncompressedBytes > 0) {
            $projected = $runningTotalUncompressed + $uncompressed;
            if ($projected > $this->maxTotalUncompressedBytes) {
                throw new ZipBombException(
                    sprintf(
                        'Archive would exceed total uncompressed size limit of %d bytes (projected %d).',
                        $this->maxTotalUncompressedBytes,
                        $projected
                    )
                );
            }
        }
    }
}
