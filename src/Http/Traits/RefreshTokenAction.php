<?php

declare(strict_types=1);

namespace Joy\VoyagerApiAuth\Http\Traits;

use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait RefreshTokenAction
{
    /**
     * @OA\Post(
     *   path="/api/refreshToken",
     *   tags={"auth"},
     *   summary="Refresh Token",
     *   operationId="refreshToken",
     *   @OA\RequestBody(
     *      required=true,
     *      description="Refresh Token",
     *      @OA\JsonContent(
     *         required={"refresh_token"},
     *         @OA\Property(
     *              property="refresh_token",
     *              type="string"
     *         )
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
    public function refreshToken(Request $request)
    {
        try {
            $tokens = joyRefreshToken($request->refresh_token);
            $user   = joyGetUser($tokens['access_token']);

            $response = $this->overrideSendRefreshTokenResponse(
                $request,
                $user,
                $tokens,
            );
            if ($response) {
                return $response;
            }

            $authResource = app()->make('joy-voyager-api-auth.auth');

            return $authResource::make($user, $tokens);
        } catch (ClientException $e) {
            return new JsonResponse(
                json_decode($e->getResponse()->getBody()->getContents(), true),
                $e->getCode()
            );
        }
    }

    /**
     * Override send RefreshToken response.
     *
     * @param Request $request Request
     * @param mixed   $user    User
     *
     * @return mixed
     */
    protected function overrideSendRefreshTokenResponse(
        Request $request,
        $user,
        array $tokens
    ) {
        //
    }
}
