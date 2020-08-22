<?php

namespace App\Models;

use App\Interfaces\SocialAccount;
use Illuminate\Database\Eloquent\Model;

class TwitterAccount extends Model implements SocialAccount
{
    protected $fillable = [
        'account_id',
        'username',
        'access_token',
        'access_token_secret',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
