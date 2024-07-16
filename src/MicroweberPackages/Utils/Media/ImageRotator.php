<?php

namespace MicroweberPackages\Utils\Media;

use MicroweberPackages\Utils\ThirdPartyLibs\PHPImageMagician\ImageLib;

class ImageRotator
{
    private $image = '';

    public function __construct($image = false)
    {
        if ($image) {
            $this->image = ($image);
        }
    }


    public function rotateAndSave($angle = 0)
    {
        $src = $this->image;
        if (!$src) {
            return;
        }

        $ext = pathinfo($src, PATHINFO_EXTENSION);

        if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
            $magicianObj = new ImageLib($src);
            $magicianObj->rotateImage($angle);
            $magicianObj->saveImage($src);
        }

    }


}
