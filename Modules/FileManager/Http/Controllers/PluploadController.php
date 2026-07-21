<?php

namespace Modules\FileManager\Http\Controllers;

use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use MicroweberPackages\App\Http\Controllers\Controller;
use MicroweberPackages\FileUploader\FileUploaderService;

class PluploadController extends Controller
{
    public $allowedFileTypes = [];
    public $returnPathResponse = true;

    public function __construct()
    {
        $this->middleware([
            \MicroweberPackages\App\Http\Middleware\VerifyCsrfToken::class,
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

    /**
     * Get the file uploader service instance.
     */
    protected function getUploaderService(): FileUploaderService
    {
        return app('file_uploader');
    }

    public function upload()
    {
        /** @var FileUploaderService $uploaderService */
        $uploaderService = $this->getUploaderService();
        $validator = $uploaderService->validator();

        if (!app()->user_manager->session_id() or (app()->user_manager->session_all() == false)) {
            // //session_start();
        }

        $validate_token = false;
        if (!isset($_SERVER['HTTP_REFERER'])) {
            header("HTTP/1.1 401 Unauthorized");
            die('{"jsonrpc" : "2.0", "error" : {"code":97, "message": "You are not allowed to upload"}}');
        } elseif (!stristr($_SERVER['HTTP_REFERER'], site_url())) {
            // allow
        }

        $is_ajax = app()->url_manager->is_ajax();
        if (!$is_ajax) {
            header("HTTP/1.1 401 Unauthorized");
            die('{"jsonrpc" : "2.0", "error" : {"code":99, "message": "You are not allowed to upload"}}');
        }

        $host = (parse_url(site_url()));
        $host_dir = 'default';

        if (mw_is_multisite()) {
            if (isset($host['host'])) {
                $host_dir = $host['host'];
                $host_dir = str_ireplace('www.', '', $host_dir);
                $host_dir = str_ireplace('.', '-', $host_dir);
            }
        }

        $fileName_ext = request()->input('name', '');
        $is_ext = strtolower(pathinfo($fileName_ext, PATHINFO_EXTENSION));

        // Validate extension using the service
        if (!empty($this->allowedFileTypes)) {
            $is_allowed_file = in_array($is_ext, $this->allowedFileTypes);
        } else {
            $is_allowed_file = $validator->isAllowedExtension($fileName_ext);
        }

        if ($is_allowed_file == false) {
            header("HTTP/1.1 401 Unauthorized");
            die('{"jsonrpc" : "2.0", "error" : {"code":100, "message": "You cannot upload scripts or executable files"}}');
        }

        // Always block dangerous extensions
        if ($validator->isDangerousExtension($fileName_ext)) {
            header("HTTP/1.1 401 Unauthorized");
            die('{"jsonrpc" : "2.0", "error" : {"code":100, "message": "You cannot upload scripts or executable files"}}');
        }

        $allowed_to_upload = false;
        $requestPath = null;

        if (is_admin() != false) {
            $allowed_to_upload = true;
        } else {
            $uid = user_id();
            if ($uid != 0) {
                $user = app()->user_manager->get_by_id($uid);
                if (!empty($user) and isset($user['is_active']) and $user['is_active'] == 1) {
                    $requestPath = 'media/' . $host_dir . DS . 'user_uploads/user/' . $user['id'] . DS;
                    $autopath = request()->input('autopath');
                    if ($autopath == 'user_hash') {
                        $up_path = md5($user['id'] . $user['created_at']);
                        $requestPath = 'media/' . $host_dir . DS . 'user_uploads/user_hash/' . DS . $up_path . DS;
                    }
                    $allowed_to_upload = true;
                }
            } else {
                $requestPath = 'media/' . $host_dir . DS . 'user_uploads/anonymous/';
                $allowed_to_upload = true;
            }
        }

        if ($allowed_to_upload == false) {
            $rel_type = request()->input('rel_type');
            $custom_field_id = request()->input('custom_field_id');
            $rel_id = request()->input('rel_id');

            if (!empty($rel_type) && !empty($custom_field_id) && trim($rel_type) != '' && trim($rel_type) != 'false') {
                $cfid = app()->fields_manager->getById(intval($custom_field_id));
                if ($cfid == false) {
                    die('{"jsonrpc" : "2.0", "error" : {"code": 90, "message": "Custom field is not found"}}');
                } else {
                    $rel_error = false;
                    if (empty($rel_id)) {
                        $rel_error = true;
                    }
                    if (!isset($cfid['rel_id'])) {
                        $rel_error = true;
                    }
                    if ($rel_id != $cfid['rel_id']) {
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
                            $fileName_ext_check = request()->input('name', '');
                            foreach ($alloled_ft as $allowed_file_type_item) {
                                if (trim($allowed_file_type_item) != '' and $fileName_ext_check != '') {
                                    $check_ext = strtolower(pathinfo($fileName_ext_check, PATHINFO_EXTENSION));

                                    // Use the service to check for dangerous extensions
                                    if ($validator->isDangerousExtension($fileName_ext_check)) {
                                        die('{"jsonrpc" : "2.0", "error" : {"code":105, "message": "You cannot upload scripts or executables"}}');
                                    }

                                    $are_allowed = $validator->getAllowedExtensionsForCategory($allowed_file_type_item);

                                    $pass_type_check = false;
                                    if ($are_allowed != false) {
                                        $are_allowed_a = explode(',', $are_allowed);
                                        if (!empty($are_allowed_a)) {
                                            foreach ($are_allowed_a as $are_allowed_a_item) {
                                                $are_allowed_a_item = strtolower(trim($are_allowed_a_item));
                                                if ($are_allowed_a_item == '*') {
                                                    $pass_type_check = 1;
                                                }
                                                if ($are_allowed_a_item != '' and $are_allowed_a_item == $check_ext) {
                                                    $pass_type_check = 1;
                                                }
                                            }
                                        }
                                    }
                                    if ($pass_type_check == false) {
                                        header("HTTP/1.1 401 Unauthorized");
                                        die('{"jsonrpc" : "2.0", "error" : {"code":106, "message": "You can only upload ' . $are_allowed . ' files."}}');
                                    } else {
                                        $captcha = request()->input('captcha');
                                        if (empty($captcha)) {
                                            if (!$validate_token) {
                                                header("HTTP/1.1 401 Unauthorized");
                                                die('{"jsonrpc" : "2.0", "error" : {"code":107, "message": "Please enter the captcha answer!"}}');
                                            }
                                        } else {
                                            $cap = app()->user_manager->session_get('captcha');
                                            if ($cap == false) {
                                                header("HTTP/1.1 401 Unauthorized");
                                                die('{"jsonrpc" : "2.0", "error" : {"code":108, "message": "You must load a captcha first!"}}');
                                            }
                                            $validate_captcha = app()->captcha_manager->validate($captcha);
                                            if (!$validate_captcha) {
                                                header("HTTP/1.1 401 Unauthorized");
                                                die('{"jsonrpc" : "2.0", "error" : {"code":109, "message": "Invalid captcha answer! "}}');
                                            } else {
                                                if (!request()->has('path')) {
                                                    $rel_type = request()->input('rel_type');
                                                    $_REQUEST['path'] = 'media/' . $host_dir . '/user_uploads' . DS . $rel_type . DS;
                                                }
                                            }
                                        }
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
        }

        // Determine target path
        $target_path = $this->getUploadPath();
        $path_restirct = userfiles_path();
        $inputPath = request()->input('path');
        $path = null;

        if (!empty($inputPath) && trim($inputPath) != '' && trim($inputPath) != 'false') {
            $path = urldecode($inputPath);
            $path = html_entity_decode($path);
            $path = htmlspecialchars_decode($path, ENT_NOQUOTES);
            $path = str_replace('%2F', '/', $path);
            $path = normalize_path($path, 0);
            $path = sanitize_path($path);
            $path = str_replace($path_restirct, '', $path);
            $target_path = media_uploads_path() . DS . $path;
            $target_path = normalize_path($target_path, 1);
        }

        // Determine auto-resize settings
        $automatic_image_resize_on_upload = get_option('automatic_image_resize_on_upload', 'website') == 1;
        $automatic_image_resize_on_upload_disabled = get_option('automatic_image_resize_on_upload', 'website') == 'd';
        $autoResize = !$automatic_image_resize_on_upload_disabled && $automatic_image_resize_on_upload;

        // Use the service for the actual file upload processing
        $result = $uploaderService->upload(request(), [
            'targetDir' => $target_path,
            'allowedFileTypes' => $this->allowedFileTypes,
            'autoResize' => $autoResize,
            'maxDimension' => 1980,
            'disk' => 'public',
            'returnPath' => $this->returnPathResponse,
            'storeToDisk' => false, // We handle storage below for backward compat
        ]);

        if (isset($result['error']) && $result['error']) {
            $httpStatus = $result['http_status'] ?? 401;
            $errorJson = [
                'jsonrpc' => '2.0',
                'error' => [
                    'code' => $result['error_code'] ?? 100,
                    'message' => $result['error_message'] ?? 'Upload failed',
                ],
                'id' => 'id',
            ];
            return response()->json($errorJson, $httpStatus);
        }

        // Clean up logs
        if (app()->bound('log_manager')) {
            app()->log_manager->delete('is_system=y&rel=uploader&created_at=[lt]30 min ago');
            app()->log_manager->delete('is_system=y&rel=uploader&session_id=' . app()->user_manager->session_id());
        }

        // Build file URL (backward compatible)
        $filePath = $result['file_path'] ?? ($target_path . DIRECTORY_SEPARATOR . ($result['name'] ?? ''));
        $f_name = $result['name'] ?? basename($filePath);

        if (!isset($path)) {
            $path = '/';
        }

        $filenameUrl = normalize_path($path . DS . $f_name, false);
        $filenameUrl = str_replace(DS, '/', $filenameUrl);
        $filePathUrl = media_uploads_url() . $filenameUrl;

        $isFileFullyUploaded = $result['file_is_uploaded'] ?? false;
        $chunk = request()->input('chunk');
        $chunks = request()->input('chunks');
        if ($chunks && $chunk == $chunks - 1) {
            $isFileFullyUploaded = true;
        }

        if ($isFileFullyUploaded) {
            $fileRealPath = $target_path . DIRECTORY_SEPARATOR . $f_name;
            if (!is_file($fileRealPath)) {
                $fileRealPath = $filePath;
            }
            $fileBaseName = basename($fileRealPath);

            $storageInstance = Storage::disk('public');

            if ($path && $storageInstance->directoryExists($path)) {
                $storedPath = $storageInstance->putFileAs($path, new File($fileRealPath), $fileBaseName);
                if (is_file($fileRealPath)) {
                    @unlink($fileRealPath);
                }
                $filePathUrl = $storageInstance->url($storedPath);
            }
        }

        $jsonResponse = [];
        $jsonResponse['file_is_uploaded'] = $isFileFullyUploaded;
        $jsonResponse['name'] = $f_name;

        if ($this->returnPathResponse) {
            $jsonResponse['src'] = $filePathUrl;
        }

        // Merge extra info from the service (image size, auto-resize, etc.)
        $extraKeys = ['file_size', 'file_size_human', 'image_size', 'automatic_image_resize_is_enabled',
            'ask_user_to_enable_auto_resizing', 'ask_user_to_enable_auto_resizing_filesize',
            'image_was_auto_resized', 'image_was_auto_resized_msg', 'bytes_uploaded'];
        foreach ($extraKeys as $key) {
            if (isset($result[$key])) {
                $jsonResponse[$key] = $result[$key];
            }
        }

        return response()->json($jsonResponse, 200);
    }
}
