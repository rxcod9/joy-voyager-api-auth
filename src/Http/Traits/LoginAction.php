<?php

declare(strict_types=1);

namespace Joy\VoyagerApiAuth\Http\Traits;

use GuzzleHttp\Exception\ClientException;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;

trait LoginAction
{
    use ThrottlesLogins;

    /**
     * @OA\Post(
     *   path="/api/login",
     *   tags={"auth"},
     *   summary="Login",
     *   operationId="login",
     *   @OA\RequestBody(
     *      required=true,
     *      description="Pass user credentials",
     *      @OA\JsonContent(
     *         required={"email","password"},
     *         @OA\Property(
     *              property="email",
     *              type="string",
     *              format="email",
     *              example="user@user.com"
     *         ),
     *         @OA\Property(property="password", type="string", format="password", example="password"),
     *         @OA\Property(property="remember_me", type="boolean", example="false"),
     *      ),
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *   @OA\Response(
     *       response=403,
     *       description="Forbidden"
     *   )
     *)
     */
    public function login(Request $request)
    {
        $this->maxAttempts  = config('joy-voyager-api-auth.maxAttempts', 5) ?? 5;
        $this->decayMinutes = config('joy-voyager-api-auth.decayMinutes', 2) ?? 2;

        $loginData = $request->validate([
            'email'    => 'email|required',
            'password' => 'required',
        ]);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            $this->sendLockoutResponse($request);
        }

        if (!auth(config('joy-voyager-api-auth.webGuard', 'web'))->attempt($loginData)) {
            $this->incrementLoginAttempts($request);

            return response(['message' => 'Invalid Credentials']);
        }

        if ($request->remember_me) {
            Passport::tokensExpireIn(now()->addDays(15));
            Passport::refreshTokensExpireIn(now()->addDays(30));
            Passport::personalAccessTokensExpireIn(now()->addMonths(6));
        }

        try {
            $tokens = joyGetTokens(
                $request->email,
                $request->password
            );
        } catch (ClientException $e) {
            return new JsonResponse(
                json_decode($e->getResponse()->getBody()->getContents(), true),
                $e->getCode()
            );
        }

        $response = $this->overrideSendLoginResponse(
            $request,
            auth('web')->user(),
            $tokens,
        );
        if ($response) {
            return $response;
        }

        $authResource = app()->make('joy-voyager-api-auth.auth');

        return $authResource::make(auth('web')->user(), $tokens);
    }

    /**
     * Override send Login response.
     *
     * @param Request $request Request
     * @param mixed   $user    User
     *
     * @return mixed
     */
    protected function overrideSendLoginResponse(
        Request $request,
        $user,
        array $tokens
    ) {
        //
    }

    /**
     * Username for login.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }
}
