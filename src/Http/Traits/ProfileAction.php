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
     *   path="/api/profile",
     *   tags={"auth"},
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
        $slug     = '';
        $dataType = Voyager::model('DataType')->where(
            'model_name',
            joyProviderModel()
        )->first();

        if (!$dataType && joyGuard() == 'api') {
            $slug = 'users';
        } elseif ($dataType) {
            $slug = $dataType->slug;
        }

        $isSoftDeleted = false;

        $dataTypeContent = Auth::user();

        if ($dataTypeContent->deleted_at) {
            $isSoftDeleted = true;
        }

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        $response = $this->overrideSendProfileResponse(
            $request,
            $dataTypeContent
        );
        if ($response) {
            return $response;
        }

        $resourceClass = 'joy-voyager-api.json';

        if (app()->bound("joy-voyager-api.$slug.json")) {
            $resourceClass = "joy-voyager-api.$slug.json";
        }

        $resource = app()->make($resourceClass);

        return $resource::make($dataTypeContent)
            ->additional(
                compact(
                    // 'dataType', // @TODO
                    'isModelTranslatable',
                    'isSoftDeleted'
                )
            );
    }

    /**
     * Override send Profile response.
     *
     * @param Request $request         Request
     * @param mixed   $dataTypeContent DataTypeContent
     *
     * @return mixed
     */
    protected function overrideSendProfileResponse(
        Request $request,
        $dataTypeContent
    ) {
        //
    }
}
