<?php

declare(strict_types=1);

namespace Joy\VoyagerApiAuth\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Joy\VoyagerApiAuth\Http\Traits\ProfileAction;
use TCG\Voyager\Http\Controllers\VoyagerBaseController as TCGVoyagerBaseController;

class VoyagerUserController extends TCGVoyagerBaseController
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
