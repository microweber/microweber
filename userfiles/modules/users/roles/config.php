<?php



$config = array(
    'name' => 'User Roles',
    'icon' => 'icon.png',
    'author' => 'Microweber',
    'description' => 'User Roles',
    'website' => 'http://microweber.com/',
    'help' => 'http://microweber.info/modules',
    'version' => 0.19,
    'ui' => true,
    'ui_admin' => true,
    'position' => 30,
    'categories' => 'admin',
    'providers'=>[
        \MicroweberPackages\Role\RoleServiceProvider::class,
    ],
    'controllers' => [
        'index' => "MicroweberPackages\Role\Http\Controllers\IndexController@index",
        'admin' => "MicroweberPackages\Role\Http\Controllers\IndexController@admin",
    ],


);