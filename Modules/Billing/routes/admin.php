<?php

Route::name('admin.billing.')
    ->prefix(mw_admin_prefix_url() . '/billing')
    ->middleware(['admin'])
    ->namespace('Modules\Billing\Http\Controllers\Admin')
    ->group(function () {

        Route::get('/', 'AdminController@index')->name('index');
        Route::get('/users', 'AdminController@users')->name('users');
        Route::get('/subscription-plans', 'AdminController@subscriptionPlans')->name('subscription_plans');
        Route::get('/subscription-plans/groups', 'AdminController@subscriptionPlanGroups')->name('subscription_plan_groups');
        Route::get('/settings', 'AdminController@settings')->name('settings');

    });
