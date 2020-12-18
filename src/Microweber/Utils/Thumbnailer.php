<?php

namespace Microweber\Utils;


class Thumbnailer
{
    private $image = '';

    public function __construct($image = false)
    {


        if ($image) {
            $this->image = ($image);
        }
    }

    //$specifications['height'];
    //$specifications['width'];

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
        $restQuality = 90;

        if (isset($specifications['crop']) and $specifications['crop']) {
            $crop = $specifications['crop'];
        }

        $width = intval($width);
        $height = intval($height);

        $ext = pathinfo($src, PATHINFO_EXTENSION);

        $magicianObj = new \Microweber\Utils\lib\PHPImageMagician\imageLib($src);

        $magicianObj_mode = 'landscape';
        if ($crop) {
            $magicianObj_mode = 'crop';
        }

        $magicianObj->resizeImage($width, $height, $magicianObj_mode);

        if ($ext == 'png') {
            $imgQuality = $pngQuality;
        } else {
            $imgQuality = $restQuality;
        }

        $magicianObj->saveImage($dest, $imgQuality);

    }

}


//
//
//class LegacyThumbnailer-DoBeDeleted
//{
//    // from http://www.hardcode.nl/subcategory_4/article_503-thumbnail-class
//    private $image = '';
//    //image filename and path
//    private $sizes = null;
//
//    public function __construct($image = false)
//    {
//        if (defined('INI_SYSTEM_CHECK_DISABLED') == false) {
//            define('INI_SYSTEM_CHECK_DISABLED', ini_get('disable_functions'));
//        }
//
//        if (!strstr(INI_SYSTEM_CHECK_DISABLED, 'ini_set')) {
//            @ini_set('memory_limit', '256M');
//            @ini_set('set_time_limit', 90);
//        }
//        if (!strstr(INI_SYSTEM_CHECK_DISABLED, 'set_time_limit')) {
//            @set_time_limit(90);
//        }
//
//        if ($image) {
//            $this->setImage($image);
//        }
//    }
//
//    public function setImage($image)
//    {
//        if (is_file($image) && ($this->sizes = @getimagesize($image))) {
//            $this->image = $image;
//        }
//    }
//
//    /*  createThumb creates and saves a thumbnail
//     * 	requires an $outputPath including filename to save thumbnail  file to;
//     * 	specifications = array(maxLength=>100,width=>100,height=100,mime=>'image/jpeg')
//     */
//
//    public function createThumb(array $specifications, $outputPath)
//    {
//        if (!strlen($this->image)) {
//            return;
//        }
//        $sizes = $this->sizes;
//        $originalImage = $this->loadImage($this->image, $sizes['mime']);
//        $newWidth = 0;
//        $newHeight = 0;
//
//        if (isset($specifications['width']) && !isset($specifications['height']) && !isset($specifications['maxLength'])) {
//            $newWidth = $specifications['width'];
//            $newHeight = $sizes[1] * ($newWidth / $sizes[0]);
//        } elseif (isset($specifications['height']) && !isset($specifications['width'])) {
//            $newHeight = $specifications['height'];
//            $newWidth = $sizes[0] * ($newHeight / $sizes[1]);
//        } elseif (isset($specifications['height']) && isset($specifications['width'])) {
//            $newWidth = $specifications['width'];
//            $newHeight = $specifications['height'];
//            // $newHeight = (int)(100 * $newWidth / $newHeight);
//        } elseif (isset($specifications['maxLength'])) {
//            if ($sizes[0] >= $sizes[1]) {
//                $newWidth = $specifications['width'];
//                $newHeight = $sizes[1] * ($newWidth / $sizes[0]);
//            } else {
//                $newHeight = $specifications['maxLength'];
//                $newWidth = $sizes[0] * ($newHeight / $sizes[1]);
//            }
//        } else {
//            $newWidth = $sizes[0];
//            $newHeight = $sizes[1];
//        }
//
//        $crop_x = 0;
//        if (isset($specifications['crop_x'])) {
//            $crop_x = $specifications['crop_x'];
//        }
//        $crop_y = 0;
//        if (isset($specifications['crop_y'])) {
//            $crop_y = $specifications['crop_y'];
//        }
//        $newWidth = intval($newWidth);
//        $newHeight = intval($newHeight);
//        $im = imagecreatetruecolor($newWidth, $newHeight);
//        imagealphablending($im, false);
//        imagesavealpha($im, true);
//        imagecopyresampled($im, $originalImage, 0, 0, $crop_x, $crop_y, $newWidth, $newHeight, $sizes[0], $sizes[1]);
//
//        $type = !isset($specifications['mime']) ? $sizes['mime'] : $specifications['mime'];
//        if ($type == 'image/gif') {
//            $type = 'image/png';
//            //to preserve transpacency
//            //  $outputPath = str_replace('.gif', '.png', $outputPath);
//        }
//        $this->saveImage($im, $outputPath, $type);
//
//        // Free up memory
//        imagedestroy($im);
//        imagedestroy($originalImage);
//    }
//
//    private function loadImage($imgname, $type)
//    {
//        switch ($type) {
//            case 'image/gif' :
//                $im = $this->LoadGif($imgname);
//                break;
//            case 'image/jpeg' :
//            case 'image/jpg' :
//                $im = $this->LoadJpeg($imgname);
//                break;
//            case 'image/png' :
//                $im = $this->LoadPNG($imgname);
//        }
//
//        return $im;
//    }
//
//    private function LoadGif($imgname)
//    {
//        $im = @imagecreatefromgif($imgname);
//        /* Attempt to open */
//        if (!$im) { /* See if it failed */
//            $im = $this->imageerror();
//        }
//
//        return $im;
//    }
//
//    private function imageerror()
//    {
//        $im = imagecreatetruecolor(150, 30);
//        /* Create a black image */
//        $bgc = imagecolorallocate($im, 255, 255, 255);
//        $tc = imagecolorallocate($im, 0, 0, 0);
//        imagefilledrectangle($im, 0, 0, 150, 30, $bgc);
//        /* Output an errmsg */
//        imagestring($im, 1, 5, 5, 'Error loading image', $tc);
//
//        return $im;
//    }
//
//    private function LoadJpeg($imgname)
//    {
//        $im = @imagecreatefromjpeg($imgname);
//        /* Attempt to open */
//        if (!$im) { /* See if it failed */
//            $im = $this->imageerror();
//        }
//
//        return $im;
//    }
//
//    private function LoadPNG($imgname)
//    {
//        $im = @imagecreatefrompng($imgname);
//
//        /* Attempt to open */
//        if (!$im) { /* See if it failed */
//            $im = $this->imageerror();
//        }
//
//        return $im;
//    }
//
//    private function saveImage($image, $imgname, $type)
//    {
//        switch ($type) {
//            case 'image/gif' :
//                $im = imagegif($image, $imgname);
//                break;
//            case 'image/jpg' :
//            case 'image/jpeg' :
//                $im = imagejpeg($image, $imgname, 90);
//                break;
//            case 'image/png' :
//                //
//                $im = imagepng($image, $imgname, 9);
//        }
//
//        return $im;
//    }
//}
