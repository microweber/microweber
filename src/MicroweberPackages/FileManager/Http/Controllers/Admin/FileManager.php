<?php
namespace MicroweberPackages\FileManager\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\App\Http\Controllers\Controller;

class FileManager extends Controller {

    public function listFiles(Request $request) {

        $path = media_uploads_path();
        $pathRestirct = media_uploads_path();

        if (!empty($request->get('path'))) {
            $path = $request->get('path');
        }

        $path = str_replace('./', '', $path);
        $path = str_replace('..', '', $path);
        $path = urldecode($path);
        $path = str_replace($pathRestirct, '', $path);

        $fileFilter = [];
        $fileFilter['directory'] = $pathRestirct . $path;
        $fileFilter['restrict_path'] = $pathRestirct;

        $data = [];
        $getData = mw('MicroweberPackages\Utils\System\Files')->get($fileFilter);

        if (isset($getData['files']) && is_array($getData['files'])) {
            foreach ($getData['files'] as $file) {
                $data[] = [
                    'type'=>'file',
                    'mimeType'=> '',
                    'name'=> '',
                    'path'=> $file,
                    'created'=> '',
                    'modified'=> '',
                    'thumbnail'=> '',
                    'url'=> '',
                    'size'=> '',
                ];
            }
        }

        if (isset($getData['dirs']) && is_array($getData['dirs'])) {
            foreach ($getData['dirs'] as $dir) {
                $data[] = [
                    'type'=>'folder',
                    'mimeType'=> '',
                    'name'=> '',
                    'path'=> $dir,
                    'created'=> '',
                    'modified'=> ''
                ];
            }
        }

        return [
            'data'=>$data,
            'query'=>'',
            'permissions'=>[
                'edit'=>true,
                'create'=>true,
                'delete'=>true,
            ]
        ];
    }



}