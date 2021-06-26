<?php

declare(strict_types=1);

namespace Joy\VoyagerApiAuth;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Joy\VoyagerApiAuth\Http\Resources\Auth;
use Joy\VoyagerApiAuth\Http\Resources\User;

/**
 * Class VoyagerApiAuthServiceProvider
 *
 * @category  Package
 * @package   JoyVoyagerApiAuth
 * @author    Ramakant Gangwar <gangwar.ramakant@gmail.com>
 * @copyright 2021 Copyright (c) Ramakant Gangwar (https://github.com/rxcod9)
 * @license   http://github.com/rxcod9/joy-voyager-api-auth/blob/main/LICENSE New BSD License
 * @link      https://github.com/rxcod9/joy-voyager-api-auth
 */
class ResourcesServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('joy-voyager-api-auth.auth', function ($app) {
            return new Auth(null);
        });
        $this->app->bind('joy-voyager-api-auth.user', function ($app) {
            return new User(null);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'joy-voyager-api-auth.auth',
            'joy-voyager-api-auth.user',
        ];
    }
}
