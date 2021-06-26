<?php

declare(strict_types=1);

namespace Joy\VoyagerApiAuth\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *    schema="Auth"
 * )
 */

class Auth extends JsonResource
{
    /**
     * The user instance.
     *
     * @var mixed
     */
    public $user;

    /**
     * The tokens instance.
     *
     * @var mixed
     */
    public $tokens;

    /**
     * Create a new resource instance.
     *
     * @param mixed $user
     * @param mixed $tokens
     *
     * @return void
     */
    public function __construct($user, $tokens)
    {
        parent::__construct($user);

        $this->user   = $user;
        $this->tokens = $tokens;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function toArray($request): array
    {
        $userResource = app()->make('joy-voyager-api-auth.user');

        return [
            'user'   => $userResource::make($this->user),
            'tokens' => $this->tokens,
        ];
    }
}
