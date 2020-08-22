<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\MicrobloggingService;

class FollowController extends Controller
{
    public function store(Request $request, $provider, $username)
    {
        (new MicrobloggingService($provider))
                        ->account($request->user('api'))
                        ->follow($username);

        return response([
            'message' => __('services.follow.store'),
        ]);
    }
}
