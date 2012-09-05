<?php

function get_picture($content_id, $for = 'post') {
    print "get_picture must be finished!";
    return false;
    $imgages = get_pictures($content_id, $for);
    //..p($imgages);
    return $imgages [0];
}

api_expose('upload_progress_check');

function upload_progress_check() {
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
    d($cache_content);
    if ($cache_content != false) {
        if (isset($cache_content ["tmp_name"]) != false) {
            if (isset($cache_content ["f"]) != false) {

                $filename = $cache_content ["tmp_name"];
                if (is_file($filename)) {
                    $filesize = filesize($filename);
                }

                $filename = $cache_content ["f"];

                if (is_file($filename)) {
                    $filesize = filesize($filename);
                }

                $perc = percent($filesize, $cache_content ["size"]);
                return $perc;
                //  d($perc);
            }
        }
    }



    //ini_set("session.upload_progress.enabled", true);
    // return $_SESSION;
}

api_expose('upload');

function upload($data) {
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


            base64_to_file($data['file'], $f);


            $rerturn['src'] = pathToURL($f);
            $rerturn['name'] = $data['name'];
        }
    } else {
        //$upl = cache_save($_FILES, $cache_id, $cache_group);
        foreach ($_FILES as $item) {
            $upl = cache_store_data($item, $cache_id, $cache_group);


            $f = $target_path . $item['name'];
            if (is_file($f)) {
                $f = $target_path . date('YmdHis') . $item['name'];
            }

            $progress = (array) $item;
            $progress['f'] = $f;
            $upl = cache_store_data($progress, $cache_id, $cache_group);

            if (move_uploaded_file($item ['tmp_name'], $f)) {
                $rerturn['src'] = pathToURL($f);
                $rerturn['name'] = $item['name'];
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

    return $rerturn;
    //var_dump($data);
    //var_dump($_FILES);
}

function base64_to_file($data, $target) {

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