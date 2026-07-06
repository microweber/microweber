<?php

namespace MicroweberPackages\Filesystem;

use Illuminate\Support\Str;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use FilesystemIterator;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Unified filesystem service for Microweber.
 *
 * Modernised from the legacy MicroweberPackages\Utils\System\Files class
 * and the laravel-helper-functions filesystem.php global functions.
 *
 * Changes vs. legacy code:
 *  - No static variables (memory-leak source).
 *  - No exit() / die() calls — returns proper Response objects instead.
 *  - No ob_flush() / ob_end_clean() — uses Symfony BinaryFileResponse.
 *  - All methods are instance methods on a singleton service.
 */
class FilesystemService
{
    // ------------------------------------------------------------------
    //  Directory Operations
    // ------------------------------------------------------------------

    /**
     * Copy a directory recursively.
     *
     * @return string[] List of copied destination file paths.
     */
    public function copyDirectory(string $source, string $destination): array
    {
        $copies = [];

        if (is_dir($source)) {
            if (!is_dir($destination)) {
                @mkdir($destination, 0755, true);
            }
            $directory = dir($source);
            while (false !== ($entry = $directory->read())) {
                if ($entry === '.' || $entry === '..') {
                    continue;
                }
                $srcPath = $source . DIRECTORY_SEPARATOR . $entry;
                $dstPath = $destination . DIRECTORY_SEPARATOR . $entry;
                if (is_dir($srcPath)) {
                    $copies = array_merge($copies, $this->copyDirectory($srcPath, $dstPath));
                } else {
                    copy($srcPath, $dstPath);
                    $copies[] = $dstPath;
                }
            }
            $directory->close();
        } elseif (is_file($source)) {
            copy($source, $destination);
            $copies[] = $destination;
        }

        return $copies;
    }

    /**
     * Remove a directory and all its contents recursively.
     */
    public function removeDirectory(string $dirPath): void
    {
        if (!is_dir($dirPath)) {
            return;
        }
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dirPath, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($iterator as $path) {
            $path->isDir() && !$path->isLink()
                ? @rmdir($path->getPathname())
                : @unlink($path->getPathname());
        }
        @rmdir($dirPath);
    }

    /**
     * Remove a directory recursively (legacy-compatible version).
     *
     * @param bool $empty When true, keep the top-level directory.
     */
    public function removeDirRecursive(string $directory, bool $empty = true): bool
    {
        $directory = rtrim($directory, DIRECTORY_SEPARATOR);

        if (!is_dir($directory) || !is_readable($directory)) {
            return false;
        }

        $handle = opendir($directory);
        while (false !== ($item = readdir($handle))) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $directory . DIRECTORY_SEPARATOR . $item;
            if (is_dir($path)) {
                $this->removeDirRecursive($path, $empty);
            } else {
                @unlink($path);
            }
        }
        closedir($handle);

        if (!$empty) {
            @rmdir($directory);
        }

        return true;
    }

    /**
     * Create a directory recursively.
     */
    public function mkdirRecursive(string $pathname): bool
    {
        if ($pathname === '') {
            return false;
        }
        if (is_dir($pathname)) {
            return true;
        }
        $parent = dirname($pathname);
        if (!is_dir($parent)) {
            $this->mkdirRecursive($parent);
        }
        return @mkdir($pathname, 0755);
    }

    /**
     * Compute MD5 checksums for all files in a directory.
     *
     * @return array<string, string> md5 => normalised file path
     */
    public function md5Dir(string $path): array
    {
        if (!file_exists($path)) {
            throw new \RuntimeException("Directory does not exist: {$path}");
        }

        $items = [];
        $directoryIterator = new \DirectoryIterator($path);
        foreach ($directoryIterator as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }
            $filePath = $fileInfo->getPathname();
            if ($fileInfo->isFile()) {
                $md = md5_file($filePath);
                $items[$md] = $this->normalizePath($filePath, false);
            } elseif ($fileInfo->isDir()) {
                $items = array_merge($items, $this->md5Dir($filePath));
            }
        }
        return $items;
    }

    /**
     * Create a directory map (array representation).
     *
     * @param int  $directoryDepth 0 = fully recursive
     * @param bool $hidden         Include hidden files
     * @param bool $fullPath       Return full paths instead of just filenames
     *
     * @return array|false
     */
    public function directoryMap(
        string $sourceDir,
        int $directoryDepth = 0,
        bool $hidden = false,
        bool $fullPath = false
    ): array|false {
        $fp = @opendir($sourceDir);
        if ($fp === false) {
            return false;
        }

        $filedata  = [];
        $newDepth  = $directoryDepth - 1;
        $sourceDir = rtrim($sourceDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        while (false !== ($file = readdir($fp))) {
            if (!trim($file, '.') || (!$hidden && $file[0] === '.')) {
                continue;
            }

            if (($directoryDepth < 1 || $newDepth > 0) && @is_dir($sourceDir . $file)) {
                $filedata[$file] = $this->directoryMap(
                    $sourceDir . $file . DIRECTORY_SEPARATOR,
                    $newDepth,
                    $hidden,
                    $fullPath
                );
            } else {
                $filedata[] = $fullPath ? $sourceDir . $file : $file;
            }
        }

        closedir($fp);
        return $filedata;
    }

    // ------------------------------------------------------------------
    //  Recursive Glob
    // ------------------------------------------------------------------

    /**
     * Recursive glob().
     *
     * @return array|false
     */
    public function rglob(string $pattern = '*', int $flags = 0, string $path = ''): array|false
    {
        if (!$path && ($dir = dirname($pattern)) !== '.') {
            $dir = ($dir === '\\' || $dir === '/') ? '' : $dir;
            return $this->rglob(basename($pattern), $flags, $dir . DIRECTORY_SEPARATOR);
        }

        if (stripos($path, '_notes') !== false
            || stripos($path, '.git') !== false
            || stripos($path, '.svn') !== false
        ) {
            return false;
        }

        $paths = glob($path . '*', GLOB_ONLYDIR | GLOB_NOSORT);
        $files = glob($path . $pattern, $flags);

        if (is_array($paths)) {
            foreach ($paths as $p) {
                if (!is_dir($p) || !is_readable($p)) {
                    continue;
                }
                $temp = $this->rglob($pattern, 0, $p . DIRECTORY_SEPARATOR);
                if (is_array($temp) && is_array($files)) {
                    $files = array_merge($files, $temp);
                } elseif (is_array($temp)) {
                    $files = $temp;
                }
            }
        }

        return $files;
    }

    // ------------------------------------------------------------------
    //  Path Utilities
    // ------------------------------------------------------------------

    /**
     * Normalize a path for the current OS, optionally adding a trailing slash.
     */
    public function normalizePath(string $path, bool $slashIt = true): string
    {
        $original = $path;
        $s        = DIRECTORY_SEPARATOR;

        $path = preg_replace('/[\/\\\]/', $s, $path);
        $path = str_replace($s . $s, $s, $path);

        if ((string) $path === '') {
            $path = $original;
        }

        if (!$slashIt) {
            $path = rtrim($path, DIRECTORY_SEPARATOR);
        } else {
            $path .= DIRECTORY_SEPARATOR;
            $path  = rtrim($path, DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR);
        }

        if (trim($path) === '' || trim($path) === '/') {
            $path = $original;
        }

        if ($slashIt) {
            $path = $path . DIRECTORY_SEPARATOR;
            $path = $this->reduceDoubleSlashes($path);
        }

        return $path;
    }

    /**
     * Remove double slashes from a string (preserving protocol slashes like http://).
     */
    public function reduceDoubleSlashes(string $str): string
    {
        return preg_replace('#([^:])//+#', '\\1/', $str);
    }

    /**
     * Get a file extension from a filename or path.
     */
    public function getFileExtension(string $pathToFile): string
    {
        $pathToFile = rtrim($pathToFile, '.');
        return substr($pathToFile, strrpos($pathToFile, '.') + 1);
    }

    /**
     * Return a filename without its extension.
     */
    public function noExt(string $filename): string
    {
        $filename = rtrim($filename, '.');
        $parts    = explode('.', $filename);
        array_pop($parts);
        return implode('.', $parts);
    }

    // ------------------------------------------------------------------
    //  Filesize
    // ------------------------------------------------------------------

    /**
     * Return a human-readable file size string.
     */
    public function fileSizeNice(int|float $size): string
    {
        $mod   = 1024;
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

        for ($i = 0; $size >= $mod && $i < count($units) - 1; ++$i) {
            $size /= $mod;
        }

        return round($size, 2) . ' ' . $units[$i];
    }

    // ------------------------------------------------------------------
    //  File Download — returns a proper Response, never calls exit()
    // ------------------------------------------------------------------

    /**
     * Return a download response for the given file.
     *
     * Replaces the legacy download_to_browser() that called exit().
     */
    public function downloadResponse(string $filename): BinaryFileResponse
    {
        if (!file_exists($filename)) {
            throw new \RuntimeException("File not found: {$filename}");
        }

        $response = new BinaryFileResponse($filename);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            basename($filename)
        );

        return $response;
    }

    // ------------------------------------------------------------------
    //  Security: extension checks, SVG sanitization
    // ------------------------------------------------------------------

    /**
     * Get list of dangerous file extensions.
     */
    public function getDangerousExtensions(): array
    {
        // Delegate to file-uploader package when available (single source of truth)
        try {
            if (function_exists('app') && app()->bound('file_uploader')) {
                $fromPackage = app('file_uploader')->validator()->getDangerousExtensions();
                if (is_array($fromPackage) && !empty($fromPackage)) {
                    return array_values(array_unique($fromPackage));
                }
            } elseif (function_exists('config') && function_exists('app') && app()->bound('config')) {
                $fromPackage = config('file-uploader.dangerous_extensions');
                if (is_array($fromPackage) && !empty($fromPackage)) {
                    return array_values(array_unique($fromPackage));
                }
            }
        } catch (\Throwable) {
            // Container not available — fall through to fallback
        }

        // Emergency fallback
        return [
            'phtml', 'php', 'php3', 'php4', 'php5', 'php6', 'php7', 'php8', 'phps', 'phtm',
            'pht', 'shtml', 'htaccess', 'asp', 'aspx', 'jsp', 'jspx', 'cgi', 'pl', 'py', 'rb',
            'sh', 'bash', 'ksh', 'csh', 'exe', 'msi', 'bat', 'cmd', 'com', 'scr', 'pif', 'vbs',
            'vbe', 'js', 'jse', 'ws', 'wsf', 'wsc', 'wsh', 'ps1', 'psm1', 'psd1', 'hta', 'jar',
            'phar', 'cfm', 'cfml', 'svg',
        ];
    }

    /**
     * Check if a file has a dangerous extension.
     */
    public function isDangerousFile(string $fileName): bool
    {
        $ext = strtolower($this->getFileExtension($fileName));
        return in_array($ext, $this->getDangerousExtensions(), true);
    }

    /**
     * Check if a file extension is in the allowed upload list.
     */
    public function isAllowedFile(string $fileName): bool
    {
        if (Str::contains($fileName, ['*', '::', '?', '"', '<', '>'])) {
            return false;
        }

        $allowed = array_merge(
            $this->getAllowedExtensionsForUpload('images', true),
            $this->getAllowedExtensionsForUpload('videos', true),
            $this->getAllowedExtensionsForUpload('audios', true),
            $this->getAllowedExtensionsForUpload('files', true),
            $this->getAllowedExtensionsForUpload('documents', true),
            $this->getAllowedExtensionsForUpload('archives', true),
        );

        $ext = strtolower($this->getFileExtension($fileName));
        return in_array($ext, $allowed, true);
    }

    /**
     * Get allowed file extensions for a given upload type.
     *
     * @param bool $returnAsArray When true, returns array; otherwise comma-separated string.
     *
     * @return array|string
     */
    public function getAllowedExtensionsForUpload(string $fileTypes = 'images', bool $returnAsArray = false): array|string
    {
        $extensionMap = [
            'images'    => 'png,gif,jpg,jpeg,tiff,bmp,webp,ico,svg',
            'img'       => 'png,gif,jpg,jpeg,tiff,bmp,webp,ico,svg',
            'image'     => 'png,gif,jpg,jpeg,tiff,bmp,webp,ico,svg',
            'media'     => 'png,gif,jpg,jpeg,tiff,bmp,webp,ico,svg',
            'audios'    => 'mp3,mp4,ogg,wav,flac',
            'audio'     => 'mp3,mp4,ogg,wav,flac',
            'videos'    => 'avi,asf,mpg,mpeg,mp4,flv,mkv,webm,ogg,ogv,3gp,3g2,wma,mov,wmv',
            'video'     => 'avi,asf,mpg,mpeg,mp4,flv,mkv,webm,ogg,ogv,3gp,3g2,wma,mov,wmv',
            'files'     => 'css,json,zip,gzip,psd,csv,7z,rar,gz,woff,woff2,ttf,oet,otf,cur,ico',
            'file'      => 'css,json,zip,gzip,psd,csv,7z,rar,gz,woff,woff2,ttf,oet,otf,cur,ico',
            'documents' => 'doc,docx,pdf,odt,rtf,txt,pps,ppt,pptx,xls,xlsx,xml',
            'doc'       => 'doc,docx,pdf,odt,rtf,txt,pps,ppt,pptx,xls,xlsx,xml',
            'archives'  => 'zip,zipx,gzip,rar,gz,7z,cbr,tar.gz',
            'arc'       => 'zip,zipx,gzip,rar,gz,7z,cbr,tar.gz',
            'arch'      => 'zip,zipx,gzip,rar,gz,7z,cbr,tar.gz',
        ];

        if ($fileTypes === 'all' || $fileTypes === '*') {
            $extensions = ['*'];
        } elseif (isset($extensionMap[$fileTypes])) {
            $extensions = explode(',', $extensionMap[$fileTypes]);
        } else {
            $extensions = explode(',', $fileTypes);
        }

        $extensions = array_unique(array_filter($extensions));

        return $returnAsArray ? $extensions : implode(',', $extensions);
    }

    // ------------------------------------------------------------------
    //  SVG Sanitization
    // ------------------------------------------------------------------

    /**
     * Sanitize an SVG string.
     */
    public function sanitizeSvg(string $dirtySVG): ?string
    {
        $sanitizer = new \enshrined\svgSanitize\Sanitizer();
        return $sanitizer->sanitize($dirtySVG);
    }

    /**
     * Check if an SVG string is valid (not tampered with malicious content).
     */
    public function checkIfSvgIsValid(string $dirtySVG): bool
    {
        $sanitizer = new \enshrined\svgSanitize\Sanitizer();

        try {
            $cleanSVG = $sanitizer->sanitize($dirtySVG);

            $replaces = ["\r", "\n", "\t", '"', "'", " ", "<path>", "</path>", "/", ">", "<"];

            // Strip XML declaration regardless of encoding case
            $cleanSVG = preg_replace('/<\?xml[^?]*\?>/i', '', $cleanSVG);
            $dirtySVG = preg_replace('/<\?xml[^?]*\?>/i', '', $dirtySVG);

            // Normalize self-closing tags to open+close for comparison
            $cleanSVG = preg_replace('/<(\w+)([^>]*)\s*\/>/i', '<$1$2></$1>', $cleanSVG);
            $dirtySVG = preg_replace('/<(\w+)([^>]*)\s*\/>/i', '<$1$2></$1>', $dirtySVG);

            $clean = strtolower(trim(str_replace($replaces, '', $cleanSVG)));
            $dirty = strtolower(trim(str_replace($replaces, '', $dirtySVG)));

            return md5($clean) === md5($dirty);
        } catch (\Exception) {
            return false;
        }
    }
}