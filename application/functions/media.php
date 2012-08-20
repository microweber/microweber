<?php

function get_picture($content_id, $for = 'post') {
    print "get_picture must be finished!";
    return false;
    $imgages = get_pictures($content_id, $for);
    //..p($imgages);
    return $imgages [0];
}

api_expose('upload');

function upload($data) {
    if (is_admin() == false) {

        error('not logged in as admin');
    }
    $target_path = MEDIAFILES . 'uploaded' . DS;
    $target_path = normalize_path($target_path, 1);

    if (!is_dir($target_path)) {

        mkdir_recursive($target_path);
    }
    $rerturn = array();
    if (isset($data['file'])) {
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

        foreach ($_FILES as $item) {
            $f = $target_path . $item['name'];
            if (is_file($f)) {
                $f = $target_path . date('YmdHis') . $item['name'];
            }
            if (move_uploaded_file($item ['tmp_name'], $f)) {
                $rerturn['src'] = pathToURL($f);
                $rerturn['name'] = $item['name'];
            }
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