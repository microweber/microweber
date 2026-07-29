<?php

declare(strict_types=1);

namespace MicroweberPackages\Zip;

use DirectoryIterator;
use MicroweberPackages\Zip\Exceptions\ZipException;
use MicroweberPackages\Zip\Support\SafePath;
use ZipArchive;

/**
 * Create and manage a Zip archive.
 *
 * Modernised from the legacy Grandt PHPZip Zip class:
 *  - No die() / exit() calls — throws ZipException instead
 *  - Typed properties and return types
 *  - Safe temp-file handling with cleanup
 *  - Primary storage via ZipArchive for large files
 *
 * Inspired by CreateZipFile by Rochak Chauhan and the PKWARE APPNOTE.
 * Original author: A. Grandt (LGPL).
 */
class Zip
{
    public const VERSION = '2.0.0';

    private const ZIP_LOCAL_FILE_HEADER = "\x50\x4b\x03\x04";
    private const ZIP_CENTRAL_FILE_HEADER = "\x50\x4b\x01\x02";
    private const ZIP_END_OF_CENTRAL_DIRECTORY = "\x50\x4b\x05\x06\x00\x00\x00\x00";

    private const EXT_FILE_ATTR_DIR = "\x10\x00\xFF\x41";
    private const EXT_FILE_ATTR_FILE = "\x00\x00\xFF\x81";

    private const ATTR_VERSION_TO_EXTRACT = "\x14\x00";
    private const ATTR_MADE_BY_VERSION = "\x1E\x03";

    private int $zipMemoryThreshold = 1_048_576;

    private ?string $zipData = null;

    /** @var resource|null */
    private $zipFile = null;

    private ?string $zipComment = null;

    /** @var list<string> */
    private array $cdRec = [];

    private int $offset = 0;

    private bool $isFinalized = false;

    private bool $addExtraField = true;

    private int $streamChunkSize = 65_536;

    private string $streamFilePath = '';

    private int $streamTimeStamp = 0;

    private ?string $streamFileComment = null;

    private ?string $streamFile = null;

    /** @var resource|null */
    private $streamData = null;

    private int $streamFileLength = 0;

    public function __construct(bool $useZipFile = false)
    {
        if ($useZipFile) {
            $tmp = tmpfile();
            if ($tmp === false) {
                throw new ZipException('Unable to create temporary zip file handle.');
            }
            $this->zipFile = $tmp;
        } else {
            $this->zipData = '';
        }
    }

    public function __destruct()
    {
        if (is_resource($this->zipFile)) {
            fclose($this->zipFile);
            $this->zipFile = null;
        }
        $this->zipData = null;
    }

    public function setExtraField(bool $setExtraField = true): void
    {
        $this->addExtraField = $setExtraField;
    }

    public function setComment(?string $newComment = null): bool
    {
        if ($this->isFinalized) {
            return false;
        }
        $this->zipComment = $newComment;

        return true;
    }

    /**
     * Write zip data to a permanent file (overwrites if exists).
     */
    public function setZipFile(string $fileName): bool
    {
        if (is_file($fileName)) {
            unlink($fileName);
        }

        $fd = fopen($fileName, 'x+b');
        if ($fd === false) {
            throw new ZipException(sprintf('Unable to create zip file: %s', $fileName));
        }

        if (is_resource($this->zipFile)) {
            rewind($this->zipFile);
            while (!feof($this->zipFile)) {
                $chunk = fread($this->zipFile, max(1, $this->streamChunkSize));
                if ($chunk === false) {
                    break;
                }
                fwrite($fd, $chunk);
            }
            fclose($this->zipFile);
        } else {
            fwrite($fd, (string) $this->zipData);
            $this->zipData = null;
        }
        $this->zipFile = $fd;

        return true;
    }

    public function addDirectory(string $directoryPath, int $timestamp = 0, ?string $fileComment = null): bool
    {
        if ($this->isFinalized) {
            return false;
        }

        $directoryPath = str_replace('\\', '/', $directoryPath);
        $directoryPath = rtrim($directoryPath, '/');

        if ($directoryPath === '') {
            return false;
        }

        $this->buildZipEntry(
            $directoryPath . '/',
            $fileComment,
            "\x00\x00",
            "\x00\x00",
            $timestamp,
            "\x00\x00\x00\x00",
            0,
            0,
            self::EXT_FILE_ATTR_DIR
        );

        return true;
    }

    /**
     * Add file contents (or a stream resource) to the archive.
     *
     * @param string|resource $data
     */
    public function addFile(
        mixed $data,
        string $filePath,
        int $timestamp = 0,
        ?string $fileComment = null,
        bool $compress = true,
    ): bool {
        if ($this->isFinalized) {
            return false;
        }

        if (is_resource($data) && get_resource_type($data) === 'stream') {
            $this->addLargeFile($data, $filePath, $timestamp, $fileComment);

            return true;
        }

        if (!is_string($data)) {
            throw new ZipException('addFile() expects string data or a stream resource.');
        }

        $gzType = "\x08\x00";
        $gpFlags = "\x00\x00";
        $dataLength = strlen($data);
        $fileCRC32 = pack('V', crc32($data));
        $gzData = $data;
        $gzLength = $dataLength;

        if ($compress) {
            $gzTmp = gzcompress($data);
            if ($gzTmp !== false) {
                $gzData = substr(substr($gzTmp, 0, strlen($gzTmp) - 4), 2);
                $gzLength = strlen($gzData);
            }
        }

        if ($gzLength >= $dataLength) {
            $gzLength = $dataLength;
            $gzData = $data;
            $gzType = "\x00\x00";
            $gpFlags = "\x00\x00";
        }

        if (!is_resource($this->zipFile) && ($this->offset + $gzLength) > $this->zipMemoryThreshold) {
            $this->zipflush();
        }

        $this->buildZipEntry(
            $filePath,
            $fileComment,
            $gpFlags,
            $gzType,
            $timestamp,
            $fileCRC32,
            $gzLength,
            $dataLength,
            self::EXT_FILE_ATTR_FILE
        );
        $this->zipwrite($gzData);

        return true;
    }

    /**
     * @param array<string, string> $addedFiles
     */
    public function addDirectoryContent(
        string $realPath,
        string $zipPath,
        bool $recursive = true,
        bool $followSymlinks = true,
        array &$addedFiles = [],
    ): void {
        if (!file_exists($realPath)) {
            return;
        }

        $real = realpath($realPath);
        if ($real === false || isset($addedFiles[$real])) {
            return;
        }

        if (is_dir($realPath)) {
            $this->addDirectory($zipPath);
        }

        $addedFiles[$real] = $zipPath;

        if (!is_dir($realPath)) {
            if (is_file($realPath)) {
                $this->addLargeFile($realPath, $zipPath);
            }

            return;
        }

        $iter = new DirectoryIterator($realPath);
        foreach ($iter as $file) {
            if ($file->isDot()) {
                continue;
            }
            $newRealPath = $file->getPathname();
            $newZipPath = self::pathJoin($zipPath, $file->getFilename());

            if (!file_exists($newRealPath)) {
                continue;
            }
            if ($followSymlinks === false && is_link($newRealPath)) {
                continue;
            }

            if ($file->isFile()) {
                $rp = realpath($newRealPath);
                if ($rp !== false) {
                    $addedFiles[$rp] = $newZipPath;
                }
                $this->addLargeFile($newRealPath, $newZipPath);
            } elseif ($recursive) {
                $this->addDirectoryContent($newRealPath, $newZipPath, $recursive, $followSymlinks, $addedFiles);
            } else {
                $this->addDirectory($newZipPath);
            }
        }
    }

    /**
     * @param string|resource $dataFile
     */
    public function addLargeFile(
        mixed $dataFile,
        string $filePath,
        int $timestamp = 0,
        ?string $fileComment = null,
    ): bool {
        if ($this->isFinalized) {
            return false;
        }

        if (is_string($dataFile) && is_file($dataFile)) {
            $this->processFile($dataFile, $filePath, $timestamp, $fileComment);
        } elseif (is_resource($dataFile) && get_resource_type($dataFile) === 'stream') {
            $this->openStream($filePath, $timestamp, $fileComment);
            while (!feof($dataFile)) {
                $chunk = fread($dataFile, max(1, $this->streamChunkSize));
                if ($chunk === false || $chunk === '') {
                    break;
                }
                $this->addStreamData($chunk);
            }
            $this->closeStream();
        }

        return true;
    }

    public function openStream(string $filePath, int $timestamp = 0, ?string $fileComment = null): bool
    {
        if ($this->isFinalized) {
            return false;
        }

        $this->zipflush();

        if ($this->streamFilePath !== '') {
            $this->closeStream();
        }

        $tmp = tempnam(sys_get_temp_dir(), 'Zip');
        if ($tmp === false) {
            throw new ZipException('Unable to create temporary stream file.');
        }

        $handle = fopen($tmp, 'wb');
        if ($handle === false) {
            throw new ZipException('Unable to open temporary stream file for writing.');
        }

        $this->streamFile = $tmp;
        $this->streamData = $handle;
        $this->streamFilePath = $filePath;
        $this->streamTimeStamp = $timestamp;
        $this->streamFileComment = $fileComment;
        $this->streamFileLength = 0;

        return true;
    }

    public function addStreamData(string $data): int|false
    {
        if ($this->isFinalized || $this->streamFilePath === '' || !is_resource($this->streamData)) {
            return false;
        }

        $length = fwrite($this->streamData, $data);
        if ($length === false) {
            throw new ZipException('Failed writing stream data to temporary file.');
        }
        $this->streamFileLength += $length;

        return $length;
    }

    public function closeStream(): bool
    {
        if ($this->isFinalized || $this->streamFilePath === '') {
            return false;
        }

        if (is_resource($this->streamData)) {
            fflush($this->streamData);
            fclose($this->streamData);
        }

        $streamFile = $this->streamFile;
        if ($streamFile !== null) {
            $this->processFile(
                $streamFile,
                $this->streamFilePath,
                $this->streamTimeStamp,
                $this->streamFileComment
            );
            @unlink($streamFile);
        }

        $this->streamData = null;
        $this->streamFilePath = '';
        $this->streamTimeStamp = 0;
        $this->streamFileComment = null;
        $this->streamFileLength = 0;
        $this->streamFile = null;

        return true;
    }

    public function finalize(): bool
    {
        if ($this->isFinalized) {
            return false;
        }

        if ($this->streamFilePath !== '') {
            $this->closeStream();
        }

        $cd = implode('', $this->cdRec);
        $cdRecSize = pack('v', count($this->cdRec));
        $cdRec = $cd . self::ZIP_END_OF_CENTRAL_DIRECTORY
            . $cdRecSize . $cdRecSize
            . pack('VV', strlen($cd), $this->offset);

        if ($this->zipComment !== null && $this->zipComment !== '') {
            $cdRec .= pack('v', strlen($this->zipComment)) . $this->zipComment;
        } else {
            $cdRec .= "\x00\x00";
        }

        $this->zipwrite($cdRec);
        $this->isFinalized = true;
        $this->cdRec = [];

        return true;
    }

    /**
     * @return resource
     */
    public function getZipFile()
    {
        if (!$this->isFinalized) {
            $this->finalize();
        }
        $this->zipflush();

        if (!is_resource($this->zipFile)) {
            throw new ZipException('Zip file handle is not available.');
        }

        rewind($this->zipFile);

        return $this->zipFile;
    }

    public function getZipData(): string
    {
        if (!$this->isFinalized) {
            $this->finalize();
        }
        if (!is_resource($this->zipFile)) {
            return (string) $this->zipData;
        }

        rewind($this->zipFile);
        $filestat = fstat($this->zipFile);
        $size = is_array($filestat) ? (int) $filestat['size'] : 0;
        if ($size <= 0) {
            return '';
        }
        $data = fread($this->zipFile, $size);

        return $data === false ? '' : $data;
    }

    /**
     * Write finalized archive bytes to a filesystem path.
     */
    public function saveTo(string $fileName): bool
    {
        $data = $this->getZipData();
        $written = file_put_contents($fileName, $data);

        return $written !== false;
    }

    public function getArchiveSize(): int
    {
        if (!is_resource($this->zipFile)) {
            return strlen((string) $this->zipData);
        }
        $filestat = fstat($this->zipFile);

        return is_array($filestat) ? (int) $filestat['size'] : 0;
    }

    public static function pathJoin(string $dir, string $file): string
    {
        return SafePath::join($dir, $file);
    }

    public static function getRelativePath(string $path): string
    {
        return SafePath::normalize($path, false);
    }

    private function processFile(string $dataFile, string $filePath, int $timestamp = 0, ?string $fileComment = null): void
    {
        if ($this->isFinalized) {
            return;
        }

        $tempzip = tempnam(sys_get_temp_dir(), 'ZipStream');
        if ($tempzip === false) {
            throw new ZipException('Unable to create temporary zip for large file processing.');
        }

        try {
            $zip = new ZipArchive();
            $openResult = $zip->open($tempzip, ZipArchive::OVERWRITE | ZipArchive::CREATE);
            if ($openResult !== true) {
                // tempnam creates an empty file; reopen with CREATE
                $openResult = $zip->open($tempzip, ZipArchive::CREATE);
            }
            if ($openResult !== true) {
                throw new ZipException('Unable to open temporary ZipArchive for large file.');
            }
            $zip->addFile($dataFile, 'file');
            $zip->close();

            $fileHandle = fopen($tempzip, 'rb');
            if ($fileHandle === false) {
                throw new ZipException('Unable to read temporary ZipArchive.');
            }

            $stats = fstat($fileHandle);
            $size = is_array($stats) ? (int) $stats['size'] : 0;
            $eof = $size - 72;

            fseek($fileHandle, 6);
            $gpFlags = (string) fread($fileHandle, 2);
            $gzType = (string) fread($fileHandle, 2);
            fread($fileHandle, 4);
            $fileCRC32 = (string) fread($fileHandle, 4);
            $v = unpack('Vval', (string) fread($fileHandle, 4));
            $gzLength = is_array($v) ? (int) $v['val'] : 0;
            $v = unpack('Vval', (string) fread($fileHandle, 4));
            $dataLength = is_array($v) ? (int) $v['val'] : 0;

            $this->buildZipEntry(
                $filePath,
                $fileComment,
                $gpFlags,
                $gzType,
                $timestamp,
                $fileCRC32,
                $gzLength,
                $dataLength,
                self::EXT_FILE_ATTR_FILE
            );

            fseek($fileHandle, 34);
            $pos = 34;

            while (!feof($fileHandle) && $pos < $eof) {
                $datalen = $this->streamChunkSize;
                if ($pos + $this->streamChunkSize > $eof) {
                    $datalen = $eof - $pos;
                }
                if ($datalen <= 0) {
                    break;
                }
                $data = fread($fileHandle, $datalen);
                if ($data === false) {
                    break;
                }
                $pos += $datalen;
                $this->zipwrite($data);
            }

            fclose($fileHandle);
        } finally {
            if (is_file($tempzip)) {
                @unlink($tempzip);
            }
        }
    }

    private function getDosTime(int $timestamp = 0): string
    {
        $oldTZ = date_default_timezone_get();
        date_default_timezone_set('UTC');
        $date = $timestamp === 0 ? getdate() : getdate($timestamp);
        date_default_timezone_set($oldTZ);

        if ($date['year'] >= 1980) {
            return pack(
                'V',
                (($date['mday'] + ($date['mon'] << 5) + (($date['year'] - 1980) << 9)) << 16)
                | (($date['seconds'] >> 1) + ($date['minutes'] << 5) + ($date['hours'] << 11))
            );
        }

        return "\x00\x00\x00\x00";
    }

    private function buildZipEntry(
        string $filePath,
        ?string $fileComment,
        string $gpFlags,
        string $gzType,
        int $timestamp,
        string $fileCRC32,
        int $gzLength,
        int $dataLength,
        string $extFileAttr,
    ): void {
        $filePath = str_replace('\\', '/', $filePath);
        $fileCommentLength = ($fileComment === null || $fileComment === '') ? 0 : strlen($fileComment);
        $timestamp = $timestamp === 0 ? time() : $timestamp;

        $dosTime = $this->getDosTime($timestamp);
        $tsPack = pack('V', $timestamp);
        $ux = "\x75\x78\x0B\x00\x01\x04\xE8\x03\x00\x00\x04\x00\x00\x00\x00";

        if (strlen($gpFlags) !== 2) {
            $gpFlags = "\x00\x00";
        }

        $isFileUTF8 = mb_check_encoding($filePath, 'UTF-8') && !mb_check_encoding($filePath, 'ASCII');
        $isCommentUTF8 = $fileComment !== null && $fileComment !== ''
            && mb_check_encoding($fileComment, 'UTF-8')
            && !mb_check_encoding($fileComment, 'ASCII');

        if ($isFileUTF8 || $isCommentUTF8) {
            $flag = 0;
            $gpFlagsV = unpack('vflags', $gpFlags);
            if (is_array($gpFlagsV) && isset($gpFlagsV['flags'])) {
                $flag = (int) $gpFlagsV['flags'];
            }
            $gpFlags = pack('v', $flag | (1 << 11));
        }

        $header = $gpFlags . $gzType . $dosTime . $fileCRC32
            . pack('VVv', $gzLength, $dataLength, strlen($filePath));

        $zipEntry = self::ZIP_LOCAL_FILE_HEADER;
        $zipEntry .= self::ATTR_VERSION_TO_EXTRACT;
        $zipEntry .= $header;
        $zipEntry .= pack('v', ($this->addExtraField ? 28 : 0));
        $zipEntry .= $filePath;
        if ($this->addExtraField) {
            $zipEntry .= "\x55\x54\x09\x00\x03" . $tsPack . $tsPack . $ux;
        }
        $this->zipwrite($zipEntry);

        $cdEntry = self::ZIP_CENTRAL_FILE_HEADER;
        $cdEntry .= self::ATTR_MADE_BY_VERSION;
        $cdEntry .= ($dataLength === 0 ? "\x0A\x00" : self::ATTR_VERSION_TO_EXTRACT);
        $cdEntry .= $header;
        $cdEntry .= pack('v', ($this->addExtraField ? 24 : 0));
        $cdEntry .= pack('v', $fileCommentLength);
        $cdEntry .= "\x00\x00";
        $cdEntry .= "\x00\x00";
        $cdEntry .= $extFileAttr;
        $cdEntry .= pack('V', $this->offset);
        $cdEntry .= $filePath;
        if ($this->addExtraField) {
            $cdEntry .= "\x55\x54\x05\x00\x03" . $tsPack . $ux;
        }
        if ($fileComment !== null && $fileComment !== '') {
            $cdEntry .= $fileComment;
        }

        $this->cdRec[] = $cdEntry;
        $this->offset += strlen($zipEntry) + $gzLength;
    }

    private function zipwrite(string $data): void
    {
        if (!is_resource($this->zipFile)) {
            $this->zipData = ((string) $this->zipData) . $data;
        } else {
            fwrite($this->zipFile, $data);
            fflush($this->zipFile);
        }
    }

    private function zipflush(): void
    {
        if (!is_resource($this->zipFile)) {
            $tmp = tmpfile();
            if ($tmp === false) {
                throw new ZipException('Unable to flush zip data to temporary file.');
            }
            fwrite($tmp, (string) $this->zipData);
            $this->zipFile = $tmp;
            $this->zipData = null;
        }
    }
}
