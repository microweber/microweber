<?php

Route::group(['middleware' => \MicroweberPackages\App\Http\Middleware\SessionlessMiddleware::class, 'namespace' => '\MicroweberPackages\App\Http\Controllers'], function () {
    Route::any('/apijs', 'JsCompileController@apijs');
    Route::any('apijs/{all}', array('as' => 'apijs', 'uses' => 'JsCompileController@apijs'))->where('all', '.*');
    Route::any('/apijs_settings', 'JsCompileController@apijs_settings');
    Route::any('/apijs_combined', 'JsCompileController@apijs_combined');
    Route::any('/apijs_liveedit', 'JsCompileController@apijs_liveedit');

    Route::any('api_nosession/{all}', array('as' => 'api', 'uses' => 'FrontendController@api'))->where('all', '.*');
    Route::any('/api_nosession', 'FrontendController@api');
    Route::any('/favicon.ico', function () {
        return;
    });

});

Route::group(['namespace' => '\MicroweberPackages\App\Http\Controllers'], function () {

    Route::any('/', 'FrontendController@index');

    Route::any('/api', 'FrontendController@api');
    Route::any('/api/{slug}', 'FrontendController@api');

    $custom_admin_url = \Config::get('microweber.admin_url');
    $admin_url = 'admin';
    if($custom_admin_url){
        $admin_url = $custom_admin_url;
    }

    Route::any('/'.$admin_url, 'AdminController@index');
    Route::any($admin_url, array('as' => 'admin', 'uses' => 'AdminController@index'));

    Route::any($admin_url.'/{all}', array('as' => 'admin', 'uses' => 'AdminController@index'))->where('all', '.*');


    Route::any('api/{all}', array('as' => 'api', 'uses' => 'FrontendController@api'))->where('all', '.*');
    Route::any('api_html/{all}', array('as' => 'api', 'uses' => 'FrontendController@api_html'))->where('all', '.*');
    Route::any('/api_html', 'FrontendController@api_html');
    //
    Route::any('/editor_tools', 'FrontendController@editor_tools');
    Route::any('editor_tools/{all}', array('as' => 'editor_tools', 'uses' => 'FrontendController@editor_tools'))->where('all', '.*');

    Route::any('/module/', 'FrontendController@module');
    Route::any('module/{all}', array('as' => 'module', 'uses' => 'FrontendController@module'))->where('all', '.*');

    Route::any('robots.txt', 'FrontendController@robotstxt');
    Route::get('sitemap.xml', 'SitemapController@index');
    Route::any('rss', 'FrontendController@rss');
    Route::any('{all}', array('as' => 'all', 'uses' => 'FrontendController@index'))->where('all', '.*');

});

