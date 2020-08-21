<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
        'mediable_type',
        'mediable_id',
        'type',
        'path',
        'name',
        'title',
        'description',
        'notes',
    ];

    public function mediable()
    {
        return $this->morphTo();
    }

    public function getDisplayedPathAttribute() : ?string
    {
        if ( is_null($this->path) ) {
            return $this->path;
        }

        if ( Str::startsWith($this->path, 'http') ) {
            return $this->path;
        }

        return url( str_replace('public', 'storage', $this->path), [], !app()->isLocal() );
    }
}
