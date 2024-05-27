<?php



if (!function_exists('xss_clean')) {
    function xss_clean($string)
    {
        $cleaner = new \MicroweberPackages\Helper\XSSClean();
        return $cleaner->clean($string);
    }


}
