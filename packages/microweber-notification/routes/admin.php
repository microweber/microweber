<?php

declare(strict_types=1);

/**
 * Admin routes are registered by NotificationServiceProvider::registerAdminRoutes()
 * so the package works with configurable prefixes/middleware in standalone apps.
 *
 * This file is kept for documentation and optional manual include.
 */

use Illuminate\Support\Facades\Route;
use MicroweberPackages\Notification\Http\Controllers\Admin\NotificationController;

$prefix = config('microweber-notification.admin_route_prefix');
if (! is_string($prefix) || $prefix === '') {
    $prefix = function_exists('mw_admin_prefix_url_legacy')
        ? mw_admin_prefix_url_legacy()
        : 'admin';
}

/** @var list<string> $middleware */
$middleware = (array) config('microweber-notification.admin_middleware', ['web', 'admin']);

Route::name('admin.')
    ->prefix((string) $prefix)
    ->middleware($middleware)
    ->group(function (): void {
        Route::post('notification/read', [NotificationController::class, 'read'])->name('notification.read');
        Route::post('notification/reset', [NotificationController::class, 'reset'])->name('notification.reset');
        Route::post('notification/delete', [NotificationController::class, 'delete'])->name('notification.delete');
        Route::post('notification/test_mail', [NotificationController::class, 'testMail'])->name('notification.test_mail');
        Route::get('notification', [NotificationController::class, 'index'])->name('notification.index');
    });
