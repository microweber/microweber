<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 11/12/2020
 * Time: 2:36 PM
 */

use Illuminate\Support\Facades\Route;
use Modules\Backup\Http\Controllers\Admin\LanguageController;
use \Modules\Backup\Http\Controllers\Admin\BackupController;


Route::prefix(admin_url() . '/api/backup')
    ->middleware(['admin'])
    ->group(function () {




    });
