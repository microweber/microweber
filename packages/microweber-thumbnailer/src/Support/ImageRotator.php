<?php

namespace MicroweberPackages\Thumbnailer\Support;

use MicroweberPackages\Thumbnailer\Libs\PHPImageMagician\ImageLib;

class ImageRotator
{
    private string $image = '';

    public function __construct(string $image = '')
    {
        $this->image = $image;
    }

    public function rotateAndSave(int $angle = 0): void
    {
        $src = $this->image;
        if (!$src) {
            return;
        }

        $ext = strtolower(pathinfo($src, PATHINFO_EXTENSION));

        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
            $magicianObj = new ImageLib($src);
            $magicianObj->rotateImage($angle);
            $magicianObj->saveImage($src);
        }
    }
}