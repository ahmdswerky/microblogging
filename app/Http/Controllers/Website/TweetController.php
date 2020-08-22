<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\MicrobloggingService;
use App\Http\Resources\Website\TweetResource;
use App\Http\Requests\Website\TweetStoreRequest;

class TweetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tweets = (new MicrobloggingService('twitter'))
                        ->account($request->user('api'))
                        ->timeline($request->query());

        return TweetResource::collection($tweets);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TweetStoreRequest $request)
    {
        $tweet = $request->storeTweet();

        return response([
            'tweet' => new TweetResource($tweet),
            'message' => __('services.tweets.store'),
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $tweet = (new MicrobloggingService('twitter'))
                        ->account($request->user('api'))
                        ->show($id);

        return response([
            'tweet' => new TweetResource($tweet),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        (new MicrobloggingService('twitter'))
            ->account($request->user('api'))
            ->delete($id);

        return response([
            'message' => __('services.tweets.destroy'),
        ]);
    }
}
