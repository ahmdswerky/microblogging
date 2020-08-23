<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Services\MicrobloggingService;

class FollowController extends Controller
{
    public function store($provider, $username)
    {
        (new MicrobloggingService($provider))->follow($username);

        return response([
            'message' => __('services.follow.store'),
        ]);
    }
}
