<?php

function get_picture($content_id, $for = 'post', $full = false)
{
    $arr = array();
    $arr['rel'] = 'content';
    $arr['limit'] = '1';
    $arr['rel_id'] = $content_id;
    $imgages = get_pictures($arr);

    if ($imgages != false and isset($imgages[0])) {
        if (isset($imgages[0]['filename']) and $full == false) {
            return $imgages[0]['filename'];
        } else {
            return $imgages[0];
        }

    } else {
        $cont_id = get_content_by_id($content_id);

        if (isset($cont_id['content'])) {
            $img = get_first_image_from_html(html_entity_decode($cont_id['content']));

            if ($img != false) {
                $surl = site_url();

                $img = str_replace_once('{SITE_URL}', $surl, $img);

                $media_url = MEDIA_URL;
                if (stristr($img, $surl)) {
                    return $img;
                } else {
                    return $img;
                    return false;
                    // $src = $img;
                    // $dl_file = MEDIAFILES . 'downloaded' . DS . md5($src) . basename($src);
                    //
                    // if (!file_exists($dl_file)) {
                    // $is_dl = url_download($src, false, $dl_file);
                    // } else {
                    // $is_dl = 1;
                    // }
                    // if ($is_dl == true) {
                    // $src = $dl_file;
                    // return $src;
                    // } else {
                    //
                    // }

                }

            }
        }
    }
}

function get_first_image_from_html($html)
{
    if (preg_match('/<img.+?src="(.+?)"/', $html, $matches)) {
        return $matches[1];
    } elseif (preg_match('/<img.+?src=\'(.+?)\'/', $html, $matches)) {
        return $matches[1];
    } else {
        return false;
    }
}

api_expose('upload_progress_check');

function upload_progress_check()
{
    if (is_admin() == false) {

        error('not logged in as admin');
    }
    if (isset($_SERVER["HTTP_REFERER"])) {
        $ref_str = md5($_SERVER["HTTP_REFERER"]);
    } else {
        $ref_str = 'no_HTTP_REFERER';
    }
    $ref_str = 'no_HTTP_REFERER';
    $cache_id = 'upload_progress_' . $ref_str;
    $cache_group = 'media/global';

    $cache_content = cache_get_content($cache_id, $cache_group);
    if ($cache_content != false) {
        if (isset($cache_content["tmp_name"]) != false) {
            if (isset($cache_content["f"]) != false) {

                $filename = $cache_content["tmp_name"];
                if (is_file($filename)) {
                    $filesize = filesize($filename);
                }

                $filename = $cache_content["f"];

                if (is_file($filename)) {
                    $filesize = filesize($filename);
                }

                $perc = percent($filesize, $cache_content["size"]);
                return $perc;
                //  d($perc);
            }
        }
    }

    //ini_set("session.upload_progress.enabled", true);
    // return $_SESSION;
}

api_expose('upload');

function upload($data)
{
    if (is_admin() == false) {

        error('not logged in as admin');
    }
    ini_set("upload_max_filesize", "2500M");
    ini_set("memory_limit", "256M");
    ini_set("max_execution_time", 0);
    ini_set("post_max_size", "2500M");
    ini_set("max_input_time", 9999999);

    // ini_set("session.upload_progress.enabled", 1);
    if (isset($_SERVER["HTTP_REFERER"])) {
        $ref_str = md5($_SERVER["HTTP_REFERER"]);
    } else {
        $ref_str = 'no_HTTP_REFERER';
    }
    $ref_str = 'no_HTTP_REFERER';
    $cache_id = 'upload_progress_' . $ref_str;
    $cache_group = 'media/global';

    $target_path = MEDIAFILES . 'uploaded' . DS;
    $target_path = normalize_path($target_path, 1);

    if (!is_dir($target_path)) {

        mkdir_recursive($target_path);
    }
    $rerturn = array();
    if (!isset($_FILES) and isset($data['file'])) {
        if (isset($data['name'])) {
            $f = $target_path . $data['name'];
            if (is_file($f)) {
                $f = $target_path . date('YmdHis') . $data['name'];
            }

            $df = strpos($data['file'], 'base64,');
            if ($df != false) {
                //   $df = substr($data['file'], 0, $df);
                $data['file'] = substr($data['file'], $df + 7);
                $data['file'] = str_replace(' ', '+', $data['file']);
                //    d($data['file']);
            }

            // base64_to_file($data['file'], $f);

            $rerturn['src'] = pathToURL($f);
            $rerturn['name'] = $data['name'];
        }
    } else {

        $allowedExts = array("jpg", "jpeg", "gif", "png", 'bmp');

        //$upl = cache_save($_FILES, $cache_id, $cache_group);
        foreach ($_FILES as $item) {

            $extension = end(explode(".", $item["name"]));
            if (in_array($extension, $allowedExts)) {
                if ($item["error"] > 0) {
                    error("Error: " . $item["error"]);
                } else {
                    $upl = cache_save($item, $cache_id, $cache_group);

                    $f = $target_path . $item['name'];
                    if (is_file($f)) {
                        $f = $target_path . date('YmdHis') . $item['name'];
                    }

                    $progress = (array)$item;
                    $progress['f'] = $f;
                    $upl = cache_save($progress, $cache_id, $cache_group);

                    if (move_uploaded_file($item['tmp_name'], $f)) {
                        $rerturn['src'] = pathToURL($f);
                        $rerturn['name'] = $item['name'];
                    }
                }
            } else {
                error("Invalid file ext");
            }

            //
            //            $input = fopen("php://input", "r");
            //            $temp = tmpfile();
            //
            //            $realSize = stream_copy_to_stream($input, $temp);
            //            fclose($input);
            //
            //
            //
            //
            //            $target = fopen($f, "w");
            //            fseek($temp, 0, SEEK_SET);
            //            stream_copy_to_stream($temp, $target);
            //            $rerturn['src'] = pathToURL($f);
            //            $rerturn['name'] = $item['name'];
            //            fclose($target);
        }

        //   var_dump($_FILES);
    }

    exit(json_encode($rerturn));
    //var_dump($data);
    //var_dump($_FILES);
}

function base64_to_file($data, $target)
{

    touch($target);
    if (is_writable($target) == false) {
        exit("$target is not writable");
    }
    $whandle = fopen($target, 'wb');
    stream_filter_append($whandle, 'convert.base64-decode', STREAM_FILTER_WRITE);
    fwrite($whandle, $data);

    // file_put_contents($target, base64_decode($data));
    fclose($whandle);
}

api_expose('reorder_media');

function reorder_media($data)
{

    $adm = is_admin();
    if ($adm == false) {
        error('Error: not logged in as admin.' . __FILE__ . __LINE__);
    }

    $table = MW_TABLE_PREFIX . 'media';
    foreach ($data as $value) {
        if (is_arr($value)) {
            $indx = array();
            $i = 0;
            foreach ($value as $value2) {
                $indx[$i] = $value2;
                $i++;
            }

            db_update_position($table, $indx);
            return true;
            // d($indx);
        }
    }
}

api_expose('delete_media');

function delete_media($data)
{

    $adm = is_admin();
    if ($adm == false) {
        error('Error: not logged in as admin.' . __FILE__ . __LINE__);
    }

    if (isset($data['id'])) {
        $c_id = intval($data['id']);
        $pic_data = get_pictures("one=1&id=" . $c_id);
        if (isset($pic_data['filename'])) {
            $fn_remove = url2dir($pic_data['filename']);
            if (is_file($fn_remove)) {
                @unlink($fn_remove);
            }
        }
        db_delete_by_id('media', $c_id);
    }
}

api_expose('save_media');

function save_media($data)
{

    $s = array();
    if (isset($data['for'])) {
        $t = guess_table_name($data['for']);
        $t = db_get_assoc_table_name($t);
        $s['rel'] = $t;
    }

    if (isset($data['for-id'])) {
        $t = trim($data['for-id']);
        $s['rel_id'] = $t;
    }

    if (isset($data['for_id'])) {
        $t = trim($data['for_id']);
        $s['rel_id'] = $t;
    }

    if (isset($data['id'])) {
        $t = intval($data['id']);
        $s['id'] = $t;
    }

    if (isset($data['title'])) {
        $t = ($data['title']);
        $s['title'] = $t;
    }

    if (isset($data['src'])) {

        $url2dir = url2dir($data['src']);
        $uploaded_files_dir = MEDIAFILES . DS . 'uploaded';

        if (isset($s['rel']) and isset($s['rel_id'])) {
            $move_uploaded_files_dir = MEDIAFILES . DS . $s['rel'] . DS;
            $move_uploaded_files_dir_index = MEDIAFILES . DS . $s['rel'] . DS . 'index.php';

            $uploaded_files_dir = normalize_path($uploaded_files_dir);
            if (!is_dir($move_uploaded_files_dir)) {
                mkdir_recursive($move_uploaded_files_dir);
                @touch($move_uploaded_files_dir_index);

            }

            $url2dir = normalize_path($url2dir, false);
            $newfile = basename($url2dir);

            $newfile = preg_replace('/[^\w\._]+/', '_', $newfile);
            $newfile = $move_uploaded_files_dir . $newfile;

            if (is_file($newfile)) {

                $newfile = date('YmdHis') . basename($url2dir);
                $newfile = preg_replace('/[^\w\._]+/', '_', $newfile);
                $newfile = $move_uploaded_files_dir . $newfile;
            }

            if (rename($url2dir, $newfile)) {
                $data['src'] = dir2url($newfile);
            } else {

            }

        }

        /*
         if(strstr($url2dir,$uploaded_files_dir)){
         if (!copy($file, $newfile)) {

         } else {

         }
        }*/

        $s['filename'] = $data['src'];
    }

    if (!isset($data['position']) and !isset($s['id'])) {
        $s['position'] = 9999999;
    }

    if (isset($data['for_id'])) {
        $t = trim($data['for_id']);
        $s['rel_id'] = $t;
    }

    if (isset($data['media_type'])) {
        $t = db_escape_string($data['media_type']);
        $s['media_type'] = $t;
    }

    // ->'content';
    if (isset($s['rel']) and isset($s['rel_id'])) {
        $table = MW_TABLE_PREFIX . 'media';
        //$s['debug'] = $t;
        $s = save_data($table, $s);
        return ($s);
    } elseif (isset($s['id']) and isset($s['title'])) {
        $table = MW_TABLE_PREFIX . 'media';
        //$s['debug'] = $t;
        $s = save_data($table, $s);
        return ($s);
    } else {
        error('Invalid data');
    }
}

function pixum_img()
{
    $mime_type = "image/jpg";
    $extension = ".jpg";
    $cache_folder = CACHEDIR . 'pixum' . DS;
    if (!is_dir($cache_folder)) {
        mkdir_recursive($cache_folder);
    }

    if (isset($_REQUEST['width'])) {
        $w = $_REQUEST['width'];
    } else {
        $w = 1;
    }

    if (isset($_REQUEST['height'])) {
        $h = $_REQUEST['height'];
    } else {
        $h = 1;
    }
    $h = intval($h);
    $w = intval($w);
    if ($h == 0) {
        $h = 1;
    }

    if ($w == 0) {
        $w = 1;
    }
    $hash = 'pixum-' . ($h) . 'x' . $w;
    $cachefile = $cache_folder . '/' . $hash . $extension;

    header("Content-Type: image/jpg");

    # Generate cachefile for image, if it doesn't exist
    if (!file_exists($cachefile)) {

        $img = imagecreatetruecolor($w, $h);

        $bg = imagecolorallocate($img, 225, 226, 227);

        imagefilledrectangle($img, 0, 0, $w, $h, $bg);
        //  header("Content-type: image/png");
        imagejpeg($img, $cachefile);
        imagedestroy($img);

        $fp = fopen($cachefile, 'rb');
        # stream the image directly from the cachefile
        fpassthru($fp);
        exit;
    } else {

        $fp = fopen($cachefile, 'rb');
        # stream the image directly from the cachefile
        fpassthru($fp);
        exit;
    }
}

function pixum($width, $height)
{
    return site_url('api/pixum_img') . "?width=" . $width . "&height=" . $height;
}

api_expose('thumbnail_img');
function thumbnail_img($params)
{
    if (isset($_SESSION) and !empty($_SESSION)) {
        //session_write_close();
    }
    extract($params);


    if (!isset($width)) {
        $width = 200;
    }


    if (!isset($height)) {
        $width = 200;
    }

    if (!isset($src) or $src == false) {
        return pixum($width, $height);
    }


    //require_once ();
    $surl = site_url();
    $local = false;

    $media_url = MEDIA_URL;
    $media_url = trim($media_url);
    $src = str_replace('{SITE_URL}', $surl, $src);
    $src = str_replace('%7BSITE_URL%7D', $surl, $src);
    $src = str_replace('..', '', $src);

    // $src = str_replace('%7BSITE_URL%7D', $surl, $src);

    /*
     */


    if (strstr($src, $surl) or strpos($src, $surl)) {

        $src = str_replace($surl . '/', $surl, $src);
        //$src = str_replace($media_url, '', $src);
        $src = str_replace($surl, '', $src);
        $src = ltrim($src, DS);
        $src = ltrim($src, '/');
        $src = rtrim($src, DS);
        $src = rtrim($src, '/');
        //$src = MEDIAFILES . $src;
        $src = MW_ROOTPATH . $src;
        $src = normalize_path($src, false);

    } else {

        $src = MW_ROOTPATH . $src;
        $src = normalize_path($src, false);


        // $dl_file = MEDIAFILES . 'downloaded' . DS . md5($src) . basename($src);
        //
        // if (!file_exists($dl_file)) {
        // $is_dl = url_download($src, false, $dl_file);
        // } else {
        // $is_dl = 1;
        // }
        // if ($is_dl == true) {
        // $src = $dl_file;
        // } else {
        //
        // }


        if (is_file($src)) {

        } else {
            return pixum_img();
        }

        if ($src == false) {

        }
    }
    $cd = CACHEDIR . 'thumbnail' . DS;
    if (!is_dir($cd)) {
        mkdir_recursive($cd);
    }
    $index_file = $cd.'index.html';
    if (!is_file($index_file)) {
        file_put_contents($index_file, 'Thumbnail directory is not allowed');
    }

    $ext = strtolower(get_file_extension($src));
    $cache = crc32(serialize($params)) . '.'.$ext;

    $cache = str_replace(' ', '_', $cache);

    if(isset($cache_id)){
        $cache = str_replace(' ', '_', $cache_id);
        $cache = str_replace('..', '', $cache);
    }

    $cache_path = $cd . $cache;

    if (file_exists($cache_path)) {

    } else {
        //

        if (file_exists($src)) {


            if (($ext) == 'svg') {
                $res1 = file_get_contents($src);
                $res1 = svgScaleHack($res1, $width, $height);
                file_put_contents($cache_path, $res1);

            } else {
                if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'png' || $ext == 'bmp') {
                $tn = new \mw\Thumbnailer($src);
                $thumbOptions = array('maxLength' => $height, 'width' => $width);
                $tn->createThumb($thumbOptions, $cache_path);

                unset($tn);
                } else {
                    return pixum_img();
                }


            }
        }

    }


    $ext = get_file_extension($cache_path);
    if ($ext == 'jpg') {
        $ext = 'jpeg';
    }
    header("Content-Type: image/" . $ext);
    header("Content-Length: " . filesize($cache_path));


    readfile($cache_path);

    exit;


    if (file_exists($cache_path)) {

        //$cache_path = pathToURL($cache_path);
        //$cache_path = file_get_contents($cache_path);
        //
        //
        return $cache_path;
    } else {
        return pixum($width, $height);
    }
    return false;

}

function thumbnail($src, $width = 200, $height = 200)
{
    if ($src == false) {
        return pixum($width, $height);
    }
    $src = html_entity_decode($src);
    $src = htmlspecialchars_decode($src);

    $surl = site_url();
    $src = str_replace('{SITE_URL}', $surl, $src);
    $src = str_replace('%7BSITE_URL%7D', $surl, $src);
    $base_src = str_replace($surl, '', $src);


    if (!isset($width)) {
        $width = 200;
    }


    if (!isset($height)) {
        $width = 200;
    }
    $cd = CACHEDIR . 'thumbnail' . DS;



    $ext = strtolower(get_file_extension($base_src));
    $cache =  ($base_src . $width . $height) . '.'.$ext;

    $cache = str_replace(' ', '_', $cache);

  //print $cache_path;
    //exit ($cache_path);
    $ext = strtolower(get_file_extension($src));

    $cache_id = array();
    $cache_id['src'] = $base_src;
    $cache_id['width'] = $width;
    $cache_id['height'] = $height;
    $cache_id = 'tn-'.crc32(serialize($cache_id)) . '.'.$ext;
    $cache_path = $cd . $cache_id;
    if (file_exists($cache_path)) {
       // d($cache);
        $cache_path = pathToURL($cache_path);
        return $cache_path;
    } else {


    return site_url('api_html/thumbnail_img') . "?&src=" . $base_src . "&width=" . $width . "&height=" . $height.'&cache_id='.$cache_id;
    }

    //require_once ();
    $surl = site_url();
    $local = false;

    $media_url = MEDIA_URL;
    $media_url = trim($media_url);
    $src = str_replace('{SITE_URL}', $surl, $src);
    $src = str_replace('%7BSITE_URL%7D', $surl, $src);

    // $src = str_replace('%7BSITE_URL%7D', $surl, $src);

    /*
     */

    if (strstr($src, $surl) or strpos($src, $surl)) {

        $src = str_replace($surl . '/', $surl, $src);
        //$src = str_replace($media_url, '', $src);
        $src = str_replace($surl, '', $src);
        $src = ltrim($src, DS);
        $src = ltrim($src, '/');
        $src = rtrim($src, DS);
        $src = rtrim($src, '/');
        //$src = MEDIAFILES . $src;
        $src = MW_ROOTPATH . $src;
        $src = normalize_path($src, false);

    } else {

        // $dl_file = MEDIAFILES . 'downloaded' . DS . md5($src) . basename($src);
        //
        // if (!file_exists($dl_file)) {
        // $is_dl = url_download($src, false, $dl_file);
        // } else {
        // $is_dl = 1;
        // }
        // if ($is_dl == true) {
        // $src = $dl_file;
        // } else {
        //
        // }
        if ($src == false) {
            return pixum($width, $height);
        }
    }
    $cd = CACHEDIR . 'thumbnail' . DS;
    if (!is_dir($cd)) {
        mkdir_recursive($cd);
    }

    $cache = crc32($src . $width . $height) . basename($src);

    $cache = str_replace(' ', '_', $cache);
    $cache_path = $cd . $cache;

    if (file_exists($cache_path)) {
//$src1 = encode_var($src);

//$base_src = basename($src);

        //return site_url('api_html/thumbnail_img'). "?filename=" . $base_src   . "&src=" . $src1  . "&width=" . $width."&height=" . $height;
    } else {
        //

        if (file_exists($src)) {

            $src1 = encode_var($src);

            $base_src = basename($src);

            //return site_url('api_html/thumbnail_img'). "?filename=" . $base_src   . "&src=" . $src1  . "&width=" . $width."&height=" . $height;


            $ext = get_file_extension($src);

            if (strtolower($ext) == 'svg') {
                $res1 = file_get_contents($src);
                $res1 = svgScaleHack($res1, $width, $height);
                file_put_contents($cache_path, $res1);

            } else {

                $tn = new \mw\Thumbnailer($src);
                $thumbOptions = array('maxLength' => $height, 'width' => $width);
                $tn->createThumb($thumbOptions, $cache_path);

                unset($tn);


            }
        }

    }
    if (file_exists($cache_path)) {

        $cache_path = pathToURL($cache_path);
        return $cache_path;
    } else {
        return pixum($width, $height);
    }
    return false;
    //d($src);
}

function get_pictures($params)
{

    $table = MW_TABLE_PREFIX . 'media';
    $params = parse_params($params);
    /*
     // if (is_string($params)) {
     // $params = parse_str($params, $params2);
     // $params = $params2;
     // }*/

    if (isset($params['for'])) {
        $params['rel'] = db_get_assoc_table_name($params['for']);
    }

    //$params['debug'] = $table;
    $params['limit'] = 1000;
    $params['table'] = $table;
    $params['orderby'] = 'position ASC';
    $data = get($params);

    return $data;
}

api_expose('create_media_dir');

function create_media_dir($params)
{
    only_admin_access();
    $resp = array();
    $target_path = MEDIAFILES . 'uploaded' . DS;
    $fn_path = MEDIAFILES;
    if (isset($_REQUEST["path"]) and trim($_REQUEST["path"]) != '') {
        $_REQUEST["path"] = urldecode($_REQUEST["path"]);

        //$string = preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), $_REQUEST["path"]);
        $fn_path = MW_USERFILES . DS . $_REQUEST["path"] . DS;
        $fn_path = normalize_path($fn_path, false);
    }
    if (!isset($_REQUEST["name"])) {
        $resp = array('error' => 'You must send new_folder parameter');
    } else {
        $fn_new_folder_path = $_REQUEST["name"];
        $fn_new_folder_path = urldecode($fn_new_folder_path);
        $fn_new_folder_path_new = $fn_path . DS . $fn_new_folder_path;
        $fn_path = normalize_path($fn_new_folder_path_new, false);
        // d($fn_path);
        if (!is_dir($fn_path)) {
            mkdir_recursive($fn_path);
            $resp = array('success' => "Folder " . $fn_path . ' is created');

        } else {
            $resp = array('error' => "Folder " . $fn_new_folder_path . ' already exists');

        }

    }

    return $resp;

}

api_expose('delete_media_file');

function delete_media_file($params)
{
    only_admin_access();

    $target_path = MEDIAFILES . 'uploaded' . DS;
    $target_path = normalize_path($target_path, 0);
    $path_restirct = MW_USERFILES;

    $fn_remove_path = $_REQUEST["path"];
    $resp = array();
    if ($fn_remove_path != false and isarr($fn_remove_path)) {
        foreach ($fn_remove_path as $key => $value) {

            $fn_remove = url2dir($value);

            if (isset($fn_remove) and trim($fn_remove) != '' and trim($fn_remove) != 'false') {
                $path = urldecode($fn_remove);
                $path = normalize_path($path, 0);
                $path = str_replace('..', '', $path);
                $path = str_replace($path_restirct, '', $path);
                $target_path = MW_USERFILES . DS . $path;
                $target_path = normalize_path($target_path, false);

                if (stristr($target_path, MEDIAFILES)) {

                    if (is_dir($target_path)) {
                        recursive_remove_directory($target_path, false);
                        $resp = array('success' => 'Directory ' . $target_path . ' is deleted');
                    } else if (is_file($target_path)) {
                        unlink($target_path);
                        $resp = array('success' => 'File ' . basename($target_path) . ' is deleted');
                    } else {
                        $resp = array('error' => 'Not valid file or folder ' . $target_path . ' ');
                    }

                } else {
                    $resp = array('error' => 'Not allowed to delete on ' . $target_path . ' ');

                }
            }

        }
    }
    return $resp;

}


function svgScaleHack($svg, $minWidth, $minHeight)
{
    $reW = '/(.*<svg[^>]* width=")([\d.]+px)(.*)/si';
    $reH = '/(.*<svg[^>]* height=")([\d.]+px)(.*)/si';
    preg_match($reW, $svg, $mw);
    preg_match($reH, $svg, $mh);

    if (!isset($mw[2]) and isset($mh[2])) {
        $mw[2] = $mh[2];
    }

    if (empty($mw)) {
        $width = floatval($minWidth);
        $height = floatval($minHeight);
    } else {
        $width = floatval($mw[2]);
        $height = floatval($mh[2]);
    }

    if (!$width || !$height) return false;

    // scale to make width and height big enough
    $scale = 1;
    if ($width < $minWidth) {
        $scale = $minWidth / $width;
    }
    if ($height < $minHeight) {
        $scale = max($scale, ($minHeight / $height));
    }
    $scale = 1;
    //$width *= $scale*2;
    //$height *= $scale*2;

    $svg = preg_replace($reW, "\${1}{$width}px\${3}", $svg);
    $svg = preg_replace($reH, "\${1}{$height}px\${3}", $svg);

    return $svg;
}