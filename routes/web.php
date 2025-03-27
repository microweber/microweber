<?php

use Illuminate\Support\Facades\Route;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Category\Models\CategoryItem;
use MicroweberPackages\Content\Models\Content;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('xx', function () {

$req  =  request();

    $fileManagerParams = ['path' => media_uploads_path_relative()];

    $req->merge($fileManagerParams);

    $controller = new \Modules\FileManager\Http\Controllers\Api\FileManagerApiController();
    $path = media_uploads_path();
    $lsit = $controller->list($req);
dd($path,$lsit);
    // List files
    $response = $this->call('GET', route('api.file-manager.list', $fileManagerParams));




    //dump( asset('templates/big2/dist/build/app.css'));


});
