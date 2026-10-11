<?php
/**
 * @deprecated Use \MicroweberPackages\Thumbnailer\Libs\PHPImageMagician\PhpPsdReader instead.
 * This file is kept for backward compatibility only.
 */
namespace MicroweberPackages\Utils\ThirdPartyLibs\PHPImageMagician;

class PhpPsdReader extends \MicroweberPackages\Thumbnailer\Libs\PHPImageMagician\PhpPsdReader
{
}

if (!function_exists('imagecreatefrompsd')) {
    function imagecreatefrompsd($fileName) {
        $psdReader = new PhpPsdReader($fileName);
        if (isset($psdReader->infoArray['error'])) return '';
        else return $psdReader->getImage();
    }
}
