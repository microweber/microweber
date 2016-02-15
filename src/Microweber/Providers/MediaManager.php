<?php
namespace Microweber\Providers;


class MediaManager {

    public $app;
    public $tables = array();
    public $table_prefix = false;
    public $download_remote_images = false;
    public $no_cache;


    function __construct($app = null) {


        if (!is_object($this->app)){

            if (is_object($app)){
                $this->app = $app;
            } else {
                $this->app = mw();
            }

        }

        $this->tables['media'] = 'media';

    }

    public function get_picture($content_id, $for = 'content', $full = false) {

        $arr = array();
        if ($for=='post' or $for=='posts' or $for=='page' or $for=='pages'){
            $for = 'content';
        } elseif ($for=='category' or $for=='categories') {
            $for = 'categories';
        }

        $arr['rel_type'] = $for;
        $arr['limit'] = '1';
        $arr['rel_id'] = $content_id;


        $imgages = $this->get($arr);


        if ($imgages!=false and isset($imgages[0])){
            if (isset($imgages[0]['filename']) and $full==false){
                $surl = $this->app->url_manager->site();

                $img = $this->app->format->replace_once('{SITE_URL}', $surl, $imgages[0]['filename']);

                return $img;
            } else {
                return $imgages[0];
            }

        } else {
            if ($for=='content'){
                $cont_id = $this->app->content_manager->get_by_id($content_id);

                if (isset($cont_id['content'])){
                    $img = $this->get_first_image_from_html(html_entity_decode($cont_id['content']));

                    if ($img!=false){
                        $surl = $this->app->url_manager->site();

                        $img = $this->app->format->replace_once('{SITE_URL}', $surl, $img);

                        $media_url = media_base_url();
                        if (stristr($img, $surl)){
                            return $img;
                        } else {
                            return $img;

                            return false;

                        }

                    }
                }

            }


        }

        return false;

    }

    public function get_first_image_from_html($html) {
        if (preg_match('/<img.+?src="(.+?)"/', $html, $matches)){
            return $matches[1];
        } elseif (preg_match('/<img.+?src=\'(.+?)\'/', $html, $matches)) {
            return $matches[1];
        } else {
            return false;
        }
    }

    public function get_by_id($id) {

        if ($id==false){
            return false;
        }

        $table = $this->tables['media'];
        $id = intval($id);
        if ($id==0){
            return false;
        }

        $q = "SELECT * FROM $table WHERE id='$id'  LIMIT 0,1 ";

        $params = array();
        $params['id'] = $id;
        $params['limit'] = 1;
        $params['table'] = $table;
        $params['cache_group'] = 'media/' . $id;

        $q = $this->get($params);
        if (is_array($q) and isset($q[0])){
            $content = $q[0];

        } else {
            return false;
        }

        return $content;
    }

    public function upload_progress_check() {
        if ($this->app->user_manager->is_admin()==false){

            mw_error('not logged in as admin');
        }
        if (isset($_SERVER["HTTP_REFERER"])){
            $ref_str = md5($_SERVER["HTTP_REFERER"]);
        } else {
            $ref_str = 'no_HTTP_REFERER';
        }
        $ref_str = 'no_HTTP_REFERER';
        $cache_id = 'upload_progress_' . $ref_str;
        $cache_group = 'media/global';

        $cache_content = $this->app->cache_manager->get($cache_id, $cache_group);
        if ($cache_content!=false){
            if (isset($cache_content["tmp_name"])!=false){
                if (isset($cache_content["f"])!=false){

                    $filename = $cache_content["tmp_name"];
                    if (is_file($filename)){
                        $filesize = filesize($filename);
                    }

                    $filename = $cache_content["f"];

                    if (is_file($filename)){
                        $filesize = filesize($filename);
                    }

                    $perc = $this->app->format->percent($filesize, $cache_content["size"]);

                    return $perc;
                }
            }
        }

    }

    public function upload($data) {


        if ($this->app->user_manager->is_admin()==false){

            mw_error('not logged in as admin');
        }
        ini_set("upload_max_filesize", "2500M");
        ini_set("memory_limit", "256M");
        ini_set("max_execution_time", 0);
        ini_set("post_max_size", "2500M");
        ini_set("max_input_time", 9999999);

        // ini_set("session.upload_progress.enabled", 1);
        if (isset($_SERVER["HTTP_REFERER"])){
            $ref_str = md5($_SERVER["HTTP_REFERER"]);
        } else {
            $ref_str = 'no_HTTP_REFERER';
        }
        $ref_str = 'no_HTTP_REFERER';
        $cache_id = 'upload_progress_' . $ref_str;
        $cache_group = 'media/global';

        $target_path = media_base_path() . 'uploaded' . DS;
        $target_path = normalize_path($target_path, 1);

        if (!is_dir($target_path)){

            mkdir_recursive($target_path);
        }
        $rerturn = array();

        if ((!isset($_FILES) or empty($_FILES)) and isset($data['file'])){

            if (isset($data['name'])){
                $f = $target_path . $data['name'];
                if (is_file($f)){
                    $f = $target_path . date('YmdHis') . $data['name'];
                }

                $df = strpos($data['file'], 'base64,');
                if ($df!=false){
                    //   $df = substr($data['file'], 0, $df);
                    $data['file'] = substr($data['file'], $df + 7);
                    $data['file'] = str_replace(' ', '+', $data['file']);
                    //    d($data['file']);
                }

                $up = $this->base64_to_file($data['file'], $f);

                $rerturn['src'] = $this->app->url_manager->link_to_file($f);
                $rerturn['name'] = $data['name'];

                return (json_encode($rerturn));
            }
        } else {

            $allowedExts = array("jpg", "jpeg", "gif", "png", 'bmp');

            //$upl = $this->app->cache_manager->save($_FILES, $cache_id, $cache_group);
            foreach ($_FILES as $item) {

                $extension = end(explode(".", $item["name"]));
                if (in_array($extension, $allowedExts)){
                    if ($item["error"] > 0){
                        mw_error("Error: " . $item["error"]);
                    } else {
                        $upl = $this->app->cache_manager->save($item, $cache_id, $cache_group);

                        $f = $target_path . $item['name'];
                        if (is_file($f)){
                            $f = $target_path . date('YmdHis') . $item['name'];
                        }

                        $progress = (array) $item;
                        $progress['f'] = $f;
                        $upl = $this->app->cache_manager->save($progress, $cache_id, $cache_group);

                        if (move_uploaded_file($item['tmp_name'], $f)){
                            $rerturn['src'] = $this->app->url_manager->link_to_file($f);
                            $rerturn['name'] = $item['name'];
                        }
                    }
                } else {
                    mw_error("Invalid file ext");
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
                //            $rerturn['src'] = $this->app->url_manager->link_to_file($f);
                //            $rerturn['name'] = $item['name'];
                //            fclose($target);
            }
        }

        exit(json_encode($rerturn));

    }

    private function base64_to_file($data, $target) {

        touch($target);
        if (is_writable($target)==false){
            exit("$target is not writable");
        }
        $whandle = fopen($target, 'wb');
        stream_filter_append($whandle, 'convert.base64-decode', STREAM_FILTER_WRITE);
        fwrite($whandle, $data);
        fclose($whandle);
    }

    public function reorder($data) {

        $adm = $this->app->user_manager->is_admin();
        if ($adm==false){
            mw_error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        $table = $this->tables['media'];
        foreach ($data as $value) {
            if (is_array($value)){
                $indx = array();
                $i = 0;
                foreach ($value as $value2) {
                    $indx[ $i ] = $value2;
                    $i ++;
                }

                $this->app->database_manager->update_position_field($table, $indx);

                return true;
                // d($indx);
            }
        }
    }

    public function delete($data) {

        $adm = $this->app->user_manager->is_admin();

        if (!isset($data['id']) and (!is_array($data) and intval($data) > 0)){
            $data = array('id' => intval($data));
        }
        if (isset($data['id'])){
            $c_id = intval($data['id']);
            $pic_data = $this->get_by_id($c_id);
            if ($adm==false){
                if ($pic_data['created_by']!=$this->app->user_manager->id()){
                    mw_error('Error: not logged in as admin.' . __FILE__ . __LINE__);
                }
            }
            if (isset($pic_data['filename'])){
                $fn_remove = $this->app->url_manager->to_path($pic_data['filename']);
                if (is_file($fn_remove)){
                    @unlink($fn_remove);
                }
            }

            return $this->app->database_manager->delete_by_id('media', $c_id);
        }
    }

    public function get_all($params) {
        if (!is_array($params)){
            $params = parse_params($params);
        }
        $table = $this->tables['media'];
        $params['table'] = $table;

        return $this->app->database_manager->get($params);

    }

    public function get($params) {

        $table = $this->tables['media'];

        if ($params!=false and !is_array($params) and intval($params) > 0){
            $params2 = array();

            $params2['rel_type'] = 'content';
            $params2['rel_id'] = intval($params);
            $params = $params2;

        } else {
            $params = parse_params($params);
        }


        if (!isset($params['rel_type']) and isset($params['for'])){
            $params['rel_type'] = $this->app->database_manager->assoc_table_name($params['for']);
        }
        if (!isset($params['rel_type'])){
            $params['rel_type'] = 'content';
        }

        if (!isset($params['limit'])){
            $params['limit'] = "nolimit";
        }


        $params['table'] = $table;
        $params['order_by'] = 'position ASC';


        $data = $this->app->database_manager->get($params);

        if (media_base_url()){
            if (!empty($data)){
                $return = array();
                foreach ($data as $item) {
                    if (isset($item['filename']) and $item['filename']!=false){
                        if (!stristr($item['filename'], '{SITE_URL}')
                            and !stristr($item['filename'], '{MEDIA_URL}')
                            and !stristr($item['filename'], '://')
                            and !stristr($item['filename'], media_base_url())
                        ){
                            $item['filename'] = media_base_url() . $item['filename'];

                        }
                    }


                    if (isset($item['title']) and $item['title']!=''){
                        $item['title'] = html_entity_decode($item['title']);
                        $item['title'] = strip_tags($item['title']);
                        $item['title'] = $this->app->format->clean_html($item['title']);
                    }
                    $return[] = $item;
                }
                $data = $return;
            }
        }

        return $data;
    }

    public function save($data) {

        $s = array();


        if (isset($data['content-id'])){
            $t = trim($data['content-id']);
            $s['rel_id'] = $t;
            $s['rel_type'] = 'content';
        } elseif (isset($data['content_id'])) {
            $t = trim($data['content_id']);
            $s['rel_id'] = $t;
            $s['rel_type'] = 'content';
        }

        if (isset($data['for'])){
            $t = trim($data['for']);
            $t = $this->app->database_manager->assoc_table_name($t);
            $s['rel_type'] = $t;
        }
        if (isset($data['rel_id'])){
            $t = $data['rel_id'];
            $s['rel_id'] = $t;
        }
        if (isset($data['rel_type'])){
            $t = $data['rel_type'];
            $s['rel_type'] = $t;
        }

        if (isset($data['for-id'])){
            $t = trim($data['for-id']);
            $s['rel_id'] = $t;
        }

        if (isset($data['for_id'])){
            $t = trim($data['for_id']);
            $s['rel_id'] = $t;
        }

        if (isset($data['id'])){
            $t = intval($data['id']);
            $s['id'] = $t;
        }

        if (isset($data['title'])){
            $t = ($data['title']);
            $s['title'] = $t;
        }
        if (!isset($data['src']) and isset($data['filename'])){
            $data['src'] = $data['filename'];
        }


        if (isset($data['src'])){


            $host = (parse_url(site_url()));

            $host_dir = false;
            if (isset($host['host'])){
                $host_dir = $host['host'];
                $host_dir = str_ireplace('www.', '', $host_dir);
                $host_dir = str_ireplace('.', '-', $host_dir);
            }


            $url2dir = $this->app->url_manager->to_path($data['src']);
            $uploaded_files_dir = media_base_path() . DS . 'uploaded';

            if (isset($s['rel_type']) and isset($s['rel_id'])){

                $s['rel_type'] = str_replace('..', '', $s['rel_type']);

                $move_uploaded_files_dir = media_base_path() . $host_dir . DS . $s['rel_type'] . DS;
                $move_uploaded_files_dir_index = media_base_path() . $host_dir . DS . $s['rel_type'] . DS . 'index.php';

                $uploaded_files_dir = normalize_path($uploaded_files_dir);
                if (!is_dir($move_uploaded_files_dir)){
                    mkdir_recursive($move_uploaded_files_dir);
                    @touch($move_uploaded_files_dir_index);

                }

                $url2dir = normalize_path($url2dir, false);

                $dl_remote = $this->download_remote_images;


                if (isset($data['allow_remote_download']) and $data['allow_remote_download']){
                    $dl_remote = $data['allow_remote_download'];
                }


                if ($dl_remote and isset($data['src'])){
                    $ext = get_file_extension($data['src']);
                    $data['media_type'] = $this->_guess_media_type_from_file_ext($ext);
                    if ($data['media_type']!=false){
                        // starting download

                        $is_remote = strtolower($data['src']);

                        if (strstr($is_remote, 'http:') || strstr($is_remote, 'https:')){


                            $dl_host = (parse_url($is_remote));

                            $dl_host_host_dir = false;
                            if (isset($dl_host['host'])){
                                $dl_host_host_dir = $dl_host['host'];
                                $dl_host_host_dir = str_ireplace('www.', '', $dl_host_host_dir);
                                $dl_host_host_dir = str_ireplace('.', '-', $dl_host_host_dir);
                            }

                            $move_uploaded_files_dir = $move_uploaded_files_dir . 'external' . DS;
                            if ($dl_host_host_dir){
                                $move_uploaded_files_dir = $move_uploaded_files_dir . $dl_host_host_dir . DS;
                            }


                            if (!is_dir($move_uploaded_files_dir)){
                                mkdir_recursive($move_uploaded_files_dir);
                            }

                            $newfile = basename($data['src']);

                            $newfile = preg_replace('/[^\w\._]+/', '_', $newfile);
                            $newfile = $move_uploaded_files_dir . $newfile;


                            if (!is_file($newfile)){

                                mw()->http->url($data['src'])->download($newfile);
                            }
                            if (is_file($newfile)){


                                $url2dir = $this->app->url_manager->to_path($newfile);

                            }


                        }
                    }

                }


                if (is_file($url2dir)){
                    $data['src'] = $this->app->url_manager->link_to_file($url2dir);

                }

            }

            $s['filename'] = $data['src'];
        }

        if (!isset($data['position']) and !isset($s['id'])){
            $s['position'] = 9999999;
        }

        if (isset($data['for_id'])){
            $t = trim($data['for_id']);
            $s['rel_id'] = $t;
        }

        if ((!isset($s['id']) or (isset($s['id']) and $s['id']==0))
            and isset($s['filename'])
            and isset($s['rel_id'])
            and isset($s['rel_type'])
        ){
            $s['filename'] = str_replace(site_url(), '{SITE_URL}', $s['filename']);



            $check = array();
            $check['rel_type'] = $s['rel_type'];
            $check['rel_id'] = $s['rel_id'];
            $check['filename'] = $s['filename'];
            $check['single'] = true;
            $check = $this->get($check);
            if (isset($check['id'])){
                $s['id'] = $check['id'];
            }
        }

        if (!isset($s['id']) and isset($s['filename']) and !isset($data['media_type'])){
            $ext = get_file_extension($s['filename']);
            $data['media_type'] = $this->_guess_media_type_from_file_ext($ext);

        }

        if (isset($data['media_type'])){
            $t = $this->app->database_manager->escape_string($data['media_type']);
            $s['media_type'] = $t;
        }


        if (isset($s['rel_type']) and isset($s['rel_id'])){
            $s['rel_id'] = trim($s['rel_id']);
            $table = $this->tables['media'];
            $s = $this->app->database_manager->save($table, $s);
            $this->app->cache_manager->delete('media');

            return ($s);
        } elseif (isset($s['id'])) {
            $table = $this->tables['media'];
            $s = $this->app->database_manager->save($table, $s);
            $this->app->cache_manager->delete('media');

            return ($s);
        } else {
            mw_error('Invalid data');
        }
    }

    public function thumbnail_img($params) {

        extract($params);


        if (!isset($width)){
            $width = 200;
        }


        if (!isset($height)){
            $width = 200;
        }

        if (!isset($src) or $src==false){
            return $this->pixum($width, $height);
        }
        

        $src = strtok($src, '?');

        $surl = $this->app->url_manager->site();
        $local = false;

        $media_url = media_base_url();
        $media_url = trim($media_url);
        $src = str_replace('{SITE_URL}', $surl, $src);
        $src = str_replace('%7BSITE_URL%7D', $surl, $src);
        $src = str_replace('..', '', $src);


        if (strstr($src, $surl) or strpos($src, $surl)){

            $src = str_replace($surl . '/', $surl, $src);
            //$src = str_replace($media_url, '', $src);
            $src = str_replace($surl, '', $src);
            $src = ltrim($src, DS);
            $src = ltrim($src, '/');
            $src = rtrim($src, DS);
            $src = rtrim($src, '/');
            //$src = media_base_path() . $src;
            $src = MW_ROOTPATH . $src;
            $src = normalize_path($src, false);

        } else {
            $src1 = media_base_path() . $src;
            $src1 = normalize_path($src1, false);


            $src2 = MW_ROOTPATH . $src;
            $src2 = normalize_path($src2, false);
            $src3 = strtolower($src2);

            if (is_file($src1)){
                $src = $src1;
            } elseif (is_file($src2)) {
                $src = $src2;

            } elseif (is_file($src3)) {
                $src = $src3;

            } else {
                $no_img = true;


                if ($no_img){
                    return $this->pixum_img();
                }

            }


        }
        $media_root = media_base_path();

        if (!is_writable($media_root)){
            $media_root = mw_cache_path();
        }

        $cd = $this->thumbnails_path() . $width . DS;

        if (!is_dir($cd)){
            mkdir_recursive($cd);
        }

        $index_file = $cd . 'index.html';
        if (!is_file($index_file)){
            file_put_contents($index_file, 'Thumbnail directory is not allowed');
        }
        if (!isset($ext)){
            $ext = strtolower(get_file_extension($src));
        }
        $cache = md5(serialize($params)) . '.' . $ext;

        $cache = str_replace(' ', '_', $cache);

        if (isset($cache_id)){
            $cache = str_replace(' ', '_', $cache_id);
            $cache = str_replace('..', '', $cache);
        }

        $cache_path = $cd . $cache;
         if (file_exists($cache_path)){
            if (!headers_sent()){
                if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])){
                    $if_modified_since = preg_replace('/;.*$/', '', $_SERVER['HTTP_IF_MODIFIED_SINCE']);
                } else {
                    $if_modified_since = '';
                }
                $mtime = filemtime($src);
                $gmdate_mod = gmdate('D, d M Y H:i:s', $mtime) . ' GMT';
                if ($if_modified_since==$gmdate_mod){
                    header("HTTP/1.0 304 Not Modified");
                }
            }

        } else {
            if (file_exists($src)){
                if (($ext)=='svg'){
                    $res1 = file_get_contents($src);
                    $res1 = $this->svgScaleHack($res1, $width, $height);
                    file_put_contents($cache_path, $res1);

                } else {

                    if ($ext=='jpg' || $ext=='jpeg' || $ext=='gif' || $ext=='png' || $ext=='bmp'){
                        $tn = new \Microweber\Utils\Thumbnailer($src);
                        $thumbOptions = array('maxLength' => $height, 'width' => $width);
                        $tn->createThumb($thumbOptions, $cache_path);

                        unset($tn);
                    } else {
                        return $this->pixum_img();
                    }


                }
            }

        }


        $ext = get_file_extension($cache_path);
        if ($ext=='jpg'){
            $ext = 'jpeg';
        }
        header("Content-Type: image/" . $ext);
        header("Content-Length: " . filesize($cache_path));
        readfile($cache_path);
        exit;
    }

    public function pixum($width = 150, $height = false) {

        $cache_folder = media_base_path() . 'pixum' . DS;
        if ($height){
            $h = $height;
        } else {
            $h = $width;
        }
        $h = intval($h);
        $w = intval($width);
        if ($h==0){
            $h = 1;
        }

        if ($w==0){
            $w = 1;
        }
        $extension = ".png";

        $hash = 'pixum-' . ($h) . 'x' . $w;
        $cachefile = normalize_path($cache_folder . DS . $hash . $extension, false);
        if (!file_exists($cachefile)){
            $dirname_file = dirname($cachefile);
            if (!is_dir($dirname_file)){
                mkdir_recursive($dirname_file);
            }


            $img = imagecreatetruecolor($w, $h);

            $white = imagecolorallocatealpha($img, 239, 236, 236, 0);
            imagefill($img, 0, 0, $white);
            imagealphablending($img, false);
            imagesavealpha($img, true);
            imagepng($img, $cachefile);
            imagedestroy($img);
        }
        if (file_exists($cachefile)){
            $url = media_base_url() . 'pixum/' . $hash . $extension;

        } else {
            $url = $this->app->url_manager->site('api_nosession/pixum_img') . "?width=" . $width . "&height=" . $height;
        }

        return $url;
    }

    public function pixum_img() {
        $mime_type = "image/png";
        $extension = ".png";
        $cache_folder = media_base_path() . 'pixum' . DS;
        $cache_folder = normalize_path($cache_folder, true);


        if (!is_dir($cache_folder)){
            mkdir_recursive($cache_folder);
        }

        if (isset($_REQUEST['width'])){
            $w = $_REQUEST['width'];
        } else {
            $w = 1;
        }

        if (isset($_REQUEST['height'])){
            $h = $_REQUEST['height'];
        } else {
            $h = 1;
        }
        $h = intval($h);
        $w = intval($w);
        if ($h==0){
            $h = 1;
        }

        if ($w==0){
            $w = 1;
        }
        $hash = 'pixum-' . ($h) . 'x' . $w;
        $cachefile = $cache_folder . '/' . $hash . $extension;


        header("Content-Type: image/png");

        if (!file_exists($cachefile)){
            $img = imagecreatetruecolor($w, $h);
            $white = imagecolorallocatealpha($img, 239, 236, 236, 0);
            imagefill($img, 0, 0, $white);
            imagealphablending($img, false);
            imagesavealpha($img, true);
            imagepng($img, $cachefile);
            imagedestroy($img);
            $fp = fopen($cachefile, 'rb');
            fpassthru($fp);
            exit;
        } else {
            $fp = fopen($cachefile, 'rb');
            fpassthru($fp);
            exit;
        }
    }

    private function _guess_media_type_from_file_ext($ext) {
        $type = false;
        switch ($ext) {
            case 'jpeg':
            case 'jpg':
            case 'png':
            case 'gif':
            case 'bpm':
            case 'svg':
                $type = 'picture';
                break;
            case 'avi':
            case 'ogg':
            case 'flv':
            case 'mp4':
            case 'qt':
            case 'mpeg':
                $type = 'video';
                break;
            case 'mp3':
            case 'wav':
            case 'flac':
                $type = 'audio';
                break;
        }

        return $type;
    }

    private function svgScaleHack($svg, $minWidth, $minHeight) {
        $reW = '/(.*<svg[^>]* width=")([\d.]+px)(.*)/si';
        $reH = '/(.*<svg[^>]* height=")([\d.]+px)(.*)/si';
        preg_match($reW, $svg, $mw);
        preg_match($reH, $svg, $mh);

        if (!isset($mw[2]) and isset($mh[2])){
            $mw[2] = $mh[2];
        }

        if (empty($mw)){
            $width = floatval($minWidth);
            $height = floatval($minHeight);
        } else {
            $width = floatval($mw[2]);
            $height = floatval($mh[2]);
        }

        if (!$width || !$height){
            return false;
        }

        // scale to make width and height big enough
        $scale = 1;
        if ($width < $minWidth){
            $scale = $minWidth / $width;
        }
        if ($height < $minHeight){
            $scale = max($scale, ($minHeight / $height));
        }
        $scale = 1;

        $svg = preg_replace($reW, "\${1}{$width}px\${3}", $svg);
        $svg = preg_replace($reH, "\${1}{$height}px\${3}", $svg);

        return $svg;
    }

    public function thumbnail($src, $width = 200, $height = 200) {


        if ($src==false){
            return $this->pixum($width, $height);
        }
        $src = html_entity_decode($src);
        $src = htmlspecialchars_decode($src);

        $surl = $this->app->url_manager->site();
        $src = str_replace('{SITE_URL}', $surl, $src);
        $src = str_replace('%7BSITE_URL%7D', $surl, $src);
        $base_src = str_replace($surl, '', $src);

        if (!isset($width)){
            $width = 200;
        }

        $src = strtok($src, '?');
        if (!isset($height)){
            $height = 200;
        }

        $cd = $this->thumbnails_path() . $width . DS;


        $ext = strtolower(get_file_extension($base_src));
        $cache = ($base_src . $width . $height) . '.' . $ext;

        $cache = str_replace(' ', '_', $cache);

        $ext = strtolower(get_file_extension($src));
        $is_remote = false;
        if (!stristr($src, $surl)){
            if (strstr($src, 'http://')){
                $is_remote = true;
            } elseif (strstr($src, 'https://')) {
                $is_remote = true;
            }
        }

        $cache_id = array();
        $cache_id['base_src'] = $base_src;
        $cache_id['src'] = $src;

        $cache_id['width'] = $width;
        $cache_id['height'] = $height;
        $cache_id = 'tn-' . md5(serialize($cache_id)) . '.' . $ext;
        $cache_path = $cd . $cache_id;


        if ($is_remote){
            return $src;
        } else if (file_exists($cache_path)){

            $cache_path = $this->app->url_manager->link_to_file($cache_path);

            return $cache_path;
        } else {
            if (stristr($base_src, 'pixum_img')){
                return $this->pixum($width, $height);
            }
            $tn_img_url = $this->app->url_manager->site('api_html/thumbnail_img') . "?&src=" . $base_src . "&width=" . $width . "&height=" . $height . '&cache_id=' . $cache_id;
            $tn_img_url = str_replace('(', '&#40;', $tn_img_url);
            $tn_img_url = str_replace(')', '&#41;', $tn_img_url);

            return $tn_img_url;
        }

//        $surl = $this->app->url_manager->site();
//        $local = false;
//
//        $media_url = media_base_url();
//        $media_url = trim($media_url);
//        $src = str_replace('{SITE_URL}', $surl, $src);
//        $src = str_replace('%7BSITE_URL%7D', $surl, $src);
//
//        if (strstr($src, $surl) or strpos($src, $surl)) {
//            $src = str_replace($surl . '/', $surl, $src);
//            $src = str_replace($surl, '', $src);
//            $src = ltrim($src, DS);
//            $src = ltrim($src, '/');
//            $src = rtrim($src, DS);
//            $src = rtrim($src, '/');
//            $src = MW_ROOTPATH . $src;
//            $src = normalize_path($src, false);
//
//        } else {
//            if ($src == false) {
//                return $this->pixum($width, $height);
//            }
//        }
//        $cd = media_base_path() . 'thumbnail' . DS;
//        if (!is_dir($cd)) {
//            mkdir_recursive($cd);
//        }
//
//        $cache = md5($src . $width . $height) . basename($src);
//
//        $cache = str_replace(' ', '_', $cache);
//        $cache_path = $cd . $cache;
//
//        if (!file_exists($cache_path)) {
//            if (file_exists($src)) {
//                $src1 = $this->app->format->array_to_base64($src);
//                $base_src = basename($src);
//                $ext = get_file_extension($src);
//                if (strtolower($ext) == 'svg') {
//                    $res1 = file_get_contents($src);
//                    $res1 = $this->svgScaleHack($res1, $width, $height);
//                    file_put_contents($cache_path, $res1);
//                } else {
//                    $tn = new \Microweber\Thumbnailer($src);
//                    $thumbOptions = array('maxLength' => $height, 'width' => $width);
//                    $tn->createThumb($thumbOptions, $cache_path);
//                    unset($tn);
//                }
//            }
//
//        }
//        if (file_exists($cache_path)) {
//            $cache_path = $this->app->url_manager->link_to_file($cache_path);
//            return $cache_path;
//        } else {
//            return $this->pixum($width, $height);
//        }
//        return false;
    }

    public function create_media_dir($params) {
        only_admin_access();
        $resp = array();
        $target_path = media_base_path() . 'uploaded' . DS;
        $fn_path = media_base_path();
        if (isset($_REQUEST["path"]) and trim($_REQUEST["path"])!=''){
            $_REQUEST["path"] = urldecode($_REQUEST["path"]);

            $fn_path = userfiles_path() . DS . $_REQUEST["path"] . DS;
            $fn_path = normalize_path($fn_path, false);
        }
        if (!isset($_REQUEST["name"])){
            $resp = array('error' => 'You must send new_folder parameter');
        } else {
            $fn_new_folder_path = $_REQUEST["name"];
            $fn_new_folder_path = urldecode($fn_new_folder_path);
            $fn_new_folder_path_new = $fn_path . DS . $fn_new_folder_path;
            $fn_path = normalize_path($fn_new_folder_path_new, false);
            // d($fn_path);
            if (!is_dir($fn_path)){
                mkdir_recursive($fn_path);
                $resp = array('success' => "Folder " . $fn_path . ' is created');

            } else {
                $resp = array('error' => "Folder " . $fn_new_folder_path . ' already exists');

            }

        }

        return $resp;

    }

    public function delete_media_file($params) {
        only_admin_access();

        $target_path = media_base_path() . 'uploaded' . DS;
        $target_path = normalize_path($target_path, 0);
        $path_restirct = userfiles_path();

        $fn_remove_path = $_REQUEST["path"];
        $resp = array();
        if ($fn_remove_path!=false and is_array($fn_remove_path)){
            foreach ($fn_remove_path as $key => $value) {

                $fn_remove = $this->app->url_manager->to_path($value);

                if (isset($fn_remove) and trim($fn_remove)!='' and trim($fn_remove)!='false'){
                    $path = urldecode($fn_remove);
                    $path = normalize_path($path, 0);
                    $path = str_replace('..', '', $path);
                    $path = str_replace($path_restirct, '', $path);
                    $target_path = userfiles_path() . DS . $path;
                    $target_path = normalize_path($target_path, false);

                    if (stristr($target_path, media_base_path())){

                        if (is_dir($target_path)){
                            mw('Microweber\Utils\Files')->rmdir($target_path, false);
                            $resp = array('success' => 'Directory ' . $target_path . ' is deleted');
                        } else if (is_file($target_path)){
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

    public function relative_media_start_path() {

        static $path;
        if ($path==false){
            $host = (parse_url(site_url()));
            $host_dir = false;
            if (isset($host['host'])){
                $host_dir = $host['host'];
                $host_dir = str_ireplace('www.', '', $host_dir);
                $host_dir = str_ireplace('.', '-', $host_dir);
            }
            $path = MW_MEDIA_FOLDER_NAME . '/' . $host_dir . '';
        }

        return $path;
    }

    public function thumbnails_path() {
        return media_base_path() . 'thumbnail' . DS;
    }


}