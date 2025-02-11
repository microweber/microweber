<?php

if (!function_exists('media_uploads_url')) {
    function media_uploads_url()
    {
        $environment = app()->environment();
        $folder = media_base_url() . ('/default/');

        if (mw_is_multisite()) {
            $folder = media_base_url() . ('/' . $environment . '/');
        }


        return $folder;
    }
}
if (!function_exists('media_uploads_path')) {
    function media_uploads_path()
    {
        $environment = app()->environment();
        $folder = media_base_path() . ('default' . DIRECTORY_SEPARATOR);

        if (mw_is_multisite()) {
            $folder = media_base_path() . ($environment . DIRECTORY_SEPARATOR);
        }

        return $folder;
    }
}
if (!function_exists('media_base_url')) {
    function media_base_url()
    {
        static $folder;

        if (!$folder) {
            //    $folder = userfiles_url() . (MW_MEDIA_FOLDER_NAME . '/');
            $folder = asset('storage/' . MW_MEDIA_FOLDER_NAME). '/';
        }

        return $folder;
    }
}

if (!function_exists('media_base_path')) {
    function media_base_path()
    {
        static $folder;
        if (!$folder) {
            // $folder = userfiles_path() . (MW_MEDIA_FOLDER_NAME . DIRECTORY_SEPARATOR);
            $folder = storage_path('app/public/' . MW_MEDIA_FOLDER_NAME . '/');
        }

        return $folder;
    }

}

if (!function_exists('content_picture')) {
    function content_picture($rel_id, $full = false)
    {
        $rel_type = morph_name(\Modules\Content\Models\Content::class);
        return app()->media_manager->get_picture($rel_id, $rel_type, $full);
    }

}

if (!function_exists('get_picture')) {
    function get_picture($rel_id, $rel_type = false, $full = false)
    {
        return app()->media_manager->get_picture($rel_id, $rel_type, $full);
    }
}

if (!function_exists('get_picture_by_id')) {
    function get_picture_by_id($media_id)
    {
        return get_media_by_id($media_id);
    }
}
if (!function_exists('get_media_by_id')) {
    function get_media_by_id($media_id)
    {
        return app()->media_manager->get_by_id($media_id);
    }

}

if (!function_exists('reorder_media')) {

    function reorder_media($data)
    {
        return app()->media_manager->reorder($data);
    }
}

if (!function_exists('delete_media')) {
    function delete_media($data)
    {
        return app()->media_manager->delete($data);
    }
}

if (!function_exists('save_picture')) {
    function save_media($data)
    {
        return save_picture($data);
    }

}

if (!function_exists('save_picture')) {
    function save_picture($data)
    {
        return app()->media_manager->save($data);
    }
}
if (!function_exists('pixum_img')) {
    function pixum_img()
    {
        return app()->media_manager->pixum_img();
    }
}
if (!function_exists('pixum')) {
    function pixum($width, $height)
    {
        return app()->media_manager->pixum($width, $height);
    }
}

if (!function_exists('thumbnail_img')) {
    function thumbnail_img($params)
    {
        return app()->media_manager->thumbnail_img($params);
    }
}

if (!function_exists('thumbnail')) {
    function thumbnail($src, $width = 200, $height = null, $crop = null)
    {
        return app()->media_manager->thumbnail($src, $width, $height, $crop);
    }
}
if (!function_exists('get_media')) {
    function get_media($params)
    {
        return app()->media_manager->get($params);
    }
}
if (!function_exists('get_pictures')) {
    function get_pictures($params)
    {
        return app()->media_manager->get($params);
    }
}


if (!function_exists('remove_exif_data')) {


    /**
     * Remove EXIF from a IMAGE file.
     * @param string $old Path to original image file (input).
     * @param string $new Path to new jpeg file (output).
     */
    function remove_exif_data($in, $out)
    {
        $buffer_len = 4096;
        $fd_in = fopen($in, 'rb');
        $fd_out = fopen($out, 'wb');
        while (($buffer = fread($fd_in, $buffer_len))) {
            //  \xFF\xE1\xHH\xLLExif\x00\x00 - Exif
            //  \xFF\xE1\xHH\xLLhttp://      - XMP
            //  \xFF\xE2\xHH\xLLICC_PROFILE  - ICC
            //  \xFF\xED\xHH\xLLPhotoshop    - PH
            while (preg_match('/\xFF[\xE1\xE2\xED\xEE](.)(.)(exif|photoshop|http:|icc_profile|adobe)/si', $buffer, $match, PREG_OFFSET_CAPTURE)) {
                //echo "found: '{$match[3][0]}' marker\n";
                $len = ord($match[1][0]) * 256 + ord($match[2][0]);
                //echo "length: {$len} bytes\n";
                //echo "write: {$match[0][1]} bytes to output file\n";
                fwrite($fd_out, substr($buffer, 0, $match[0][1]));
                $filepos = $match[0][1] + 2 + $len - strlen($buffer);
                fseek($fd_in, $filepos, SEEK_CUR);
                //echo "seek to: ".ftell($fd_in)."\n";
                $buffer = fread($fd_in, $buffer_len);
            }
            //echo "write: ".strlen($buffer)." bytes to output file\n";
            fwrite($fd_out, $buffer, strlen($buffer));
        }
        fclose($fd_out);
        fclose($fd_in);
    }

}


if (!function_exists('mergeScreenshotParts')) {
    function mergeScreenshotParts($files, $outputFilename = 'full-screenshot.png')
    {

        $targetHeight = 0;

        $allImageSizes = [];
        foreach ($files as $file) {
            $imageSize = getimagesize($file);
            $allImageSizes[] = [
                'file' => $file,
                'width' => $imageSize[0],
                'height' => $imageSize[1],
            ];
            $targetHeight += $imageSize[1];
        }

        $targetWidth = $allImageSizes[0]['width'];
        $targetImage = imagecreatetruecolor($targetWidth, $targetHeight);

        $i = 0;
        foreach ($allImageSizes as $imageSize) {

            $mergeFile = imagecreatefrompng($imageSize['file']);

            $destinationY = 0;
            if ($i > 0) {
                $destinationY = $imageSize['height'] * $i;
            }

            imagecopymerge($targetImage, $mergeFile, 0, $destinationY, 0, 0, $imageSize['width'], $imageSize['height'], 100);
            imagedestroy($mergeFile);
            $i++;
        }

        imagepng($targetImage, $outputFilename, 8);
    }
}
