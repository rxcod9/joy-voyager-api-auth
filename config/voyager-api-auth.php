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
    | Here you can specify voyager api guard
    |
    */

    'guard' =>  env('VOYAGER_API_AUTH_GUARD', 'api'),
];
