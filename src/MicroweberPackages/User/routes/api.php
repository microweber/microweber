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


        // AI-53 / TICKET-E (cycle-67 2026-05-08): swap bare
        // throttle:60,1 (60 attempts/min by IP only — useless against
        // a credential-stuffing attacker who can rotate emails) for
        // the named `login` limiter declared in bootstrap/app.php
        // (5/min keyed on `login::<ip>::<email|username>`). The named
        // limiter rate-limits per-account so even a botnet rotating
        // IPs can only test 5 passwords per minute against any single
        // account.
        Route::post('login', \MicroweberPackages\User\Http\Controllers\Api\AuthController::class . '@login')
            ->name('login')
            ->middleware(['allowed_ips', 'throttle:login','web']);

        Route::post('logout', \MicroweberPackages\User\Http\Controllers\Api\AuthController::class . '@logout')
            ->name('logout')
            ->middleware(['api','web']);
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
        'web',
        //  \MicroweberPackages\App\Http\Middleware\VerifyCsrfToken::class,
        \MicroweberPackages\App\Http\Middleware\XSS::class
    ])
     ->group(function () {

        // AI-53 / TICKET-E (cycle-67 2026-05-08): swap bare
        // throttle:60,1 for the named `login` limiter (5/min keyed on
        // ip+email). See sibling change at line 43 above.
        Route::post('login', \MicroweberPackages\User\Http\Controllers\UserLoginController::class.'@login')->name('login')->middleware(['allowed_ips', 'throttle:login']);
        Route::any('logout', \MicroweberPackages\User\Http\Controllers\UserLoginController::class.'@logout')->name('logout')->excludedMiddleware(
            \MicroweberPackages\App\Http\Middleware\XSS::class
        );
        Route::post('register', \MicroweberPackages\User\Http\Controllers\UserRegisterController::class.'@register')
            ->name('register')->middleware(['allowed_ips']);

        Route::post('/forgot-password', \MicroweberPackages\User\Http\Controllers\UserForgotPasswordController::class.'@send')
            ->middleware(['throttle:120,10'])
            ->name('password.email');
        Route::post('/reset-password', \MicroweberPackages\User\Http\Controllers\UserForgotPasswordController::class.'@update')->name('password.update');

        Route::post('/profile-update', \MicroweberPackages\User\Http\Controllers\UserProfileController::class.'@update')->name('profile.update');

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
