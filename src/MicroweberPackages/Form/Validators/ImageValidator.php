<?php


namespace MicroweberPackages\Form\Validators;

use Illuminate\Validation\Validator;

class ImageValidator   {


    public function validate($attribute, $value, $parameters, Validator $validator) {


        $files_utils = new \MicroweberPackages\Utils\System\Files();
        $dangerous = $files_utils->get_dangerous_files_extentions();

        if (!method_exists($value,'clientExtension')) {
            return false;
        }

        $ext = ($value->clientExtension());

        $filePath =$value->getPathname();
        $valid = false;

        if (is_file($filePath)) {
            // $ext = get_file_extension($filePath);

            if (function_exists('finfo_open') and function_exists('finfo_file')) {
                $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
                $mime = @finfo_file($finfo, $filePath);
                finfo_close($finfo);

                if ($mime) {
                    $upl_mime_ext = explode('/', $mime);
                    $upl_mime_ext = end($upl_mime_ext);
                    $upl_mime_ext = explode('-', $upl_mime_ext);
                    $upl_mime_ext = end($upl_mime_ext);
                    $upl_mime_ext = strtolower($upl_mime_ext);

                    if (in_array($upl_mime_ext, $dangerous)) {
                        return false;
                    }
                } else {
                    return false;

                }
            }

            if ($ext == 'gif' || $ext == 'jpg' || $ext == 'jpeg' || $ext === 'jpe' || $ext == 'png') {

                $valid = false;
                if ($ext === 'jpg' || $ext === 'jpeg' || $ext === 'jpe') {
                    if (@imagecreatefromjpeg($filePath)) {
                        $valid = true;
                    }
                } else if ($ext === 'png') {
                    if (@imagecreatefrompng($filePath)) {
                        $valid = true;
                    }
                } else if ($ext === 'gif') {
                    if (@imagecreatefromgif($filePath)) {
                        $valid = true;
                    }
                } else {
                    $valid = false;
                }
                if (!$valid) {
                    @unlink($filePath);
                    return false;
                }
            }

        }
        return $valid;

    }

}