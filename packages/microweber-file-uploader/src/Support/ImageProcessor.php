<?php

namespace MicroweberPackages\FileUploader\Support;

/**
 * Image processing utilities for uploaded files.
 *
 * Handles EXIF rotation, image re-creation (to strip EXIF data),
 * SVG sanitization, and automatic resizing.
 */
class ImageProcessor
{
    /**
     * Read EXIF data from an image file.
     */
    public function readExifData(string $filePath): array
    {
        if (function_exists('exif_read_data')) {
            return @exif_read_data($filePath) ?: [];
        }
        return [];
    }

    /**
     * Auto-rotate image based on EXIF orientation.
     *
     * @param \GdImage $image
     * @param array $exif
     * @return \GdImage
     */
    public function autoRotateImage($image, array $exif)
    {
        if (!empty($exif['Orientation'])) {
            $image = match ((int) $exif['Orientation']) {
                8 => imagerotate($image, 90, 0),
                3 => imagerotate($image, 180, 0),
                6 => imagerotate($image, -90, 0),
                default => $image,
            };
        }
        return $image;
    }

    /**
     * Process and sanitize an uploaded image file.
     * Re-creates the image to strip EXIF/metadata (security measure).
     *
     * @return bool True if image is valid, false otherwise.
     */
    public function processImage(string $filePath, string $extension): bool
    {
        $ext = strtolower($extension);
        $exifData = $this->readExifData($filePath);

        switch ($ext) {
            case 'jpg':
            case 'jpeg':
            case 'jpe':
                $img = @imagecreatefromjpeg($filePath);
                if (!$img) {
                    return false;
                }
                $img = $this->autoRotateImage($img, $exifData);
                imagejpeg($img, $filePath, 100);
                imagedestroy($img);
                return true;

            case 'png':
                $img = @imagecreatefrompng($filePath);
                if (!$img) {
                    return false;
                }
                $img = $this->autoRotateImage($img, $exifData);
                imagealphablending($img, false);
                imagesavealpha($img, true);
                imagepng($img, $filePath, 9);
                imagedestroy($img);
                return true;

            case 'gif':
                $img = @imagecreatefromgif($filePath);
                if (!$img) {
                    return false;
                }
                $img = $this->autoRotateImage($img, $exifData);
                // Re-create GIF to strip metadata
                $tmpFile = tempnam(sys_get_temp_dir(), 'gif_');
                copy($filePath, $tmpFile);
                if (function_exists('remove_exif_data')) {
                    remove_exif_data($tmpFile, $filePath);
                } else {
                    imagegif($img, $filePath);
                }
                @unlink($tmpFile);
                imagedestroy($img);
                return true;

            case 'svg':
                return $this->sanitizeSvgFile($filePath);

            default:
                return false;
        }
    }

    /**
     * Sanitize an SVG file to remove potential XSS/script injection.
     */
    public function sanitizeSvgFile(string $filePath): bool
    {
        if (!is_file($filePath)) {
            return false;
        }
        $dirtySVG = file_get_contents($filePath);
        $cleanSVG = $this->sanitizeSvg($dirtySVG);
        if ($cleanSVG === null || $cleanSVG === false) {
            return false;
        }
        file_put_contents($filePath, $cleanSVG);
        return true;
    }

    /**
     * Sanitize SVG content string.
     */
    public function sanitizeSvg(string $dirtySVG): ?string
    {
        if (!class_exists(\enshrined\svgSanitize\Sanitizer::class)) {
            return $dirtySVG; // If sanitizer is not available, return as-is
        }
        $sanitizer = new \enshrined\svgSanitize\Sanitizer();
        $clean = $sanitizer->sanitize($dirtySVG);
        return $clean ?: null;
    }

    /**
     * Auto-resize an image if it exceeds the max dimension.
     *
     * @return array{resized: bool, message: string|null}
     */
    public function autoResize(string $filePath, string $extension, int $maxDimension = 1980): array
    {
        $ext = strtolower($extension);
        if (!in_array($ext, ['jpg', 'jpeg', 'jpe', 'png'])) {
            return ['resized' => false, 'message' => null];
        }

        $size = @getimagesize($filePath);
        if (!$size) {
            return ['resized' => false, 'message' => null];
        }

        list($width, $height) = $size;
        if ($width <= $maxDimension && $height <= $maxDimension) {
            return ['resized' => false, 'message' => null];
        }

        $ratio = $width / $height;
        if ($ratio > 1) {
            $newWidth = $maxDimension;
            $newHeight = (int) ($maxDimension / $ratio);
        } else {
            $newWidth = (int) ($maxDimension * $ratio);
            $newHeight = $maxDimension;
        }

        $src = imagecreatefromstring(file_get_contents($filePath));
        if (!$src) {
            return ['resized' => false, 'message' => null];
        }

        $dst = imagecreatetruecolor($newWidth, $newHeight);

        if ($ext === 'png') {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($src);

        if ($ext === 'png') {
            imagepng($dst, $filePath, 9);
        } else {
            imagejpeg($dst, $filePath, 100);
        }
        imagedestroy($dst);

        return [
            'resized' => true,
            'message' => "Image was automatically resized from {$width}x{$height} to {$newWidth}x{$newHeight}",
        ];
    }
}