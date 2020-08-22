<?php

namespace App\Http\Resources\Website;

use Illuminate\Http\Resources\Json\JsonResource;

class TweetUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->screen_name,
            'url' => $this->url,
            'image' => $this->profile_image_url_https,
        ];
    }
}
