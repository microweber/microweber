<?php

/*
use MicroweberPackages\Translation\Models\Translation;

function loadLanguageByLocale($locale) {

    $translatedLanguageLines = [];

    $languageFiles = [];
    $languageFiles[] = userfiles_path() . 'language' . DIRECTORY_SEPARATOR . $locale . '.json';

    if (empty($locale) || $locale == 'en') {
        $languageFiles[] = mw_includes_path() . 'language' . DIRECTORY_SEPARATOR . 'en.json';
    } else {
        $languageFiles[] = normalize_path(mw_includes_path() . 'language' . DIRECTORY_SEPARATOR . $locale . '.json', false);
    }

    foreach ($languageFiles as $languageFile) {
        if (is_file($languageFile)) {
            $languageContent = file_get_contents($languageFile);
            $languageVariables = json_decode($languageContent, true);
            if (isset($languageVariables) and is_array($languageVariables)) {
                foreach ($languageVariables as $languageVariableKey => $languageVariableValue) {
                    $translatedLanguageLines[$languageVariableKey] = $languageVariableValue;
                }
            }
        }
    }

    return $translatedLanguageLines;
}

function migrateLanguages()
{
    $enTranslations = loadLanguageByLocale('en');

    $locales = [];
    $files = glob(normalize_path(mw_includes_path() . 'language') . '/*');
    foreach ($files as $file) {
        if (strpos($file, 'json') !== false) {
            $locale = basename($file);
            $locale = str_replace('.json', '', $locale);
            $locales[] = $locale;
        }
    }



    $saveTranslations = [];
    foreach($enTranslations as $enTranslationKey=>$enTranslationValue) {
        $saveTranslations[$enTranslationKey]['en'] = $enTranslationValue;
    }



    $locales = [];
    $locales[] = 'bg';

    foreach ($locales as $locale) {
        $getCurrentLocale = loadLanguageByLocale($locale);
        foreach($getCurrentLocale as $translationKey=>$translationValue) {
            $saveTranslations[$translationKey][$locale] = $translationValue;
        }
    }

    foreach ($saveTranslations as $translation) {

        if (!isset($translation['en'])) {
            continue;
        }

        $key = $translation['en'];
        $key = trim($key);

        foreach ($translation as $locale => $text) {

            $text = trim($text);

            if (empty($text)) {
                continue;
            }

            $saveText = [
                'translation_key' => $key,
                'translation_text' => $text,
                'translation_group' => '*',
                'translation_namespace' => '*',
                'translation_locale' => $locale,
            ];

            $findTranslation = Translation::where('translation_namespace', $saveText['translation_namespace'])
                ->where('translation_group', $saveText['translation_group'])
                ->where('translation_key', $saveText['translation_key'])
                ->where('translation_locale', $saveText['translation_locale'])->first();
            if ($findTranslation == null) {
                Translation::insert($saveText);
            } else {
                $findTranslation->translation_text = $saveText['translation_text'];
                $findTranslation->save();
            }
        }
    }
}

Route::get('migrateLanguages', function () {

    //migrateLanguages();

});

Route::get('test-lang', function () {

    //  _e('Website');
    // echo '---';

      app()->setLocale('bg');
   //   _e('Login URL');
     // app()->setLocale('en');
   //  _e('Login URL');

    // echo _e('translation::all.fwawafwaf');
    echo _e('Optional thumbnail. image for use with uploaded. or embedded videos. Required . Lazy Loading. selected.');

});
*/


Route::group([
    //'middleware' => \MicroweberPackages\App\Http\Middleware\SessionlessMiddleware::class,
    'namespace' => '\MicroweberPackages\App\Http\Controllers'
], function () {
    Route::any('/apijs', 'JsCompileController@apijs');
    Route::any('apijs/{all}', array('as' => 'apijs', 'uses' => 'JsCompileController@apijs'))->where('all', '.*');
    Route::any('/apijs_settings', 'JsCompileController@apijs_settings');
    Route::any('/apijs_combined', 'JsCompileController@apijs_combined');
    Route::any('/apijs_liveedit', 'JsCompileController@apijs_liveedit');



    Route::any('/favicon.ico', function () {
        return;
    });

});

Route::get('login', '\MicroweberPackages\User\Http\Controllers\UserLoginController@loginForm')->name('login');

Route::group(['middleware' => 'static.api', 'namespace' => '\MicroweberPackages\App\Http\Controllers'], function () {
    Route::any('/userfiles/{path}', ['uses' => '\MicroweberPackages\App\Http\Controllers\ServeStaticFileContoller@serveFromUserfiles'])->where('path', '.*');
});


Route::post('/csrf', function () {
    if (is_ajax()) {
        event_trigger('mw.csrf.ajax_request');
    }
    $headers = ['Cache-Control' => 'no-cache, no-store, must-revalidate'];
    $response =  response()->json(
        ['time' =>time()], 200, $headers
    );
    $request = request();
    $middleware = app()->make(\MicroweberPackages\App\Http\Middleware\VerifyCsrfToken::class);
    return $middleware->forceAddAddXsrfTokenCookie($request,$response);


})->middleware([
    \MicroweberPackages\App\Http\Middleware\SameSiteRefererMiddleware::class,
    \MicroweberPackages\App\Http\Middleware\IsAjaxMiddleware::class,
    \MicroweberPackages\App\Http\Middleware\EncryptCookies::class,

])->name('csrf');


Route::group([
    'middleware' => [
        \MicroweberPackages\App\Http\Middleware\SameSiteRefererMiddleware::class
    ],
], function () {
    Route::any('/module/', '\MicroweberPackages\App\Http\Controllers\ApiController@module');
    Route::any('module/{all}', array('as' => 'module', 'uses' => '\MicroweberPackages\App\Http\Controllers\ApiController@module'))->where('all', '.*');
});

Route::group(['middleware' => ['public.web' ], 'namespace' => '\MicroweberPackages\App\Http\Controllers'], function () {
    Route::any('/api', 'ApiController@api');
    Route::any('/api/{slug}', 'ApiController@api');

    Route::any('api/{all}', array('as' => 'api', 'uses' => 'ApiController@api'))->where('all', '.*');
    Route::any('api_html/{all}', array('as' => 'api_html', 'uses' => 'ApiController@api_html'))->where('all', '.*');
    Route::any('/api_html', 'ApiController@api_html');
    //
    Route::any('/editor_tools', 'ApiController@editor_tools');
    Route::any('editor_tools/{all}', array('as' => 'editor_tools', 'uses' => 'ApiController@editor_tools'))->where('all', '.*');

});


Route::group(['middleware' => ['public.web' , \MicroweberPackages\App\Http\Middleware\SessionlessMiddleware::class], 'namespace' => '\MicroweberPackages\App\Http\Controllers'], function () {
    Route::any('api_nosession/{all}', array('as' => 'api', 'uses' => 'ApiController@api'))->where('all', '.*');
    Route::any('/api_nosession', 'ApiController@api');
});


// 'middleware' => 'web',
Route::group(['middleware' => 'public.web', 'namespace' => '\MicroweberPackages\App\Http\Controllers'], function () {

    Route::any('/', 'FrontendController@index')->name('home');

    $custom_admin_url = \Config::get('microweber.admin_url');
    $admin_url = 'admin';
    if ($custom_admin_url) {
        $admin_url = $custom_admin_url;
    }

    Route::any('/' . $admin_url, 'AdminController@index')->name('admin.home');
    Route::any($admin_url, array('as' => 'admin', 'uses' => 'AdminController@index'));

    Route::any($admin_url . '/{all}', array('as' => 'admin', 'uses' => 'AdminController@index'))->where('all', '.*')->name('admin.all');

    Route::any('robots.txt', 'FrontendController@robotstxt')->name('robots');

    Route::get('sitemap.xml', 'SitemapController@index')->name('sitemap.index');
    Route::get('sitemap.xml/categories', 'SitemapController@categories')->name('sitemap.categories');
    Route::get('sitemap.xml/tags', 'SitemapController@tags')->name('sitemap.tags');
    Route::get('sitemap.xml/products', 'SitemapController@products')->name('sitemap.products');
    Route::get('sitemap.xml/posts', 'SitemapController@posts')->name('sitemap.posts');
    Route::get('sitemap.xml/pages', 'SitemapController@pages')->name('sitemap.pages');

    Route::any('rss', 'RssController@index')->name('rss.index');
    Route::any('rss-products', 'RssController@products')->name('rss.products');
    Route::any('rss-posts', 'RssController@posts')->name('rss.posts');

    Route::any('{all}', array('as' => 'all', 'uses' => 'FrontendController@index'))->where('all', '.*');

});

