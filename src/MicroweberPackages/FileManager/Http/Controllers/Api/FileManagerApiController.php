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

                $relative_path = str_ireplace(media_base_path(), '', $file);

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
        $deletePaths = $request->post('paths', false);

        if (empty($deletePaths)) {
            return array('error' => 'Please set file paths for delete.');
        }

        $resp = [];

        if (!empty($deletePaths) && is_array($deletePaths)) {

            $pathRestirct = media_base_path();

            foreach ($deletePaths as $deletePath) {

                $deletePath = trim($deletePath);
                $fnRemove = app()->url_manager->to_path($deletePath);

                if (isset($fnRemove) and trim($fnRemove) != '' and trim($fnRemove) != 'false') {

                    $path = urldecode($fnRemove);
                    $path = normalize_path($path, 0);
                    $path = str_replace('..', '', $path);
                    $path = str_replace($pathRestirct, '', $path);

                    $targetPath = media_base_path() . DS . $path;
                    $targetPath = normalize_path($targetPath, false);

                    if (stristr($targetPath, media_uploads_path())) {
                        if (is_dir($targetPath)) {
                            mw('MicroweberPackages\Utils\System\Files')->rmdir($targetPath, false);
                            $resp = array('success' => 'Directory ' . $targetPath . ' is deleted');
                        } elseif (is_file($targetPath)) {
                            unlink($targetPath);
                            $resp = array('success' => 'File ' . basename($targetPath) . ' is deleted');
                        } else {
                            $resp = array('error' => 'Not valid file or folder ' . $targetPath . ' ');
                        }
                    } else {
                        $resp = array('error' => 'Not allowed to delete on ' . $targetPath . ' ');
                    }
                }
            }
        }

        return $resp;
    }

}
