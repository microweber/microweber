<?php

namespace Modules\Media\Support;

use MicroweberPackages\Utils\ThirdPartyLibs\PHPImageMagician\ImageLib;

class Thumbnailer
{
    private $image = '';

    public function __construct($image = false)
    {
        if ($image) {
            $this->image = ($image);
        }
    }


    public function createThumb(array $specifications, $dest)
    {
        $src = $this->image;
        if (!$src) {
            return;
        }

        $width = 0;
        $height = 0;
        $crop = 0;
        $height = $specifications['height'];
        $width = $specifications['width'];
        $pngQuality = 1;
        $restQuality = 100;

        if (isset($specifications['crop']) and $specifications['crop']) {
            $crop = $specifications['crop'];
        }

        $width = intval($width);
        $height = intval($height);

        $ext = pathinfo($src, PATHINFO_EXTENSION);

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

        if ($ext == 'png' or $ext == 'webp') {
            $imgQuality = $pngQuality;
        } else {
            $imgQuality = $restQuality;
        }

        $magicianObj->saveImage($dest, $imgQuality);

    }

}
