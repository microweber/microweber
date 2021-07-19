<?php

use Illuminate\Support\Facades\Route;

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

Route::get('test123', function () {



    dump(get_content('id=1349') );

    return;
   // clearcache();

    //  $controller = app()->repository_manager->driver(\MicroweberPackages\Content\Content::class);
    $controller = app()->repository_manager->driver('content');
    dump($controller->find(1349)->media);
     dump($controller->find(1349)->contentData);
     dump($controller->find(1349)->media);


    dump($controller->find(1341)->media);
    dump($controller->find(1341)->contentData);
    dump($controller->find(1341)->media);
 dump($controller->find(1341)->media);
    dump($controller->find(1341)->contentData);
    dump($controller->find(1341)->media);
 dump($controller->find(1341)->media);
    dump($controller->find(1341)->contentData);
    dump($controller->find(1341)->media);
 dump($controller->find(1341)->media);
    dump($controller->find(1341)->contentData);
    dump($controller->find(1341)->media);



   // $content = (new MicroweberPackages\Content\Content())->find(1349);
  //  dump(get_content('id=1349') );
   // dump($content );
//    dump(content_data(1341));
//    dump(content_data(1349));

//    dump(content_data(1349));
//    dump(content_data(1349));
//    dump(content_data(1349));
//    dump(content_data(1349));
//    dump($controller->find(1349)->contentData);
//    dump($controller->find(1349)->contentData);
//    dump($controller->find(1349)->contentData);
//    dump(content_data(1349));
//    dump(content_data(1349));
//    dump(content_data(1349));
//    dump(content_data(1349));
//    dump(content_data(1349));
//    dump(content_data(1349));
//    dump($controller->find(1349)->contentData()->get());
//    dump($controller->find(1349)->contentData()->get());
//    dump($controller->find(1349)->contentData()->get());
//    dump($controller->find(1349)->contentData()->get());
//    dump($controller->find(1349)->contentData()->get());

//    dump(content_data(1349));
//    dump(content_data(1349));
//    dump(content_data(1349));
//    dump(content_data(1349));


    return;

//    $controller = app()->make(\MicroweberPackages\Repository\Controllers\ContentRepositoryController::class);
//    $controller = app()->make(\MicroweberPackages\Repository\Controllers\ContentRepositoryController::class);
//    $controller = app()->make(\MicroweberPackages\Repository\Controllers\ContentRepositoryController::class);
//    $controller = app()->make(\MicroweberPackages\Repository\Controllers\ContentRepositoryController::class);


    dump($controller->getById(1349)->contentData());
    dump($controller->getById(1349)->contentData());
    dump($controller->getById(1349)->contentData());
    dump($controller->getById(1349)->contentData());
    dump($controller->getById(1349)->contentData());
    return;
    $article = \MicroweberPackages\Content\Content::whereId(1349)->with('contentData')->first();
    $article = \MicroweberPackages\Content\Content::whereId(1349)->with('contentData')->first();
    $article = \MicroweberPackages\Content\Content::whereId(1349)->with('contentData')->first();
    $media = $article->contentData;
    $media2 = $article->contentData;
    dump($media, $media2, $article);
});

// Route::get('favorite-drink', '\App\Http\Controllers\Controller@favoriteDrink');

