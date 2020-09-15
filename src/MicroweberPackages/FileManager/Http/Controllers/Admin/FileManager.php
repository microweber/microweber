<?php
namespace MicroweberPackages\FileManager\Http\Controllers\Admin;

use MicroweberPackages\App\Http\Controllers\Controller;

class FileManager extends Controller {

    public function listFiles() {

        $data = [];



        
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