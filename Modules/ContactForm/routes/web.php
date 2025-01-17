<?php

use Illuminate\Support\Facades\Route;
use MicroweberPackages\App\Http\Middleware\SameSiteRefererMiddleware;
use Modules\ContactForm\Http\Controllers\ContactFormController;

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

Route::post('api/contact_form_submit', [ContactFormController::class, 'submit'])
    ->middleware([
        'web',
        SameSiteRefererMiddleware::class,
    ])
    ->name('api.contact_form_submit');
