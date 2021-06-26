<?php

declare(strict_types=1);

namespace Joy\VoyagerApiAuth\Http\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Facades\Voyager;

trait ProfileAction
{
    /**
     * @OA\Get(
     * path="/api/profile",
     *   tags={"User"},
     *   security={
     *      {"token": {}},
     *   },
     *   summary="Profile",
     *   operationId="user",
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\JsonContent(
     *         @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *       response=403,
     *       description="Forbidden"
     *   )
     * )
     */
    public function profile(Request $request)
    {
        $route    = '';
        $dataType = Voyager::model('DataType')->where('model_name', Auth::guard(config('joy-voyager-api-auth.guard', 'api'))->getProvider()->getModel())->first();
        if (!$dataType && config('joy-voyager-api-auth.guard', 'api') == 'api') {
            $route = route('voyager.users.edit', Auth::user()->getKey());
        } elseif ($dataType) {
            $route = route('voyager.' . $dataType->slug . '.edit', Auth::user()->getKey());
        }

        $isSoftDeleted = false;

        $dataTypeContent = Auth::user();

        if ($dataTypeContent->deleted_at) {
            $isSoftDeleted = true;
        }

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        $resourceClass = 'joy-voyager-api.json';

        if (app()->bound("joy-voyager-api.$slug.json")) {
            $resourceClass = "joy-voyager-api.$slug.json";
        }

        $resource = app()->make($resourceClass);

        return $resource::make($dataTypeContent)->additional(compact(
            // 'dataType', // @TODO
            'isModelTranslatable',
            'isSoftDeleted'
        ));
    }
}
