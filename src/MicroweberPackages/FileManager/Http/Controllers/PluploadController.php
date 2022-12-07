<?php

namespace MicroweberPackages\FileManager\Http\Controllers;

use App\Http\Controllers\Controller;

class PluploadController extends Controller
{
    public $allowedFileTypes = [];
    public $returnPathResponse = true;

    public function __construct()
    {
        $this->middleware([
           //  \MicroweberPackages\App\Http\Middleware\VerifyCsrfToken::class,
            \MicroweberPackages\App\Http\Middleware\SameSiteRefererMiddleware::class,
            \MicroweberPackages\App\Http\Middleware\IsAjaxMiddleware::class
        ]);
    }

    public function getUploadPath()
    {
        $target_path = media_uploads_path();
        $target_path = normalize_path($target_path, 0);

        return $target_path;
    }

    public function upload()
    {

        $files_utils = new \MicroweberPackages\Utils\System\Files();
        $dangerous = $files_utils->get_dangerous_files_extentions();

        if (!empty($this->allowedFileTypes)) {
            foreach ($this->allowedFileTypes as $fileType) {
                foreach ($dangerous as $iDangerous=>$dangerousFileType) {
                    if ($dangerousFileType == $fileType) {
                        unset($dangerous[$iDangerous]);
                    }
                }
            }
        }

        if (!mw()->user_manager->session_id() or (mw()->user_manager->session_all() == false)) {
            // //session_start();
        }

        $validate_token = false;
        if (!isset($_SERVER['HTTP_REFERER'])) {
            header("HTTP/1.1 401 Unauthorized");

            die('{"jsonrpc" : "2.0", "error" : {"code":97, "message": "You are not allowed to upload"}}');
        } elseif (!stristr($_SERVER['HTTP_REFERER'], site_url())) {
            //    if (!is_logged()){
//        die('{"jsonrpc" : "2.0", "error" : {"code":98, "message": "You cannot upload from remote domains"}}');
//    }
        }

//if (!is_admin()) {
        //$validate_token = mw()->user_manager->csrf_validate($_GET);

// validation is now on middleware
//    if ($validate_token == false) {
//        header("HTTP/1.1 401 Unauthorized");
//        die('{"jsonrpc" : "2.0", "error" : {"code":98, "message": "You are not allowed to upload"}}');
//    }

        $is_ajax = mw()->url_manager->is_ajax();
        if (!$is_ajax) {
            header("HTTP/1.1 401 Unauthorized");
            die('{"jsonrpc" : "2.0", "error" : {"code":99, "message": "You are not allowed to upload"}}');
        }
//}

        $host = (parse_url(site_url()));

//$host_dir = false;
        $host_dir = 'default';


        if (defined('MW_IS_MULTISITE') and MW_IS_MULTISITE) {
            if (isset($host['host'])) {
                $host_dir = $host['host'];
                $host_dir = str_ireplace('www.', '', $host_dir);
                $host_dir = str_ireplace('.', '-', $host_dir);
            }
        }


        $fileName_ext = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';

        $is_ext = get_file_extension($fileName_ext);
        $is_ext = strtolower($is_ext);

        if (!empty($this->allowedFileTypes)) {
            $is_allowed_file = in_array($is_ext, $this->allowedFileTypes);
        } else {
            $is_allowed_file = $files_utils->is_allowed_file($fileName_ext);
        }

        if ($is_allowed_file == false) {
            header("HTTP/1.1 401 Unauthorized");
            die('{"jsonrpc" : "2.0", "error" : {"code":100, "message": "You cannot upload scripts or executable files"}}');
        }

        $allowed_to_upload = false;

        if (is_admin() != false) {
            $allowed_to_upload = true;
        } else {
            $uid = user_id();
            if ($uid != 0) {
                $user = mw()->user_manager->get_by_id($uid);
                if (!empty($user) and isset($user['is_active']) and $user['is_active'] == 1) {
                    $are_allowed = 'img';
                    $_REQUEST['path'] = 'media/' . $host_dir . DS . 'user_uploads/user/' . $user['id'] . DS;
                    if (isset($_REQUEST['autopath']) and $_REQUEST['autopath'] == 'user_hash') {
                        $up_path = md5($user['id'] . $user['created_at']);
                        $_REQUEST['path'] = 'media/' . $host_dir . DS . 'user_uploads/user_hash/' . DS . $up_path . DS;
                    }
                    $allowed_to_upload = true;
                }
            } else {
                $_REQUEST['path'] = 'media/' . $host_dir . DS . 'user_uploads/anonymous/';
                $allowed_to_upload = true;
            }


        }


        if ($allowed_to_upload == false) {
            if (isset($_REQUEST['rel_type']) and isset($_REQUEST['custom_field_id']) and trim($_REQUEST['rel_type']) != '' and trim($_REQUEST['rel_type']) != 'false') {
                $cfid = mw()->fields_manager->getById(intval($_REQUEST['custom_field_id']));
                if ($cfid == false) {
                    die('{"jsonrpc" : "2.0", "error" : {"code": 90, "message": "Custom field is not found"}}');
                } else {
                    $rel_error = false;
                    if (!isset($_REQUEST['rel_id'])) {
                        $rel_error = true;
                    }
                    if (!isset($cfid['rel_id'])) {
                        $rel_error = true;
                    }

                    if (($_REQUEST['rel_id']) != $cfid['rel_id']) {
                        $rel_error = true;
                    }


                    if ($rel_error) {
                        die('{"jsonrpc" : "2.0", "error" : {"code": 91, "message": "You are not allowed to upload"}}');
                    }
                }

                if ($cfid != false and isset($cfid['custom_field_type'])) {
                    if ($cfid['custom_field_type'] != 'upload') {
                        header("HTTP/1.1 401 Unauthorized");

                        die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Custom field is not file upload type"}}');
                    }
                    if ($cfid != false and (!isset($cfid['options']) or !isset($cfid['options']['file_types']))) {
                        header("HTTP/1.1 401 Unauthorized");

                        die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "File types is not set."}}');
                    }
                    if ($cfid != false and isset($cfid['file_types']) and empty($cfid['file_types'])) {
                        header("HTTP/1.1 401 Unauthorized");

                        die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "File types cannot by empty."}}');
                    }

                    if ($cfid != false and isset($cfid['options']) and isset($cfid['options']['file_types'])) {
                        $alloled_ft = array_values(($cfid['options']['file_types']));
                        if (empty($alloled_ft)) {
                            header("HTTP/1.1 401 Unauthorized");

                            die('{"jsonrpc" : "2.0", "error" : {"code": 104, "message": "File types cannot by empty."}}');
                        } else {
                            $are_allowed = '';
                            $fileName_ext = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';
                            foreach ($alloled_ft as $allowed_file_type_item) {
                                if (trim($allowed_file_type_item) != '' and $fileName_ext != '') {
                                    $is_ext = get_file_extension($fileName_ext);
                                    $is_ext = strtolower($is_ext);

                                    switch ($is_ext) {
                                        case 'phtml':
                                        case 'php':
                                        case 'php12':
                                        case 'php11':
                                        case 'php10':
                                        case 'php9':
                                        case 'php8':
                                        case 'php7':
                                        case 'php6':
                                        case 'php5':
                                        case 'php4':
                                        case 'php3':
                                        case 'ptml':
                                        case 'html':
                                        case 'xhtml':
                                        case 'phtml':
                                        case 'shtml':
                                        case 'htm':
                                        case 'pl':
                                        case 'cgi':
                                        case 'rb':
                                        case 'py':
                                        case 'asp':
                                        case 'htaccess':
                                        case 'exe':
                                        case 'msi':
                                        case 'sh':
                                        case 'bat':
                                        case 'vbs':
                                            $are_allowed = false;
                                            die('{"jsonrpc" : "2.0", "error" : {"code":105, "message": "You cannot upload scripts or executables"}}');

                                            break;


                                    }

                                    $are_allowed = $files_utils->get_allowed_files_extensions_for_upload($allowed_file_type_item);

                                    $pass_type_check = false;
                                    if ($are_allowed != false) {
                                        $are_allowed_a = explode(',', $are_allowed);
                                        if (!empty($are_allowed_a)) {
                                            foreach ($are_allowed_a as $are_allowed_a_item) {
                                                $are_allowed_a_item = strtolower(trim($are_allowed_a_item));
                                                $is_ext = strtolower(trim($is_ext));

                                                if ($are_allowed_a_item == '*') {
                                                    $pass_type_check = 1;
                                                }

                                                if ($are_allowed_a_item != '' and $are_allowed_a_item == $is_ext) {
                                                    $pass_type_check = 1;
                                                }
                                            }
                                        }
                                    }
                                    if ($pass_type_check == false) {
                                        header("HTTP/1.1 401 Unauthorized");

                                        die('{"jsonrpc" : "2.0", "error" : {"code":106, "message": "You can only upload ' . $are_allowed . ' files."}}');
                                    } else {
                                        if (!isset($_REQUEST['captcha'])) {
                                            if (!$validate_token) {
                                                header("HTTP/1.1 401 Unauthorized");

                                                die('{"jsonrpc" : "2.0", "error" : {"code":107, "message": "Please enter the captcha answer!"}}');
                                            }
                                        } else {
                                            $cap = mw()->user_manager->session_get('captcha');
                                            if ($cap == false) {
                                                header("HTTP/1.1 401 Unauthorized");

                                                die('{"jsonrpc" : "2.0", "error" : {"code":108, "message": "You must load a captcha first!"}}');
                                            }
                                            $validate_captcha = $this->app->captcha_manager->validate($_REQUEST['captcha']);
                                            if (!$validate_captcha) {
                                                header("HTTP/1.1 401 Unauthorized");

                                                die('{"jsonrpc" : "2.0", "error" : {"code":109, "message": "Invalid captcha answer! "}}');
                                            } else {
                                                if (!isset($_REQUEST['path'])) {
                                                    $_REQUEST['path'] = 'media/' . $host_dir . '/user_uploads' . DS . $_REQUEST['rel_type'] . DS;
                                                }
                                            }
                                        }

                                        //die('{"jsonrpc" : "2.0", "error" : {"code":98, "message": PECATA - Not finished yet."}}');
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                header("HTTP/1.1 401 Unauthorized");

                die('{"jsonrpc" : "2.0", "error" : {"code": 110, "message": "Only admin can upload."}, "id" : "id"}');
            }
        }


        if (!is_admin()) {

            die('{"jsonrpc" : "2.0", "error" : {"code": 111, "message": "Only admin can upload."}, "id" : "id"}');

            return response(array(
                'error' => _e('Please enter captcha answer!', true),
                'captcha_error' => true,
                'form_data_required' => 'captcha',
                'form_data_required_params' => array('captcha_parent_for_id' => $_REQUEST['rel_id']),
                'form_data_module' => 'captcha'
            ));
        }


// Settings
//$target_path = media_base_path() . DS;
//$target_path = media_base_path() . DS . $host_dir . DS . 'uploaded' . DS;

        $target_path = $this->getUploadPath();

        $path_restirct = userfiles_path(); // the path the script should access
        if (isset($_REQUEST['path']) and trim($_REQUEST['path']) != '' and trim($_REQUEST['path']) != 'false') {
            $path = urldecode($_REQUEST['path']);

            $path = html_entity_decode($path);
            $path = htmlspecialchars_decode($path, ENT_NOQUOTES);

            //$path = urldecode($path);
            $path = str_replace('%2F', '/', $path);
            //$path = str_replace('%25252F','/',$path);

            $path = normalize_path($path, 0);
            $path = sanitize_path($path);
            $path = str_replace($path_restirct, '', $path);

            // $target_path = userfiles_path() . DS . $path;
            $target_path = media_uploads_path() . DS . $path;
            $target_path = normalize_path($target_path, 1);
        }

        $targetDir = $target_path;
        if (!is_dir($targetDir)) {
            mkdir_recursive($targetDir);
        }
//$targetDir = 'uploads';

        $cleanupTargetDir = true;
// Remove old files
        $maxFileAge = 5 * 3600;
// Temp file age in seconds
// 5 minutes execution time
        @set_time_limit(5 * 60);

// Uncomment this one to fake upload time
// usleep(5000);
// Get parameters
        $chunk = isset($_REQUEST['chunk']) ? intval($_REQUEST['chunk']) : 0;
        $chunks = isset($_REQUEST['chunks']) ? intval($_REQUEST['chunks']) : 0;
        $fileName = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';

// Clean the fileName for security reasons
        $fileNameExtension = get_file_extension($fileName);
        $fileName = \MicroweberPackages\Helper\URLify::filter($fileName);
//$fileName = url_title($fileName);
//$fileName = preg_replace('/[\p{P}\p{Zs}\w\._]+/u', "", $fileName);
// $fileName = preg_replace('/[^\w\._]+/', '_', $fileName);
        $fileName = preg_replace('/\s+\d+%|\)/', '', $fileName);
        $fileName = preg_replace("/[\/\&%#\$]/", "_", $fileName);
        $fileName = preg_replace("/[\"\']/", " ", $fileName);
        $fileName = str_replace(array('(', ')', "'", "!", "`", "*", "#"), '_', $fileName);
        $fileName = str_replace(' ', '_', $fileName);
        $fileName = str_replace('..', '.', $fileName);
        $fileName = strtolower($fileName);
        $fileName = mw()->url_manager->clean_url_wrappers($fileName);
        $fileName = substr($fileName, 0, -(strlen($fileNameExtension)));
        $fileName = $fileName . '.' . $fileNameExtension;


        $fileName_uniq = date('ymdhis') . uniqid() . $fileName;
// Make sure the fileName is unique but only if chunking is disabled
        if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
            $ext = strrpos($fileName, '.');

            $fileName_a = substr($fileName, 0, $ext);
            $fileName_b = substr($fileName, $ext);

            $fileName_b = strtolower($fileName_b);

            $count = 1;
            while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b)) {
                ++$count;
            }

            $fileName = $fileName_a . '_' . $count . $fileName_b;
        }

        $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
        $filePath_uniq = $targetDir . DIRECTORY_SEPARATOR . $fileName_uniq;

// Create target dir
        if (!is_dir($targetDir)) {
            @mkdir_recursive($targetDir);
        }

        $has_index = $targetDir . DIRECTORY_SEPARATOR . 'index.html';

        if (!is_file($has_index)) {
            @touch($has_index);
        }

// Remove old temp files
        if ($cleanupTargetDir && is_dir($targetDir) && ($dir = opendir($targetDir))) {
            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge) && ($tmpfilePath != "{$filePath}.part")) {
                    @unlink($tmpfilePath);
                }
            }

            closedir($dir);
        } else {
            die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
        }


        if (isset($_SERVER['CONTENT_LENGTH']) and isset($_FILES['file'])) {
            $filename_log = mw()->url_manager->slug($fileName);
            $check = mw()->log_manager->get('one=true&no_cache=true&is_system=y&created_at=[mt]30 min ago&field=upload_size&rel=uploader&rel_id=' . $filename_log . '&user_ip=' . user_ip());
            $upl_size_log = $_SERVER['CONTENT_LENGTH'];
            if (is_array($check) and isset($check['id'])) {
                $upl_size_log = intval($upl_size_log) + intval($check['value']);
                mw()->log_manager->save('no_cache=true&is_system=y&field=upload_size&rel=uploader&rel_id=' . $filename_log . '&value=' . $upl_size_log . '&user_ip=' . user_ip() . '&id=' . $check['id']);
            } else {
                mw()->log_manager->save('no_cache=true&is_system=y&field=upload_size&rel=uploader&rel_id=' . $filename_log . '&value=' . $upl_size_log . '&user_ip=' . user_ip());
            }
        }

// Look for the content type header
        if (isset($_SERVER['HTTP_CONTENT_TYPE'])) {
            $contentType = $_SERVER['HTTP_CONTENT_TYPE'];
        }

        if (isset($_SERVER['CONTENT_TYPE'])) {
            $contentType = $_SERVER['CONTENT_TYPE'];
        }

// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
        $is_image = false;

        $engine = 'plupload';


        if ($engine == 'plupload') {


            if (isset($contentType)) {
                if (strpos($contentType, 'multipart') !== false) {
                    if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
                        //uploading successfully done
                    } else {
                        throw new UploadException($_FILES['file']['error']);
                    }
                }

                if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {

                    // Open temp file
                    $out = fopen("{$filePath}.part", $chunk == 0 ? 'wb' : 'ab');
                    if ($out) {
                        // Read binary input stream and append it to temp file
                        $in = fopen($_FILES['file']['tmp_name'], 'rb');

                        if ($in) {
                            while ($buff = fread($in, 4096)) {
                                fwrite($out, $buff);
                            }
                        } else {
                            header("HTTP/1.1 401 Unauthorized");

                            die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
                        }
                        fclose($in);
                        fclose($out);

                        @unlink($_FILES['file']['tmp_name']);
                    } else {
                        header("HTTP/1.1 401 Unauthorized");

                        die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
                    }
                } else {
                    header("HTTP/1.1 401 Unauthorized");

                    die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
                }
            } else {
                // Open temp file
                $out = fopen("{$filePath}.part", $chunk == 0 ? 'wb' : 'ab');
                if ($out) {
                    // Read binary input stream and append it to temp file
                    $in = fopen('php://input', 'rb');

                    if ($in) {
                        while ($buff = fread($in, 4096)) {
                            fwrite($out, $buff);
                        }
                    } else {
                        header("HTTP/1.1 401 Unauthorized");

                        die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
                    }

                    fclose($in);
                    fclose($out);
                } else {
                    header("HTTP/1.1 401 Unauthorized");

                    die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
                }
            }

        } else {


        }


        $rerturn = array();


// Check if file has been uploaded
        if (!$chunks || $chunk == $chunks - 1) {
            // Strip the temp .part suffix off
            $newfile = $filePath;
            if (is_file($newfile)) {
                $newfile = $filePath_uniq;
            }


            rename("{$filePath}.part", $newfile);
            $filePath = $newfile;

            $automatic_image_resize_on_upload = get_option('automatic_image_resize_on_upload', 'website') == 'y';
            $automatic_image_resize_on_upload_disabled = get_option('automatic_image_resize_on_upload', 'website') == 'd';

            if (is_file($filePath) and !$chunks || $chunk == $chunks - 1) {
                $ext = get_file_extension($filePath);
                $ext = strtolower($ext);
                if (function_exists('finfo_open') and function_exists('finfo_file')) {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
                    $mime = @finfo_file($finfo, $filePath);
                    if ($mime) {
                        $upl_mime_ext = explode('/', $mime);
                        $upl_mime_ext = end($upl_mime_ext);
                        $upl_mime_ext = explode('-', $upl_mime_ext);
                        $upl_mime_ext = end($upl_mime_ext);
                        $upl_mime_ext = strtolower($upl_mime_ext);

                        if (in_array($upl_mime_ext, $dangerous)) {
                            die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Cannot upload mime type ' . $upl_mime_ext . '"}, "id" : "id"}');
                        }
                    }
                    finfo_close($finfo);
                }

                if ($ext == 'gif' || $ext == 'jpg' || $ext == 'jpeg' || $ext === 'jpe' || $ext == 'png' || $ext == 'svg') {

                    $valid = false;
                    if ($ext === 'jpg' || $ext === 'jpeg' || $ext === 'jpe') {

                        // This will clear exif data - security issue
                        $imgCreatedFromJpeg = @imagecreatefromjpeg($filePath);
                        if ($imgCreatedFromJpeg) {
                            imagejpeg($imgCreatedFromJpeg, $filePath);  // this will create fresh new image without exif sensitive data
                            $valid = true;
                        }
                    } else if ($ext === 'png') {

                        $imgCreatedFromPng = @imagecreatefrompng($filePath);
                        if ($imgCreatedFromPng) {

                            // keep bg color transparent
                            imagealphablending($imgCreatedFromPng, false);
                            imagesavealpha($imgCreatedFromPng, true);

                            imagepng($imgCreatedFromPng, $filePath);  // this will create fresh new image without exif sensitive data
                            $valid = true;
                        }


                    } else if ($ext === 'gif') {


                        $imgCreatedFromGif = @imagecreatefromgif($filePath);

                        if ($imgCreatedFromGif) {

                            $filePathOld = stream_get_meta_data(tmpfile())['uri'];
                            copy($filePath, $filePathOld);
                            remove_exif_data($filePathOld, $filePath);
                            unlink($filePathOld);

                            $valid = true;
                        }

                    } else if ($ext === 'svg') {
                        $valid = false;
                        if (is_file($filePath)) {
                            $sanitizer = new \enshrined\svgSanitize\Sanitizer();
                            // Load the dirty svg
                            $dirtySVG = file_get_contents($filePath);
                            // Pass it to the sanitizer and get it back clean
                            try {
                                $cleanSVG = $sanitizer->sanitize($dirtySVG);
                                $valid = true;
                            } catch (\Exception $e) {
                                $valid = false;
                            }

                            if ($valid) {
                                file_put_contents($filePath, $cleanSVG);
                            }

                        }


                    } else {
                        $valid = false;
                    }

                    if (!$valid) {
                        @unlink($filePath);
                        die('{"jsonrpc" : "2.0", "error" : {"code": 107, "message": "File is not an image"}, "id" : "id"}');
                    }
                }

            }


            if ($is_ext == 'gif' || $is_ext == 'jpg' || $is_ext == 'jpeg' || $is_ext == 'png') {
                try {

                    $size = getimagesize($filePath);
                    $is_image = true;
                    $filesize = filesize($filePath);
                    $rerturn['file_size'] = $filesize;
                    $rerturn['file_size_human'] = mw()->format->human_filesize($filesize);
                    $rerturn['image_size'] = $size;
                    // $auto_resize_treshold = 10000000; // 10MiB
                    $auto_resize_treshold = 2000000; // 2MiB

                    if ($is_ext == 'jpg' || $is_ext == 'jpeg' || $is_ext == 'png') {
                        $rerturn['automatic_image_resize_is_enabled'] = $automatic_image_resize_on_upload;
                        if (!$automatic_image_resize_on_upload and $filesize > $auto_resize_treshold) {
                            // if image is big, ask to enable resizing
                            $rerturn['ask_user_to_enable_auto_resizing'] = 1;
                            $rerturn['ask_user_to_enable_auto_resizing_filesize'] = $filesize;

                        }
                        if (!$automatic_image_resize_on_upload_disabled and $automatic_image_resize_on_upload and $filesize > $auto_resize_treshold) {
                            $maxDim = 1980;
                            //@ini_set('memory_limit', '256M');

                            list($width, $height, $type, $attr) = $size;
                            if ($width > $maxDim || $height > $maxDim) {
//                        $d1 = dirname($filePath);
                                $d2 = basename($filePath);
//                        $target_filename = $d1 . DS . 'auto_resized_' . $d2;
                                $target_filename = $filePath;
                                $fn = $filePath;
                                $ratio = $size[0] / $size[1]; // width/height
                                if ($ratio > 1) {
                                    $width = $maxDim;
                                    $height = $maxDim / $ratio;
                                } else {
                                    $width = $maxDim * $ratio;
                                    $height = $maxDim;
                                }
                                $src = imagecreatefromstring(file_get_contents($fn));
                                $dst = imagecreatetruecolor($width, $height);

                                if ($is_ext == 'png') {
                                    // save transparency in alpha channel
                                    imagealphablending($dst, false);
                                    imagesavealpha($dst, true);

                                }
                                imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
                                imagedestroy($src);

                                if ($is_ext == 'png') {
                                    imagepng($dst, $target_filename); // adjust format as needed

                                } else if ($is_ext == 'jpg' || $is_ext == 'jpeg') {
                                    imagejpeg($dst, $target_filename); // adjust format as needed
                                }

                                $rerturn['image_was_auto_resized'] = 1;
                                $rerturn['image_was_auto_resized_msg'] = "Image was automatically resized because it was " . $rerturn['file_size_human'];

                                imagedestroy($dst);
                            }
                        }
                    }


                } catch (Exception $e) {
                    @unlink($filePath);

                    die('{"jsonrpc" : "2.0", "error" : {"code": 107, "message": "File is not an image"}, "id" : "id"}');

                }
            }

            mw()->log_manager->delete('is_system=y&rel=uploader&created_at=[lt]30 min ago');
            mw()->log_manager->delete('is_system=y&rel=uploader&session_id=' . mw()->user_manager->session_id());
        }

        $f_name = explode(DS, $filePath);
        $f_name = end($f_name);

        $filePath = mw()->url_manager->link_to_file($filePath);

        $jsonResponse['uploaded_success'] = true;

        if ($this->returnPathResponse) {
            $jsonResponse['src'] = $filePath;
            $jsonResponse['name'] = $f_name;

            if (isset($upl_size_log) and $upl_size_log > 0) {
                $jsonResponse['bytes_uploaded'] = $upl_size_log;
            }
        }

        return response()->json($jsonResponse, 200);
    }
}
