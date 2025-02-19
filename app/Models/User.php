<?php

namespace App\Models;

use App\Traits\Mediable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, Mediable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($value)
    {
        if ( $value !== '' ) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    public function twitterAccount()
    {
        return $this->hasOne(TwitterAccount::class);
    }

    public function getImageAttribute()
    {
        return $this->media('photo', 'image')->first();
    }

    public function getHasTwitterAttribute()
    {
        return (bool) $this->twitterAccount()->count();
    }
}
