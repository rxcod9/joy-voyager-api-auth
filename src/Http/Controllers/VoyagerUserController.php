<?php

declare(strict_types=1);

namespace Joy\VoyagerApiAuth\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Joy\VoyagerApiAuth\Http\Traits\ProfileAction;
use Joy\VoyagerCore\Http\Controllers\VoyagerBaseController as BaseVoyagerBaseController;

class VoyagerUserController extends BaseVoyagerBaseController
{
    use ProfileAction;

    /**
     * Create a new instance
     */
    public function __construct()
    {
        Auth::shouldUse(joyGuard());
    }
}
