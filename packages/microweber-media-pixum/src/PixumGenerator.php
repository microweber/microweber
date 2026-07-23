<?php

namespace MicroweberPackages\MediaPixum;

/**
 * Generates placeholder (pixum) PNG images on the filesystem.
 *
 * Standalone — no CMS dependencies, no exit() calls.
 */
class PixumGenerator
{
    protected string $cachePath;

    /** @var array{r: int, g: int, b: int, a: int} */
    protected array $bgColor;

    protected int $maxWidth;
    protected int $maxHeight;

    /**
     * @param array{r: int, g: int, b: int, a: int} $bgColor
     */
    public function __construct(
        string $cachePath,
        array $bgColor = ['r' => 239, 'g' => 236, 'b' => 236, 'a' => 0],
        int $maxWidth = 4000,
        int $maxHeight = 4000
    ) {
        $this->cachePath = rtrim($cachePath, '/\\');
        $this->bgColor = $bgColor;
        $this->maxWidth = $maxWidth;
        $this->maxHeight = $maxHeight;
    }

    /**
     * Generate a placeholder PNG and return the absolute path.
     *
     * If the file already exists, it is returned immediately (cached).
     */
    public function generate(int $width, int $height = 0): string
    {
        $width = $this->clampDimension($width, $this->maxWidth);
        $height = $height > 0 ? $this->clampDimension($height, $this->maxHeight) : $width;

        $hash = 'pixum-' . $height . 'x' . $width;
        $cachefile = $this->cachePath . DIRECTORY_SEPARATOR . $hash . '.png';

        if (is_file($cachefile)) {
            return $cachefile;
        }

        if (!is_dir($this->cachePath)) {
            @mkdir($this->cachePath, 0755, true);
        }

        /** @var int<1, max> $safeWidth */
        $safeWidth = max(1, $width);
        /** @var int<1, max> $safeHeight */
        $safeHeight = max(1, $height);

        $img = @imagecreatetruecolor($safeWidth, $safeHeight);
        if ($img === false) {
            // GD not available or allocation failed — create a minimal fallback
            @file_put_contents($cachefile, $this->minimalPngBytes());
            return $cachefile;
        }

        /** @var int<0, 255> $r */
        $r = max(0, min(255, $this->bgColor['r']));
        /** @var int<0, 255> $g */
        $g = max(0, min(255, $this->bgColor['g']));
        /** @var int<0, 255> $b */
        $b = max(0, min(255, $this->bgColor['b']));
        /** @var int<0, 127> $a */
        $a = max(0, min(127, $this->bgColor['a']));

        $color = imagecolorallocatealpha($img, $r, $g, $b, $a);
        if ($color !== false) {
            imagefill($img, 0, 0, $color);
        }
        imagealphablending($img, false);
        imagesavealpha($img, true);
        imagepng($img, $cachefile);
        imagedestroy($img);

        return $cachefile;
    }

    /**
     * Build a URL for a pixum image (relative to the application).
     *
     * Uses the named route when available, otherwise falls back to
     * a direct site_url path.
     */
    public function url(int $width, int $height = 0): string
    {
        if ($height <= 0) {
            $height = $width;
        }

        try {
            return route('media-pixum.serve', ['width' => $width, 'height' => $height]);
        } catch (\Throwable $e) {
            // Route not registered — build a manual URL
            if (function_exists('site_url')) {
                return site_url('pixum_img') . '?width=' . $width . '&height=' . $height;
            }
            return '/pixum_img?width=' . $width . '&height=' . $height;
        }
    }

    /**
     * Get the cache directory path.
     */
    public function getCachePath(): string
    {
        return $this->cachePath;
    }

    /**
     * Clamp a dimension to [1, max].
     */
    protected function clampDimension(int $value, int $max): int
    {
        if ($value <= 0) {
            return 1;
        }
        return min($value, $max);
    }

    /**
     * Return a minimal 1x1 transparent PNG as raw bytes.
     *
     * Used as a fallback when GD is unavailable.
     *
     * @return string
     */
    protected function minimalPngBytes(): string
    {
        // 1x1 transparent PNG, 67 bytes
        return base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAC0lEQVQI12NgAAIABQAB'
            . 'Nl7BcQAAAABJRU5ErkJggg=='
        );
    }
}