<?php

namespace MicroweberPackages\Thumbnailer;

use MicroweberPackages\Thumbnailer\Libs\PHPImageMagician\ImageLib;
use MicroweberPackages\Thumbnailer\Support\Thumbnailer;

class ThumbnailGenerator
{
    protected string $thumbnailsDir;
    protected string $thumbnailsUrl;

    public function __construct(string $thumbnailsDir, string $thumbnailsUrl = '')
    {
        $this->thumbnailsDir = rtrim($thumbnailsDir, '/\\');
        $this->thumbnailsUrl = rtrim($thumbnailsUrl, '/');
    }

    /**
     * Generate a thumbnail for the given source image.
     *
     * @param string $srcPath Absolute path to source image
     * @param int $width
     * @param int|null $height
     * @param bool|string|null $crop
     * @return string|null Absolute path to generated thumbnail, or null on failure
     */
    public function generate(string $srcPath, int $width = 200, ?int $height = null, $crop = null): ?string
    {
        if (!is_file($srcPath)) {
            return null;
        }

        $ext = strtolower(pathinfo($srcPath, PATHINFO_EXTENSION));

        if ($ext === 'svg') {
            return $srcPath; // SVGs don't need thumbnailing
        }

        if (!in_array($ext, ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'webp'])) {
            return null;
        }

        if (!$height) {
            $height = $width;
        }

        $cacheId = $this->buildCacheId($srcPath, $width, $height, $crop, $ext);
        $outputDir = $this->thumbnailsDir . DIRECTORY_SEPARATOR . $width;
        $outputPath = $outputDir . DIRECTORY_SEPARATOR . $cacheId . '.' . $ext;

        if (is_file($outputPath)) {
            return $outputPath;
        }

        if (!is_dir($outputDir)) {
            @mkdir($outputDir, 0755, true);
        }

        $tn = new Thumbnailer($srcPath);
        $thumbOptions = ['height' => $height, 'width' => $width];
        if ($crop) {
            $thumbOptions['crop'] = $crop;
        }

        $tn->createThumb($thumbOptions, $outputPath);

        if (is_file($outputPath)) {
            return $outputPath;
        }

        return null;
    }

    /**
     * Generate a placeholder (pixum) image.
     *
     * @param int $width
     * @param int $height
     * @return string Absolute path to generated pixum
     */
    public function pixum(int $width = 150, int $height = 0): string
    {
        if ($height <= 0) {
            $height = $width;
        }
        if ($width <= 0) {
            $width = 1;
        }

        $pixumDir = $this->thumbnailsDir . DIRECTORY_SEPARATOR . 'pixum';
        $hash = 'pixum-' . $height . 'x' . $width;
        $cachefile = $pixumDir . DIRECTORY_SEPARATOR . $hash . '.png';

        if (is_file($cachefile)) {
            return $cachefile;
        }

        if (!is_dir($pixumDir)) {
            @mkdir($pixumDir, 0755, true);
        }

        $img = imagecreatetruecolor($width, $height);
        $white = imagecolorallocatealpha($img, 239, 236, 236, 0);
        imagefill($img, 0, 0, $white);
        imagealphablending($img, false);
        imagesavealpha($img, true);
        imagepng($img, $cachefile);
        imagedestroy($img);

        return $cachefile;
    }

    /**
     * Build a deterministic cache ID for a given thumbnail configuration.
     */
    public function buildCacheId(string $srcPath, int $width, int $height, $crop, string $ext): string
    {
        $data = [
            'src' => $srcPath,
            'width' => $width,
            'height' => $height,
            'ext' => $ext,
        ];
        if ($crop) {
            $data['crop'] = $crop;
        }

        $hash = crc32(json_encode($data));
        $basename = pathinfo($srcPath, PATHINFO_FILENAME);
        $basename = preg_replace('/[^a-z0-9_\-]/i', '_', $basename);

        return 'tn-' . $basename . '-' . $hash;
    }

    /**
     * Serve a thumbnail image to the browser and exit.
     */
    public function outputImage(string $path): void
    {
        if (!is_file($path)) {
            return;
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        $imageLib = new ImageLib($path);
        if (!$imageLib->testIsImage()) {
            return;
        }

        $imageLib->displayImage($ext);
    }

    /**
     * Get the thumbnails directory path.
     */
    public function getThumbnailsDir(): string
    {
        return $this->thumbnailsDir;
    }

    /**
     * Check if webp is supported by both GD and the current request.
     */
    public static function isWebpSupported(): bool
    {
        return function_exists('imagewebp')
            && isset($_SERVER['HTTP_ACCEPT'])
            && is_string($_SERVER['HTTP_ACCEPT'])
            && strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false;
    }
}