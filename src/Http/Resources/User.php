<?php

declare(strict_types=1);

namespace Joy\VoyagerApiAuth\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

/**
 * @OA\Schema(
 *    schema="User"
 * )
 */

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function toArray($request): array
    {
        return Arr::except(
            parent::toArray($request),
            $this->resource->getHidden()
        );
    }
}
