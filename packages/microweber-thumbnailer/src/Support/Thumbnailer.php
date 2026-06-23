<?php

namespace MicroweberPackages\Thumbnailer\Support;

use MicroweberPackages\Thumbnailer\Libs\PHPImageMagician\ImageLib;

class Thumbnailer
{
    private string $image = '';

    public function __construct(string $image = '')
    {
        $this->image = $image;
    }

    public function createThumb(array $specifications, string $dest): void
    {
        $src = $this->image;
        if (!$src) {
            return;
        }

        $width = (int) ($specifications['width'] ?? 0);
        $height = (int) ($specifications['height'] ?? 0);
        $crop = $specifications['crop'] ?? null;
        $pngQuality = 1;
        $restQuality = 100;

        $ext = strtolower(pathinfo($src, PATHINFO_EXTENSION));

        $magicianObj = new ImageLib($src);

        $magicianObj_mode = 'landscape';
        if ($crop) {
            if (is_bool($crop)) {
                $magicianObj_mode = 'crop';
            } else {
                $magicianObj_mode = 'crop-' . $crop;
            }
        }

        $magicianObj->resizeImage($width, $height, $magicianObj_mode);

        if ($ext === 'png' || $ext === 'webp') {
            $imgQuality = $pngQuality;
        } else {
            $imgQuality = $restQuality;
        }

        $magicianObj->saveImage($dest, $imgQuality);
    }
}