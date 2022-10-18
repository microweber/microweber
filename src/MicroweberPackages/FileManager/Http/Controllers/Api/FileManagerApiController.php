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
        $orderBy = $request->get('orderBy', 'modified');
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

}
