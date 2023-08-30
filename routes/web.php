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


\Illuminate\Support\Facades\Route::post('/RequestTypeTest11', function () {

    return response()->json(request()->all());


    return request()->all();
});

Route::get('sidebar-menu-build', function (){

    $mwMenu = new \MicroweberPackages\Admin\MenuBuilder\Menu('SIDEBAR_MENU');
    $mwMenu->addChild('Dashboard', [
        'uri' => 'dashboard',
        'attributes'=>['icon'=>'adwawffwa']
    ]);
    $mwMenu->addChild('Marketplace', ['route' => 'marketplace']);
    $mwMenu->addChild('Modules', ['route' => 'Modules']);
    $mwMenu->addChild('Settings', ['route' => 'Settings']);


    $mwMenu->getChild('Settings')->addChild('General', ['route' => 'Settings']);
    $mwMenu->getChild('Settings')->addChild('Users', ['route' => 'Users']);

    $mwMenu->getChild('Settings')->getChild('Users')->addChild('Add User', ['route' => 'Users']);
    $mwMenu->getChild('Settings')->getChild('Users')->addChild('All Users', ['route' => 'Users']);
    $mwMenu->getChild('Settings')->getChild('Users')->addChild('Roles', ['route' => 'Users']);

    $mwMenu->getChild('Settings')->getChild('Users')->getChild('Roles')->addChild('Permissions', ['route' => 'Permissions']);


    dd($mwMenu->menuItems->getChildren());

//    echo $mwMenu->render();


});
