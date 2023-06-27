<?php

declare(strict_types=1);

namespace Joy\VoyagerApiAuth\Http\Traits;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

trait RegisterAction
{
    /**
     * @OA\Post(
     *   path="/api/register",
     *   tags={"auth"},
     *   summary="Register",
     *   operationId="register",
     *   @OA\RequestBody(
     *      required=true,
     *      description="User Register",
     *      @OA\JsonContent(
     *         required={"name","email","password","password_confirmation"},
     *         @OA\Property(property="name", type="string", example="User 001"),
     *         @OA\Property(
     *              property="email",
     *              type="string",
     *              format="email",
     *              example="user001@user.com"
     *         ),
     *         @OA\Property(property="password", type="string", format="password", example="password"),
     *         @OA\Property(property="password_confirmation", type="string", format="password", example="password"),
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
    public function register(Request $request)
    {
        $model = joyProviderModel();

        $validatedData = $request->validate([
            'name'     => 'required|max:100',
            'email'    => 'email|required|unique:' . (new $model())->getTable(),
            'password' => 'required|confirmed',
        ]);

        $validatedData['password'] = bcrypt($request->password);

        $user = (new $model())->create($validatedData);

        event(new Registered($user));

        $accessToken = $user->createToken('authToken')->accessToken;

        $response = $this->overrideSendRegisterResponse(
            $request,
            $user,
            $accessToken
        );
        if ($response) {
            return $response;
        }

        return response(['user' => $user, 'access_token' => $accessToken]);
    }

    /**
     * Override send Register response.
     *
     * @param Request $request Request
     * @param mixed   $user    User
     *
     * @return mixed
     */
    protected function overrideSendRegisterResponse(
        Request $request,
        $user,
        string $accessToken
    ) {
        //
    }
}
