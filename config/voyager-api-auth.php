<?php

return [
    /*
     * The config_key for voyager-api-auth package.
     */
    'config_key' => env('VOYAGER_API_AUTH_CONFIG_KEY', 'joy-voyager-api-auth'),

    /*
     * The route_prefix for voyager-api-auth package.
     */
    'route_prefix' => env('VOYAGER_API_AUTH_ROUTE_PREFIX', 'api'),

    /*
    |--------------------------------------------------------------------------
    | Controllers config
    |--------------------------------------------------------------------------
    |
    | Here you can specify voyager controller settings
    |
    */

    'controllers' => [
        'namespace' => 'Joy\\VoyagerApiAuth\\Http\\Controllers',
    ],

    /*
    |--------------------------------------------------------------------------
    | Guard config
    |--------------------------------------------------------------------------
    |
    | Here you can specify voyager api auth guard settings
    |
    */

    'guard'        => env('VOYAGER_API_AUTH_GUARD', 'api'),
    'webGuard'     => env('VOYAGER_API_WEB_AUTH_GUARD', 'web'),

    /*
    |--------------------------------------------------------------------------
    | Throttle config
    |--------------------------------------------------------------------------
    |
    | Here you can specify voyager api auth throttle settings
    |
    */

    'maxAttempts'  => env('VOYAGER_API_AUTH_MAX_ATTEMPTS', 5),
    'decayMinutes' => env('VOYAGER_API_AUTH_DECAY_MINUTES', 2),

    /*
    |--------------------------------------------------------------------------
    | Url config
    |--------------------------------------------------------------------------
    |
    | Here you can specify voyager api auth internal url
    | in case your public url is not accessible
    | or if you're running your app inside docker
    |
    */

    'internal_url' => env('VOYAGER_API_AUTH_INTERNAL_URL', env('APP_URL', 'http://localhost')),
];
