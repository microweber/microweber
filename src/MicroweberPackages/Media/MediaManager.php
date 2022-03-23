<?php

namespace MicroweberPackages\Media;

use Conner\Tagging\Model\Tagged;
use Illuminate\Support\Str;
use \Intervention\Image\ImageManagerStatic as Image;
use MicroweberPackages\Helper\HTMLClean;
use MicroweberPackages\Media\Models\Media;
use MicroweberPackages\Media\Models\MediaThumbnail;
use MicroweberPackages\Utils\Media\Thumbnailer;
use MicroweberPackages\Utils\System\Files;


class MediaManager
{
    public $app;
    public $tables = array();
    public $table_prefix = false;
    public $download_remote_images = false;
    public $no_cache;

    public $thumbnails_path_in_userfiles = 'cache/thumbnails';

    public function __construct($app = null)
    {
        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }

        $this->tables['media'] = 'media';
    }

    public function get_picture($content_id, $for = 'content', $full = false)
    {
        if ($for == 'post' or $for == 'posts' or $for == 'page' or $for == 'pages') {
            $for = 'content';
        } elseif ($for == 'category' or $for == 'categories') {
            $for = 'categories';
        }

        $media = app()->media_repository->getPictureByRelIdAndRelType($content_id, $for);
        if (!empty($media)) {

            if ($full) {
                return $media;
            }
            return $media['filename'];
        }

        return false;
    }

    public function get_first_image_from_html($html)
    {
        if (preg_match('/<img.+?src="(.+?)"/', $html, $matches)) {
            return $matches[1];
        } elseif (preg_match('/<img.+?src=\'(.+?)\'/', $html, $matches)) {
            return $matches[1];
        } else {
            return false;
        }
    }

    public function get_by_id($id)
    {

        $table = $this->tables['media'];
        $id = intval($id);
        if ($id == 0) {
            return false;
        }
        $params = array();
        $params['id'] = $id;
        $params['limit'] = 1;

        $params['table'] = $table;
        $params['cache_group'] = 'media/' . $id;

        $q = $this->get($params);
        if (is_array($q) and isset($q[0])) {
            $content = $q[0];

            if (isset($content['image_options'])) {
                $content['image_options'] = @json_decode($content['image_options'], true);
            }


        } else {
            return false;
        }

        return $content;
    }

    public function upload_progress_check()
    {
        if ($this->app->user_manager->is_admin() == false) {
            mw_error('not logged in as admin');
        }
        if (isset($_SERVER['HTTP_REFERER'])) {
            $ref_str = md5($_SERVER['HTTP_REFERER']);
        } else {
            $ref_str = 'no_HTTP_REFERER';
        }
        $ref_str = 'no_HTTP_REFERER';
        $cache_id = 'upload_progress_' . $ref_str;
        $cache_group = 'media/global';

        $cache_content = $this->app->cache_manager->get($cache_id, $cache_group);
        if ($cache_content != false) {
            if (isset($cache_content['tmp_name']) != false) {
                if (isset($cache_content['f']) != false) {
                    $filename = $cache_content['tmp_name'];
                    if (is_file($filename)) {
                        $filesize = filesize($filename);
                    }

                    $filename = $cache_content['f'];

                    if (is_file($filename)) {
                        $filesize = filesize($filename);
                    }

                    $perc = $this->app->format->percent($filesize, $cache_content['size']);

                    return $perc;
                }
            }
        }
    }

    public function upload($data)
    {
        if ($this->app->user_manager->is_admin() == false) {
            mw_error('not logged in as admin');
        }
        $files_utils = new Files();


        ini_set('upload_max_filesize', '2500M');
        // ini_set('memory_limit', '256M');
        ini_set('max_execution_time', 0);
        ini_set('post_max_size', '2500M');
        ini_set('max_input_time', 9999999);

        // ini_set("session.upload_progress.enabled", 1);
        if (isset($_SERVER['HTTP_REFERER'])) {
            $ref_str = md5($_SERVER['HTTP_REFERER']);
        } else {
            $ref_str = 'no_HTTP_REFERER';
        }
        $ref_str = 'no_HTTP_REFERER';
        $cache_id = 'upload_progress_' . $ref_str;
        $cache_group = 'media/global';

        $target_path = media_base_path() . 'uploaded' . DS;
        $target_path = normalize_path($target_path, 1);

        if (!is_dir($target_path)) {
            mkdir_recursive($target_path);
        }
        $rerturn = array();

        if ((!isset($_FILES) or empty($_FILES)) and isset($data['file'])) {
            if (isset($data['name'])) {
                $data['name'] = mw()->url_manager->clean_url_wrappers($data['name']);

                $is_dangerous_file = $files_utils->is_dangerous_file($data['name']);
                if ($is_dangerous_file) {
                    return;
                }


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

                $up = $this->base64_to_file($data['file'], $f);

                $rerturn['src'] = $this->app->url_manager->link_to_file($f);
                $rerturn['name'] = $data['name'];

                return json_encode($rerturn);
            }
        } else {
            $allowedExts = array('jpg', 'jpeg', 'gif', 'png', 'bmp');

            //$upl = $this->app->cache_manager->save($_FILES, $cache_id, $cache_group);
            foreach ($_FILES as $item) {
                $item['name'] = mw()->url_manager->clean_url_wrappers($item['name']);
                $extension = get_file_extension($item['name']);

                $is_dangerous_file = $files_utils->is_dangerous_file($data['name']);
                if ($is_dangerous_file) {
                    return;
                }

                if (in_array($extension, $allowedExts)) {
                    if ($item['error'] > 0) {
                        mw_error('Error: ' . $item['error']);
                    } else {
                        $upl = $this->app->cache_manager->save($item, $cache_id, $cache_group);

                        $f = $target_path . $item['name'];
                        if (is_file($f)) {
                            $f = $target_path . date('YmdHis') . $item['name'];
                        }

                        $progress = (array)$item;
                        $progress['f'] = $f;
                        $upl = $this->app->cache_manager->save($progress, $cache_id, $cache_group);

                        if (move_uploaded_file($item['tmp_name'], $f)) {
                            $rerturn['src'] = $this->app->url_manager->link_to_file($f);
                            $rerturn['name'] = $item['name'];
                        }
                    }
                } else {
                    mw_error('Invalid file ext');
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

    private function base64_to_file($data, $target)
    {
        touch($target);
        if (is_writable($target) == false) {
            exit("$target is not writable");
        }
        $whandle = fopen($target, 'wb');
        stream_filter_append($whandle, 'convert.base64-decode', STREAM_FILTER_WRITE);
        fwrite($whandle, $data);
        fclose($whandle);
    }

    public function reorder($data)
    {
        $adm = $this->app->user_manager->is_admin();
        if ($adm == false) {
            mw_error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        $table = $this->tables['media'];
        foreach ($data as $value) {
            if (is_array($value)) {
                $indx = array();
                $i = 0;
                foreach ($value as $value2) {
                    $indx[$i] = $value2;
                    ++$i;
                }

                $this->app->database_manager->update_position_field($table, $indx);

                return true;
                // d($indx);
            }
        }
    }

    public function delete($data)
    {
        $adm = $this->app->user_manager->is_admin();
        $ids_to_delete = array();
        if (!isset($data['id']) and (!is_array($data) and intval($data) > 0)) {
            $ids_to_delete[] = intval($data);
        } elseif (isset($data['id']) and is_array($data['id'])) {
            $ids_to_delete = $data['id'];
        } elseif (isset($data['id']) and !is_array($data['id'])) {
            $ids_to_delete[] = intval($data['id']);
        } elseif (isset($data['ids']) and is_array($data['ids'])) {
            $ids_to_delete = $data['ids'];
        } elseif (isset($data['ids']) and !is_array($data['ids'])) {
            $ids_to_delete = explode(',', $data['ids']);
        }
        if ($ids_to_delete) {
            foreach ($ids_to_delete as $delete) {
                $c_id = intval($delete);
                $pic_data = $this->get_by_id($c_id);


                if ($adm == false) {
                    if ($pic_data['created_by'] != $this->app->user_manager->id()) {
                        mw_error('Error: not logged in as admin to delete media.');
                    }
                }
//                if (isset($pic_data['filename'])) {
//                    $fn_remove = $this->app->url_manager->to_path($pic_data['filename']);
//                    if (is_file($fn_remove)) {
//                        @unlink($fn_remove);
//                    }
//                }

                $this->app->database_manager->delete_by_id('media', $c_id);
            }

            return true;
        }
    }

    public function get_all($params)
    {
        if (!is_array($params)) {
            $params = parse_params($params);
        }
        $table = $this->tables['media'];
        $params['table'] = $table;

        return $this->app->database_manager->get($params);
    }

    public function get($params)
    {
        $table = $this->tables['media'];

        if ($params != false and !is_array($params) and intval($params) > 0) {
            $params2 = array();

            $params2['rel_type'] = 'content';
            $params2['rel_id'] = intval($params);
            $params = $params2;
        } else {
            $params = parse_params($params);
        }

        if (!isset($params['rel_type']) and isset($params['for'])) {
            $params['rel_type'] = $this->app->database_manager->assoc_table_name($params['for']);
        }
        if (!isset($params['rel_type'])) {
            $params['rel_type'] = 'content';
        }

        if (!isset($params['limit'])) {
            $params['limit'] = 'nolimit';
        }

        $params['table'] = $table;
        $params['order_by'] = 'position ASC';
//d($params);
        $data = $this->app->database_manager->get($params);
        if (isset($params['single'])) {
            if (isset($data['image_options']) and !is_array($data['image_options'])) {
                $data['image_options'] = @json_decode($data['image_options'], true);
            }
            return $data;
        }
        // if (media_base_url()) {
        if (!empty($data)) {
            $return = array();
            foreach ($data as $item) {
                if (isset($item['filename']) and $item['filename'] != false) {
                    if (!stristr($item['filename'], '{SITE_URL}')
                        and !stristr($item['filename'], '{MEDIA_URL}')
                        and !stristr($item['filename'], '://')
                        and !stristr($item['filename'], media_base_url())
                    ) {
                        $item['filename'] = media_base_url() . $item['filename'];
                    }
                }

                if (isset($item['title']) and $item['title'] != '') {
                    $item['title'] = html_entity_decode($item['title']);
                    $item['title'] = strip_tags($item['title']);
                    $item['title'] = $this->app->format->clean_html($item['title']);
                }

                if (isset($item['image_options']) and !is_array($item['image_options'])) {
                    $item['image_options'] = @json_decode($item['image_options'], true);
                }


                $return[] = $item;
            }

            $data = $return;
        }
        // }

        return $data;
    }

    public function save($data)
    {
        $data = app()->html_clean->cleanArray($data);

        $s = array();

        if (isset($data['content-id'])) {
            $t = trim($data['content-id']);
            $s['rel_id'] = $t;
            $s['rel_type'] = 'content';
        } elseif (isset($data['content_id'])) {
            $t = trim($data['content_id']);
            $s['rel_id'] = $t;
            $s['rel_type'] = 'content';
            $s['rel_type'] = 'content';
        }

        if (isset($data['for'])) {
            $t = trim($data['for']);
            $t = $this->app->database_manager->assoc_table_name($t);
            $s['rel_type'] = $t;
        }
        if (isset($data['rel_id'])) {
            $t = $data['rel_id'];
            $s['rel_id'] = $t;
        }
        if (isset($data['rel_type'])) {
            $t = $data['rel_type'];
            $s['rel_type'] = $t;
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
        if (!isset($data['src']) and isset($data['filename'])) {
            $data['src'] = $data['filename'];
        }

        if (isset($data['src'])) {
            $host = (parse_url(site_url()));

            $host_dir = false;
            if (isset($host['host'])) {
                $host_dir = $host['host'];
                $host_dir = str_ireplace('www.', '', $host_dir);
                $host_dir = str_ireplace('.', '-', $host_dir);
            }

            $url2dir = $this->app->url_manager->to_path($data['src']);
            $uploaded_files_dir = media_base_path() . DS . 'uploaded';

            if (isset($s['rel_type']) and isset($s['rel_id'])) {
                $s['rel_type'] = str_replace('..', '', $s['rel_type']);

                $move_uploaded_files_dir = media_base_path() . 'downloaded' . DS . $s['rel_type'] . DS;
                $move_uploaded_files_dir_index = media_base_path() . 'downloaded' . DS . $s['rel_type'] . DS . 'index.php';

                $uploaded_files_dir = normalize_path($uploaded_files_dir);
                if (!is_dir($move_uploaded_files_dir)) {
                    mkdir_recursive($move_uploaded_files_dir);
                    @touch($move_uploaded_files_dir_index);
                }

                $url2dir = normalize_path($url2dir, false);

                $dl_remote = $this->download_remote_images;

                if (isset($data['allow_remote_download']) and $data['allow_remote_download']) {
                    $dl_remote = $data['allow_remote_download'];
                }

                if ($dl_remote and isset($data['src'])) {
                    $ext = get_file_extension($data['src']);
                    $data['media_type'] = $this->_guess_media_type_from_file_ext($ext);
                    if ($data['media_type'] != false) {
                        // starting download

                        $is_remote = strtolower($data['src']);

                        if (strstr($is_remote, 'http:') || strstr($is_remote, 'https:')) {
                            $dl_host = (parse_url($is_remote));

                            $dl_host_host_dir = false;
                            if (isset($dl_host['host'])) {
                                $dl_host_host_dir = $dl_host['host'];
                                $dl_host_host_dir = str_ireplace('www.', '', $dl_host_host_dir);
                                $dl_host_host_dir = str_ireplace('.', '-', $dl_host_host_dir);
                            }

                            $move_uploaded_files_dir = $move_uploaded_files_dir . 'external' . DS;
                            if ($dl_host_host_dir) {
                                $move_uploaded_files_dir = $move_uploaded_files_dir . $dl_host_host_dir . DS;
                            }

                            if (!is_dir($move_uploaded_files_dir)) {
                                mkdir_recursive($move_uploaded_files_dir);
                            }

                            $newfile = basename($data['src']);

                            $newfile = preg_replace('/[^\w\._]+/', '_', $newfile);
                            $newfile = $move_uploaded_files_dir . $newfile;

                            if (!is_file($newfile)) {
                                mw()->http->url($data['src'])->download($newfile);
                            }
                            if (is_file($newfile)) {
                                $url2dir = $this->app->url_manager->to_path($newfile);
                            }
                        }
                    }
                }

                if (is_file($url2dir)) {
                    $data['src'] = $this->app->url_manager->link_to_file($url2dir);
                }
            }

            $s['filename'] = $data['src'];
        }

        if (!isset($data['position']) and !isset($s['id'])) {
            $s['position'] = 9999999;
        }

        if (isset($data['for_id'])) {
            $t = trim($data['for_id']);
            $s['rel_id'] = $t;
        }

        if ((!isset($s['id']) or (isset($s['id']) and $s['id'] == 0))
            and isset($s['filename'])
            and isset($s['rel_id'])
            and isset($s['rel_type'])
        ) {
            $s['filename'] = str_replace(site_url(), '{SITE_URL}', $s['filename']);
            $check = array();
            $check['rel_type'] = $s['rel_type'];
            $check['rel_id'] = $s['rel_id'];
            $check['filename'] = $s['filename'];
            $check['single'] = true;
            $check = $this->get_all($check);
            if (isset($check['id'])) {
                $s['id'] = $check['id'];
            }
        }

        if (!isset($s['id']) and isset($s['filename']) and !isset($data['media_type'])) {
            $ext = get_file_extension($s['filename']);
            $data['media_type'] = $this->_guess_media_type_from_file_ext($ext);
        }

        if (isset($data['media_type'])) {
            $t = $this->app->database_manager->escape_string($data['media_type']);
            $s['media_type'] = $t;
        }

        if (isset($data['tags'])) {
            $s['tags'] = $data['tags'];
        }


        if (isset($data['image_options'])) {
            $s['image_options'] = @json_encode($data['image_options']);
        }

        if (isset($s['filename']) && is_array($s['filename'])) {
            if (isset($s['filename']['error'])) {
                return false;
            }
        }

        if (isset($s['rel_type']) and isset($s['rel_id'])) {
            $s['rel_id'] = trim($s['rel_id']);
            $table = $this->tables['media'];
            $s = $this->app->database_manager->extended_save($table, $s);
            $this->app->cache_manager->delete('media');

            return $s;
        } elseif (isset($s['id'])) {
            $table = $this->tables['media'];
            $s = $this->app->database_manager->extended_save($table, $s);
            $this->app->cache_manager->delete('media');

            return $s;
        } else {
            mw_error('Invalid data');
        }
    }

    public function tags($media_id = false, $return_full = false)
    {
        /* $data = array();
         $data['table'] = $this->tables['media'];
         if ($media_id) {
             $data['id'] = intval($media_id);
         }
         return $this->app->tags_manager->get_values($data, $return_full);*/

        $query = Tagged::query();
        $query->where('taggable_type', 'media');

        if ($media_id) {
            $query->where('taggable_id', $media_id);
        }
        $tags = $query->get();
        $pluck = $tags->pluck('tag_name');
        if ($return_full) {
            return $tags;
        } else {
            return $pluck->toArray();
        }
    }


    public function pixum($width = 150, $height = false)
    {
        $cache_folder = media_base_path() . 'pixum' . DS;
        if ($height) {
            $h = $height;
        } else {
            $h = $width;
        }
        $h = intval($h);
        $w = intval($width);
        if ($h == 0) {
            $h = 1;
        }

        if ($w == 0) {
            $w = 1;
        }
        $extension = '.png';

        $hash = 'pixum-' . ($h) . 'x' . $w;
        $cachefile = normalize_path($cache_folder . DS . $hash . $extension, false);
        if (!file_exists($cachefile)) {
            $dirname_file = dirname($cachefile);
            if (!is_dir($dirname_file)) {
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
        if (file_exists($cachefile)) {
            $url = media_base_url() . 'pixum/' . $hash . $extension;
        } else {
            $url = $this->app->url_manager->site('api_nosession/pixum_img') . '?width=' . $width . '&height=' . $height;
        }

        return $url;
    }

    public function pixum_img()
    {
        $mime_type = 'image/png';
        $extension = '.png';
        $cache_folder = media_base_path() . 'pixum' . DS;
        $cache_folder = normalize_path($cache_folder, true);

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

        header('Content-Type: image/png');

        if (!file_exists($cachefile)) {
            try {
                $img = @imagecreatetruecolor($w, $h);
            } catch (\Exception $e) {
                exit;
            }

            if (!$img) {
                exit;
            }


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

    private function _guess_media_type_from_file_ext($ext)
    {
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

    private function svgScaleHack($svg, $minWidth, $minHeight)
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

        if (!$width || !$height) {
            return false;
        }

        // scale to make width and height big enough
        $scale = 1;
        if ($width < $minWidth) {
            $scale = $minWidth / $width;
        }
        if ($height < $minHeight) {
            $scale = max($scale, ($minHeight / $height));
        }
        $scale = 1;

        $svg = preg_replace($reW, "\${1}{$width}px\${3}", $svg);
        $svg = preg_replace($reH, "\${1}{$height}px\${3}", $svg);

        return $svg;
    }

    public function thumbnail($src, $width = 200, $height = null, $crop = null)
    {
        if ($src == false) {
            return $this->pixum($width, $height);
        }

        if (is_array($src)) {
            extract($src);
        }

        if (!is_string($src)) {
            return $this->pixum($width, $height);
        }


        $src = html_entity_decode($src);
        $src = htmlspecialchars_decode($src);

        $surl = $this->app->url_manager->site();
        $src = str_replace('{SITE_URL}', $surl, $src);
        $src = str_replace('%7BSITE_URL%7D', $surl, $src);
        $base_src = str_replace($surl, '', $src);

        if (!isset($width)) {
            $width = 200;
        } else {
            $width = intval($width);
        }

        $src = strtok($src, '?');
        if (!isset($height)) {
            $height = 0;
        } else {
            $height = intval($height);
        }

        $cd = $this->_thumbnails_path() . $width . DS;
        $cd_relative = $this->thumbnails_path_in_userfiles . DS . $width . DS;

        $ext = strtolower(get_file_extension($base_src));


        $cache = ($base_src . $width . $height) . '.' . $ext;

        $cache = str_replace(' ', '_', $cache);

        $ext = strtolower(get_file_extension($src));


        if ($this->_is_webp_supported()) {
            $ext = 'webp';
        }
        $is_remote = false;
        if (!stristr($src, $surl)) {
            if (strstr($src, 'http://')) {
                $is_remote = true;
            } elseif (strstr($src, 'https://')) {
                $is_remote = true;
            }
        }

        $cache_id_data = array();
        $cache_id_data['mtime'] = '';
        if (!$is_remote and @is_file($base_src)) {
            $cache_id_data['mtime'] = filemtime($base_src);
        }
        $cache_id_data['base_src'] = $base_src;
        $cache_id_data['ext'] = $ext;


        $src_for_db = $src;
        if (!$is_remote) {
            $src_for_db = str_replace(site_url(), '{SITE_URL}', $src);
        }

        $cache_id_data['src'] = $src_for_db;

        $cache_id_data['width'] = $width;
        $cache_id_data['height'] = $height;
        if ($crop) {
            $cache_id_data['crop'] = $crop;
        }
        $cache_id_without_ext = 'tn-' . $this->tn_cache_id($cache_id_data);
        $cache_id = $cache_id_without_ext . '.' . $ext;
        $cache_path = $cd . $cache_id;
        $cache_path_relative = $cd_relative . $cache_id;
        $cache_path = normalize_path($cache_path, false);
        $cache_path_relative = normalize_path($cache_path_relative, false);
        //dump($cache_path);
        if ($is_remote) {
            return $src;
        } elseif (@is_file($cache_path)) {
            $cache_path = $this->app->url_manager->link_to_file($cache_path);
            return $cache_path;
        } else {
            if (stristr($base_src, 'pixum_img')) {
                return $this->pixum($width, $height);
            }
            $file_exists_local = url2dir($src);
            if (!@is_file($file_exists_local)) {
                return $this->pixum($width, $height);
            }


//            if (!defined('MW_NO_OUTPUT_CACHE')) {
//               define('MW_NO_OUTPUT_CACHE', true);
//            }

            // $cache_id_data['cache_path'] = $cache_path;
            $cache_id_data['cache_path_relative'] = $cache_path_relative;
//            if (!get_option($cache_id_without_ext, 'media_tn_temp')) {
//                save_option($cache_id_without_ext, @json_encode($cache_id_data), 'media_tn_temp');
//            }


            //$check = MediaThumbnail::where('filename', $cache_id_without_ext)->first();
            $check = app()->media_repository->getThumbnailCachedItem($cache_id_without_ext);


            if (!$check) {
                $media_tn_temp = new MediaThumbnail();
                $media_tn_temp->filename = $cache_id_without_ext;
                //$media_tn_temp->filename = null;
                $media_tn_temp->image_options = $cache_id_data;
                $media_tn_temp->save();

                return $this->app->url_manager->site('api/image-generate-tn-request/') . $media_tn_temp->id . '?saved';
            } elseif (isset($check['image_options']) and isset($check['image_options']['cache_path_relative'])) {
                $file_check = normalize_path(userfiles_path() . '' . $check['image_options']['cache_path_relative'], false);
                if (is_file($file_check)) {
                    return userfiles_url() . $check['image_options']['cache_path_relative'];
                }

            }

            return $this->app->url_manager->site('api/image-generate-tn-request/') . $check['id'] . '?finded';
        }

    }

    public function thumbnail_img($params)
    {

        if (php_can_use_func('ini_set')) {
            ini_set('memory_limit', '-1');
        }

        // ini_set('memory_limit', '256M');

        extract($params);

        if (!isset($width)) {
            $width = 200;
        } else {
            $width = intval($width);
        }

        if (!isset($height)) {
            $height = null;
        } else {
            $height = intval($height);
        }

        if (!isset($crop)) {
            $crop = null;
        } else {
            $crop = trim($crop);
        }


        if (!isset($src) or $src == false) {
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

        if (strstr($src, $surl) or strpos($src, $surl)) {
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
            $src = $this->app->url_manager->clean_url_wrappers($src);

            $src1 = media_base_path() . $src;
            $src1 = normalize_path($src1, false);

            $src2 = MW_ROOTPATH . $src;
            $src2 = normalize_path($src2, false);
            $src3 = strtolower($src2);

            if (is_file($src1)) {
                $src = $src1;
            } elseif (is_file($src2)) {
                $src = $src2;
            } elseif (is_file($src3)) {
                $src = $src3;
            } else {
                $no_img = true;

                if ($no_img) {
                    return $this->pixum_img();
                }
            }
        }
        $media_root = media_base_path();

        $cd = $this->_thumbnails_path() . $width . DS;

        if (!is_dir($cd)) {
            mkdir_recursive($cd);
        }

        $index_file = $cd . 'index.html';
        if (!is_file($index_file)) {
            file_put_contents($index_file, 'Thumbnail directory is not allowed');
        }
        if (!isset($ext)) {
            $ext = strtolower(get_file_extension($src));
        }
        if ($ext == 'webp') {
            if (!$this->_is_webp_supported()) {
                $ext = strtolower(get_file_extension($src));

            }
        }

        // $cache = md5(serialize($params)) . '.' . $ext;
        $cache = $this->tn_cache_id($params) . '.' . $ext;

        $cache = str_replace('..', '', $cache);

        if (isset($cache_id)) {
            $cache = str_replace('..', '', $cache_id);

            // $cache = url_title($cache_id);
        }
//        if(!isset($cache_path)){
//            $cache_path = $cd . $cache;
//        }
        $cache_path = $cd . $cache;
        if (isset($cache_path_relative)) {
            $cache_path = normalize_path(userfiles_path() . $cache_path_relative, false);
        }
//        if (!file_exists($cache_path)) {
//                if(!isset($cache_path)){
//                $cache_path = $cd . $cache;
//                }
//        }

        if (file_exists($cache_path)) {

            if (!isset($return_cache_path)) {

                //   if (!isset($return_cache_path) and isset($params['cache_id'])) {
                //    delete_option($cache_id, 'media_tn_temp');
                //   }


                if (!headers_sent()) {
                    if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
                        $if_modified_since = preg_replace('/;.*$/', '', $_SERVER['HTTP_IF_MODIFIED_SINCE']);
                    } else {
                        $if_modified_since = '';
                    }
                    $mtime = filemtime($src);
                    $gmdate_mod = gmdate('D, d M Y H:i:s', $mtime) . ' GMT';
                    if ($if_modified_since == $gmdate_mod) {
                        // header('HTTP/1.0 304 Not Modified');
                    }
                }
            }

        } else {
            $src = $this->app->url_manager->clean_url_wrappers($src);

            if (file_exists($src)) {
                if (($ext) == 'svg') {
                    $res1 = file_get_contents($src);
                    $res1 = $this->svgScaleHack($res1, $width, $height);
                    file_put_contents($cache_path, $res1);
                } else {
                    if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'png' || $ext == 'bmp' || $ext == 'webp') {

                        if (!$height) {
                            $height = $width;
                        }
                        $tn = new Thumbnailer($src);
                        $thumbOptions = array('height' => $height, 'width' => $width);
                        if ($crop) {
                            $thumbOptions['crop'] = $crop;
                        }

                        $cache_path_dir = dirname($cache_path);
                        if (!is_dir($cache_path_dir)) {
                            mkdir_recursive($cache_path_dir);
                        }
                        $tn->createThumb($thumbOptions, $cache_path);

//                        if (!isset($return_cache_path) and isset($params['cache_id'])) {
//                       delete_option($params['cache_id'], 'media_tn_temp');
//                        }


                        if (!defined('MW_NO_OUTPUT_CACHE')) {
                            define('MW_NO_OUTPUT_CACHE', true);
                        }


                        unset($tn);

                    } else {
                        return $this->pixum_img();
                    }
                }
            }
        }


        if (isset($return_cache_path) and $return_cache_path) {
            return $cache_path;
        }

        if (is_file($cache_path)) {
            $ext = get_file_extension($cache_path);
            if ($ext == 'jpg') {
                $ext = 'jpeg';
            }

//        if (isset($return_cache_path)) {
//            delete_option($cache_id, 'media_tn_temp');
//            return $cache_path;
//        }

            if ($ext == 'svg') {
                header('Content-Type: image/svg+xml');
            } else {
                header('Content-Type: image/' . $ext);
            }

            header('Content-Length: ' . filesize($cache_path));
            readfile($cache_path);
            exit;
        } else {
            return $this->pixum_img();
        }
    }

    function pathauto_cleanstring($string)
    {
        $url = $string;
        $url = preg_replace('~[^\\pL0-9_]+~u', '-', $url); // substitutes anything but letters, numbers and '_' with separator
        $url = trim($url, "-");

        if (function_exists('iconv')) {
            $url = iconv("utf-8", "us-ascii//TRANSLIT", $url); // TRANSLIT does the whole job
        }

        $url = strtolower($url);
        $url = preg_replace('~[^-a-z0-9_]+~', '', $url); // keep only letters, numbers, '_' and separator

        return $url;
    }

    public function create_media_dir($params)
    {
        $clean = new HTMLClean();
        $_REQUEST = $clean->cleanArray($_REQUEST);

        must_have_access();
        $resp = array();
        // $target_path = media_base_path() . 'uploaded' . DS;
        $target_path = media_uploads_path();
        $fn_path = media_base_path();
        if (isset($_REQUEST['path']) and trim($_REQUEST['path']) != '') {

            $_REQUEST['path'] = urldecode($_REQUEST['path']);
            $_REQUEST['path'] = $this->pathauto_cleanstring($_REQUEST['path']);

            if (Str::length($_REQUEST['path']) > 500) {
                return array('error' => 'Folder path is too long.');
            }

            $fn_path = $target_path . DS . $_REQUEST['path'] . DS;
            $fn_path = str_replace('..', '', $fn_path);
            $fn_path = normalize_path($fn_path, false);

            $target_path = $fn_path;
        }
        if (!isset($_REQUEST['name']) || empty($_REQUEST['name'])) {
            $resp = array('error' => 'You must send new_folder parameter');
        } else {
            $fn_new_folder_path = $_REQUEST['name'];
            $fn_new_folder_path = urldecode($fn_new_folder_path);

            $fn_new_folder_path = $this->pathauto_cleanstring($fn_new_folder_path);

            if (Str::length($fn_new_folder_path) > 500) {
                return array('error' => 'Folder path is too long.');
            }

            $fn_new_folder_path = str_replace('..', '', $fn_new_folder_path);
            $fn_new_folder_path_new = $target_path . DS . $fn_new_folder_path;
            $fn_path = normalize_path($fn_new_folder_path_new, false);
            if (!is_dir($fn_path)) {
                mkdir_recursive($fn_path);
                $resp = array('success' => 'Folder ' . $fn_path . ' is created');
            } else {
                $resp = array('error' => 'Folder ' . $fn_new_folder_path . ' already exists');
            }
        }

        return $resp;
    }

    public function delete_media_file($params)
    {
        must_have_access();

        // $target_path = media_base_path() . 'uploaded' . DS;
        $target_path = media_uploads_path();
        $target_path = normalize_path($target_path, 0);
        $path_restirct = userfiles_path();

        $fn_remove_path = $_REQUEST['path'];
        $resp = array();
        if ($fn_remove_path != false and is_array($fn_remove_path)) {
            foreach ($fn_remove_path as $key => $value) {
                $fn_remove = $this->app->url_manager->to_path($value);

                if (isset($fn_remove) and trim($fn_remove) != '' and trim($fn_remove) != 'false') {
                    $path = urldecode($fn_remove);
                    $path = normalize_path($path, 0);
                    $path = str_replace('..', '', $path);
                    $path = str_replace($path_restirct, '', $path);
                    $target_path = userfiles_path() . DS . $path;
                    $target_path = normalize_path($target_path, false);

                    //  if (stristr($target_path, media_base_path())) {
                    if (stristr($target_path, media_uploads_path())) {
                        if (is_dir($target_path)) {
                            mw('MicroweberPackages\Utils\System\Files')->rmdir($target_path, false);
                            $resp = array('success' => 'Directory ' . $target_path . ' is deleted');
                        } elseif (is_file($target_path)) {
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

    public function tn_cache_id($params)
    {

        $tnhash = crc32(json_encode($params));
        if (isset($params['src'])) {
            $src = basename($params['src']);
            $src = no_ext($src);
            if ($src) {
                $src = str_slug($src);
                $tnhash = $src . '-' . $tnhash;
            }
        }

        return $tnhash;
    }

    public function relative_media_start_path()
    {


        static $path;
        if ($path == false) {
            $environment = \App::environment();

            $path = MW_MEDIA_FOLDER_NAME . '/' . $environment . '';
        }

        return $path;
    }


    private function _is_webp_supported()
    {
        if (function_exists('imagewebp') and $_SERVER and isset($_SERVER['HTTP_ACCEPT']) and is_string($_SERVER['HTTP_ACCEPT']) and strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false) {
            return true;
        }
    }

    private function _thumbnails_path()
    {
        $userfiles_dir = userfiles_path();
        // $userfiles_dir = media_base_path();
        $userfiles_cache_dir = normalize_path($userfiles_dir . $this->thumbnails_path_in_userfiles);

        // media_base_path() . 'thumbnail' . DS;

        return $userfiles_cache_dir;
    }
}
