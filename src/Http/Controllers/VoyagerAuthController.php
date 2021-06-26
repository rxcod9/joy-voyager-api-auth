<?php

declare(strict_types=1);

namespace Joy\VoyagerApiAuth\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Joy\VoyagerApiAuth\Http\Traits\LoginAction;
use Joy\VoyagerApiAuth\Http\Traits\RefreshTokenAction;
use Joy\VoyagerApiAuth\Http\Traits\RegisterAction;
use TCG\Voyager\Http\Controllers\Controller;

class VoyagerAuthController extends Controller
{
    use RegisterAction;
    use LoginAction;
    use RefreshTokenAction;

    /**
     * Create a new instance
     */
    public function __construct()
    {
        Auth::shouldUse(joyGuard());
    }
}
