<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/7/2020
 * Time: 5:50 PM
 */

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;




Route::name('api.auth.')
    ->prefix('api/auth')
    ->middleware([
      //  'api.public',
       \MicroweberPackages\App\Http\Middleware\XSS::class
    ])
    ->group(function () {

        Route::post('refreshToken', function (\Illuminate\Http\Request $request) {


            $user = $request->user();



            if (!$user || !$request->user()->tokenCan('refresh')) {
                return response()->json(['message' => 'Invalid refresh token'], 403);
            }

            // Create new access token
            $token = $user->createToken('access', ['access'])->plainTextToken;

            return response()->json([
                'token' => $token,
            ]);
        })->middleware(['api'])->name('refreshToken');


        Route::post('login', \MicroweberPackages\User\Http\Controllers\Api\AuthController::class . '@login')
            ->name('login')
            ->middleware(['allowed_ips', 'throttle:60,1']);

        Route::post('logout', \MicroweberPackages\User\Http\Controllers\Api\AuthController::class . '@logout')
            ->name('logout')
            ->middleware(['api']);
    });




Route::get('api/users/export_my_data', function (\Illuminate\Http\Request $request) {

    if (!is_logged()) {
        return array('error' => 'You must be logged');
    }

    $userId = (int)$request->all()['user_id'];

    $allowToExport = false;
    if ($userId == user_id()) {
        $allowToExport = true;
    } else if (is_admin()) {
        $allowToExport = true;
    }

    if ($allowToExport == false) {
        return array('error' => 'You are now allowed to export this information.');
    }

    $exportFromTables = [];
    $prefix = mw()->database_manager->get_prefix();
    $tablesList = mw()->database_manager->get_tables_list(true);
    foreach ($tablesList as $table) {
        $table = str_replace($prefix, false, $table);
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing($table);
        if (in_array('created_by', $columns)) {
            $exportFromTables[] = $table;
        }
    }

    $exportData = [];
    foreach ($exportFromTables as $exportFromTable) {
        $getData = \Illuminate\Support\Facades\DB::table($exportFromTable)->where('created_by', $userId)->get();
        if (!empty($getData)) {
            $exportData[$exportFromTable] = $getData->toArray();
        }
    }

    $json = new \Modules\Backup\Formats\JsonBackup($exportData);
    $getJson = $json->start();

    if (isset($getJson['files'][0]['filepath'])) {
        return response()->download($getJson['files'][0]['filepath'])->deleteFileAfterSend(true);
    }

})->name('api.users.export_my_data');

// Admin web
Route::prefix(mw_admin_prefix_url_legacy())->middleware(['admin'])->group(function () {
    Route::get('login', \MicroweberPackages\User\Http\Controllers\UserLoginController::class . '@index')->name('admin.login')->middleware(['allowed_ips']);
});


// OLD API SAVE USER
Route::post('api/save_user', function (\Illuminate\Http\Request $request) {
    if (!defined('MW_API_CALL')) {
        define('MW_API_CALL', true);
    }
    if (!is_logged()) {
        App::abort(403, 'Unauthorized action.');
    }

    $input = $request->all();

    return save_user($input);
})->middleware(['api'])->name('api.save_user');

Route::post('api/delete_user', function (\Illuminate\Support\Facades\Request $request) {
    if (!defined('MW_API_CALL')) {
        define('MW_API_CALL', true);
    }
    if (!is_admin()) {
        App::abort(403, 'Unauthorized action.');
    }
    $input = $request->all();
    return delete_user($input);
})->middleware(['api']);


Route::name('api.')
    ->prefix('api')
    ->middleware([
        'api.public',
        //  \MicroweberPackages\App\Http\Middleware\VerifyCsrfToken::class,
        \MicroweberPackages\App\Http\Middleware\XSS::class
    ])
    ->namespace('\MicroweberPackages\User\Http\Controllers')
    ->group(function () {

        Route::post('user_login', function () {
            return user_login(request()->all());
        })->name('user_login')->middleware(['allowed_ips', 'throttle:60,1']);

    });

Route::name('api.user.')
    ->prefix('api/user')
    ->middleware([
        'api.public',
        //  \MicroweberPackages\App\Http\Middleware\VerifyCsrfToken::class,
        \MicroweberPackages\App\Http\Middleware\XSS::class
    ])
    ->namespace('\MicroweberPackages\User\Http\Controllers')
    ->group(function () {

        Route::post('login', 'UserLoginController@login')->name('login')->middleware(['allowed_ips', 'throttle:60,1']);
        Route::any('logout', 'UserLoginController@logout')->name('logout')->excludedMiddleware(
            \MicroweberPackages\App\Http\Middleware\XSS::class
        );
        Route::post('register', 'UserRegisterController@register')->name('register')->middleware(['allowed_ips']);

        Route::post('/forgot-password', 'UserForgotPasswordController@send')
            ->middleware(['throttle:120,10'])
            ->name('password.email');
        Route::post('/reset-password', 'UserForgotPasswordController@update')->name('password.update');

        Route::post('/profile-update', 'UserProfileController@update')->name('profile.update');

    });

Route::name('api.')
    ->prefix('api')
    ->middleware([
        'api',
        //  \MicroweberPackages\App\Http\Middleware\VerifyCsrfToken::class,
        \MicroweberPackages\App\Http\Middleware\XSS::class
    ])
    ->namespace('\MicroweberPackages\User\Http\Controllers\Api')
    ->group(function () {

        Route::get('/logout', '\MicroweberPackages\User\Http\Controllers\UserLogoutController@index')->name('api.logout')
            ->middleware([
                \MicroweberPackages\App\Http\Middleware\VerifyCsrfToken::class,
                \MicroweberPackages\App\Http\Middleware\SameSiteRefererMiddleware::class
            ])
            ->excludedMiddleware(
                'api'
            );;

        Route::apiResource('user', 'UserApiController')->middleware(['admin', 'xss']);
    });
