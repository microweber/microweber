<?php



if (!function_exists('xss_clean')) {
    function xss_clean($string)
    {
        $cleaner = new \MicroweberPackages\Security\XSSClean();
        return $cleaner->clean($string);
    }
}
