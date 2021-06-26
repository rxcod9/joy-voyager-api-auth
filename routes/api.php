<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use TCG\Voyager\Events\Routing;
use TCG\Voyager\Events\RoutingAdmin;
use TCG\Voyager\Events\RoutingAdminAfter;
use TCG\Voyager\Events\RoutingAfter;
use TCG\Voyager\Facades\Voyager;

/*
|--------------------------------------------------------------------------
| Voyager API Routes
|--------------------------------------------------------------------------
|
| This file is where you may override any of the routes that are included
| with VoyagerApiAuth.
|
*/

Route::group(['as' => 'joy-voyager-api-auth.'], function () {
    // event(new Routing()); @deprecated

    $namespacePrefix = '\\' . config('joy-voyager-api-auth.controllers.namespace') . '\\';

    // Public routes
    Route::group(['as' => 'user.'], function () use ($namespacePrefix): void {
        Route::post('/register', $namespacePrefix . 'VoyagerAuthController@register')->name('register');
        Route::post('/login', $namespacePrefix . 'VoyagerAuthController@login')->name('login');
        Route::post('/refreshToken', $namespacePrefix . 'VoyagerAuthController@refreshToken')->name('refreshToken');
    });

    Route::group(['middleware' => 'auth:' . joyGuard()], function () use ($namespacePrefix) {
        // event(new RoutingAdmin()); @deprecated

        Route::get('profile', ['uses' => $namespacePrefix . 'VoyagerUserController@profile', 'as' => 'profile']);

        // event(new RoutingAdminAfter()); @deprecated
    });

    // event(new RoutingAfter()); @deprecated
});
