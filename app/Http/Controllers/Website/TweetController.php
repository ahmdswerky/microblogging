<?php

namespace App\Http\Controllers\Website;

use App\Facades\Twitter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        $tweets = Twitter::timeline($request->query());

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
    public function show($id)
    {
        $tweet = Twitter::show($id);

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
    public function destroy($id)
    {
        Twitter::delete($id);

        return response([
            'message' => __('services.tweets.destroy'),
        ]);
    }
}
