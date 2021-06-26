<?php

use GuzzleHttp\Client as GuzzleHttpClient;
use Laravel\Passport\Client;

// if (! function_exists('joyVoyagerApiAuth')) {
//     /**
//      * Helper
//      */
//     function joyVoyagerApiAuth($argument1 = null)
//     {
//         //
//     }
// }

if (!function_exists('joyGuard')) {
    /**
     * Helper
     */
    function joyGuard(): ?string
    {
        return config('joy-voyager-api-auth.guard', 'api');
    }
}

if (!function_exists('joyProvider')) {
    /**
     * Helper
     */
    function joyProvider(): ?string
    {
        return config('auth.guards.' . joyGuard() . '.provider', 'users');
    }
}

if (!function_exists('joyProviderModel')) {
    /**
     * Helper
     */
    function joyProviderModel(): ?string
    {
        return config('auth.providers.' . joyProvider() . '.model');
    }
}

if (!function_exists('joyPasswordClient')) {
    /**
     * Helper
     */
    function joyPasswordClient(): Client
    {
        $guard    = joyGuard();
        $provider = joyProvider();

        return Client::where('password_client', 1)->where('provider', $provider)->firstOrFail();
    }
}

if (!function_exists('joyGetUser')) {
    /**
     * Get user by access token.
     *
     * @return mixed
     */
    function joyGetUser($accessToken)
    {
        $http     = new GuzzleHttpClient();
        $url      = config('joy-voyager-api-auth.internal_url') . '/api/user';
        $response = $http->request('GET', $url, [
            'headers' => [
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . $accessToken,
            ],
        ]);
        return json_decode((string) $response->getBody(), true);
    }
}

if (!function_exists('joyRefreshToken')) {
    /**
     * Get token and refresh token by using refresh token.
     *
     * @return mixed
     */
    function joyRefreshToken($refreshToken)
    {
        $client   = joyPasswordClient();
        $http     = new GuzzleHttpClient();
        $url      = config('joy-voyager-api-auth.internal_url') . '/oauth/token';
        $response = $http->request('POST', $url, [
            'form_params' => [
                'grant_type'    => 'refresh_token',
                'refresh_token' => $refreshToken,
                'client_secret' => $client->secret,
                'client_id'     => $client->id,
                'scope'         => '*',
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
    }
}

if (!function_exists('joyGetTokens')) {
    /**
     * Get token and refresh token by using username password.
     *
     * @return mixed
     */
    function joyGetTokens($email, $password)
    {
        $client   = joyPasswordClient();
        $http     = new GuzzleHttpClient();
        $url      = config('joy-voyager-api-auth.internal_url') . '/oauth/token';
        $response = $http->request('POST', $url, [
            'form_params' => [
                'grant_type'    => 'password',
                'client_id'     => $client->id,
                'client_secret' => $client->secret,
                'username'      => $email,
                'password'      => $password,
                'scope'         => '*',
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
    }
}
