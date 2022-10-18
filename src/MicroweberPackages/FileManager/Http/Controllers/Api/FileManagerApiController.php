<?php
namespace MicroweberPackages\FileManager\Http\Controllers\Api;

use Illuminate\Http\Request;
use MicroweberPackages\App\Http\Controllers\Controller;

class FileManagerApiController extends Controller {

    public function list(Request $request) {

        $path = media_uploads_path();
        $pathRestirct = media_uploads_path();

        if (!empty($request->get('path'))) {
            $path = $request->get('path');
        }

        $keyword = false;
        if (!empty($request->get('keyword'))) {
            $keyword = $request->get('keyword');
        }

        $order = $request->get('order', 'asc');
        $orderBy = $request->get('orderBy', 'filemtime');
        $path = urldecode($path);

        $path = str_replace('./', '', $path);
        $path = str_replace('..', '', $path);
        $path = str_replace($pathRestirct, '', $path);

        $thumbnailSize = 150;
        if (!empty($request->get('thumbnailSize'))) {
            $thumbnailSize = (int) $request->get('thumbnailSize');
        }

        $fileFilter = [];
        $fileFilter['directory'] = $pathRestirct . $path;
        $fileFilter['restrict_path'] = $pathRestirct;
        $fileFilter['hide_files'] = ['index.html','index.php'];

        if (!empty($keyword)) {
            $fileFilter['search'] = $keyword;
        }

        $fileFilter['sort_order'] = $order;
        $fileFilter['sort_by'] = $orderBy;

        $data = [];
        $getData = app()->make(\MicroweberPackages\Utils\System\Files::class)->get($fileFilter);

        // Append dirs
        if (isset($getData['dirs']) && is_array($getData['dirs'])) {
            foreach ($getData['dirs'] as $dir) {
                $data[] = [
                    'type'=>'folder',
                    'mimeType'=> mime_content_type($dir),
                    'name'=> basename($dir),
                    'path'=> $dir,
                    'created'=> date('Y-m-d H:i:s',filectime($dir)),
                    'modified'=> date('Y-m-d H:i:s',filemtime($dir))
                ];
            }
        }

        // Append files
        if (isset($getData['files']) && is_array($getData['files'])) {
            foreach ($getData['files'] as $file) {

                $thumbnail = false;

                $ext = strtolower(get_file_extension($file));
                if ($ext == 'jpg' or $ext == 'png' or $ext == 'gif' or $ext == 'jpeg' or $ext == 'bmp') {
                    $thumbnail = thumbnail(mw()->url_manager->link_to_file($file), $thumbnailSize, $thumbnailSize, true);
                }

                $relative_path = str_ireplace(base_path(), '', $file);

                $data[] = [
                    'type'=>'file',
                    'mimeType'=> mime_content_type($file),
                    'name'=> basename($file),
                    'path'=> $relative_path,
                    'created'=> date('Y-m-d H:i:s',filectime($file)),
                    'modified'=> date('Y-m-d H:i:s',filemtime($file)),
                    'thumbnail'=> $thumbnail,
                    'url'=> dir2url($file),
                    'size'=> filesize($file),
                ];
            }
        }

        return [
            'data'=>$data,
            'query'=>[
                'order'=>$order,
                'orderBy'=>$orderBy,
                'keyword'=>$keyword,
                'path'=>$path
            ],
            'permissions'=>[
                'edit'=>true,
                'create'=>true,
                'delete'=>true,
            ],
            'pagination'=>[
                'itemsPerPage'=>15,
                'offset'=>0,
                'total'=>count($data),
                'next'=>0,
                'prev'=>0,
            ]
        ];
    }

    public function rename(Request $request)
    {
        $path = $request->get('path', false);
        $newPath = $request->get('newPath', false);

        $path = trim($path);
        $newPath = trim($newPath);

        if (empty($path)) {
            return array('error' => 'Please set file path');
        }

        if (empty($newPath)) {
            return array('error' => 'Please set new file path');
        }

    }

    public function delete(Request $request)
    {

        dump($request->all());
        return;
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

}
