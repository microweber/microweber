<?php

if (!function_exists('media_uploads_url')) {
    function media_uploads_url()
    {
        $environment = app()->environment();
        $folder = media_base_url() . ('default/');

        if (mw_is_multisite()) {
            $folder = media_base_url() . ( $environment . '/');
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


if (!function_exists('media_uploads_path_relative')) {
    function media_uploads_path_relative()
    {
        $environment = app()->environment();
        $folder = MW_USERFILES_FOLDER_NAME.DIRECTORY_SEPARATOR. MW_MEDIA_FOLDER_NAME  . DIRECTORY_SEPARATOR. ('default' . DIRECTORY_SEPARATOR);

        if (mw_is_multisite()) {
            $folder = MW_USERFILES_FOLDER_NAME .DIRECTORY_SEPARATOR. MW_MEDIA_FOLDER_NAME . DIRECTORY_SEPARATOR . ($environment . DIRECTORY_SEPARATOR);
        }

        return $folder;
    }
}


if (!function_exists('media_base_url')) {
    function media_base_url()
    {
        static $folder;

        if (!$folder) {
              $folder = userfiles_url() . (MW_MEDIA_FOLDER_NAME . '/');
           // $folder = asset('storage/' . MW_MEDIA_FOLDER_NAME) . '/';
        }

        return $folder;
    }
}

if (!function_exists('media_base_path')) {
    function media_base_path()
    {
        static $folder;
        if (!$folder) {
           $folder = userfiles_path() . (MW_MEDIA_FOLDER_NAME . DIRECTORY_SEPARATOR);
           // $folder = storage_path('app/public/' . MW_MEDIA_FOLDER_NAME . '/');
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

if (!function_exists('responsive_thumbnail')) {
    /**
     * Render a complete <img> element with src + srcset + sizes + alt +
     * loading + decoding attrs.
     *
     * audit-test 2026-05-08 PM TASK-012 / TICKET-CX (JIRA AI-29):
     * The bare thumbnail() helper returns just a URL, so every public
     * template that wants a responsive image had to hand-roll its own
     * <img> markup — partial and inconsistent srcset/lazy coverage was
     * the result. This helper centralises the shape so a future bandwidth
     * tweak (more srcset widths, different sizes default, etc.) lands in
     * ONE place per the ADR-0001 helper-layer principle (cycle-44 EVAL-PLAN
     * 10/10 Grug recommendation).
     *
     * Usage:
     *   {!! responsive_thumbnail($item['filename'], 800, 600, ['alt' => $item['title']]) !!}
     *   {!! responsive_thumbnail($member['file'], 800, 800, [
     *       'alt' => $member['name'],
     *       'class' => 'img-fluid',
     *       'loading' => $loop->first ? 'eager' : 'lazy',
     *   ]) !!}
     *
     * Options accepted:
     *   alt       string       — alt text. If omitted OR empty, the helper
     *                            derives a non-empty fallback from the src
     *                            filename basename (TASK-012 addition
     *                            2026-05-08: alt="" is forbidden on
     *                            product/content imagery for accessibility).
     *                            Pass a meaningful value (product title,
     *                            member name, caption) at every call site;
     *                            the filename fallback is only a safety net.
     *   loading   string       — 'lazy' | 'eager' (default: 'lazy')
     *   sizes     string       — sizes attribute (default: '100vw')
     *   class     string       — extra CSS class names (default: '')
     *   crop      bool|null    — passed through to thumbnail() (default: null)
     *   srcset    array<int>|null
     *                          — list of pixel widths for srcset; null
     *                            means [width, width*2] (1x + 2x)
     *   itemprop  string|null  — Schema.org itemprop (default: null)
     *   id        string|null  — id attribute (default: null)
     *   style     string|null  — inline style (default: null)
     *   decoding  string       — decoding attribute (default: 'async')
     *
     * Returns the <img ...> string; callers use `{!! !!}` to render
     * (the helper escapes attribute values via htmlspecialchars).
     */
    function responsive_thumbnail($src, $width = 800, $height = null, array $options = [])
    {
        $alt        = (string) ($options['alt'] ?? '');
        if ($alt === '') {
            // Safety net per TASK-012 addition 2026-05-08: empty alt is
            // forbidden. Derive from the src filename basename, cleaning
            // separators into spaces. If the basename is unusable, fall
            // back to the literal 'Image'.
            $base = pathinfo((string) $src, PATHINFO_FILENAME);
            $base = trim(preg_replace('/[_\-]+/', ' ', (string) $base));
            $alt  = $base !== '' ? $base : 'Image';
        }
        $loading    = (string) ($options['loading'] ?? 'lazy');
        $sizes      = (string) ($options['sizes'] ?? '100vw');
        $class      = (string) ($options['class'] ?? '');
        $crop       = $options['crop'] ?? null;
        $itemprop   = $options['itemprop'] ?? null;
        $id         = $options['id'] ?? null;
        $style      = $options['style'] ?? null;
        $decoding   = (string) ($options['decoding'] ?? 'async');

        // Build srcset. Default: [width, width * 2] for 1x + 2x DPR.
        $srcsetWidths = $options['srcset'] ?? [$width, $width * 2];
        if (! is_array($srcsetWidths)) {
            $srcsetWidths = [$width, $width * 2];
        }
        $srcsetWidths = array_values(array_unique(array_filter(array_map('intval', $srcsetWidths))));

        $primarySrc = thumbnail($src, $width, $height, $crop);

        $srcsetParts = [];
        foreach ($srcsetWidths as $w) {
            // For srcset variants we scale height proportionally if a height
            // was given; otherwise let the source aspect ratio decide.
            $variantHeight = $height ? (int) round($height * ($w / $width)) : null;
            $variantUrl = thumbnail($src, $w, $variantHeight, $crop);
            $srcsetParts[] = $variantUrl . ' ' . $w . 'w';
        }
        $srcset = implode(', ', $srcsetParts);

        $escape = static fn ($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');

        $attrs = [
            'src="' . $escape($primarySrc) . '"',
            'srcset="' . $escape($srcset) . '"',
            'sizes="' . $escape($sizes) . '"',
            'alt="' . $escape($alt) . '"',
            'loading="' . $escape($loading) . '"',
            'decoding="' . $escape($decoding) . '"',
        ];
        if ($class !== '') {
            $attrs[] = 'class="' . $escape($class) . '"';
        }
        if ($itemprop !== null && $itemprop !== '') {
            $attrs[] = 'itemprop="' . $escape($itemprop) . '"';
        }
        if ($id !== null && $id !== '') {
            $attrs[] = 'id="' . $escape($id) . '"';
        }
        if ($style !== null && $style !== '') {
            $attrs[] = 'style="' . $escape($style) . '"';
        }

        return '<img ' . implode(' ', $attrs) . '>';
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
