<?php

namespace App\Traits;

use ReflectionClass;
use App\Models\Media;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

Trait Mediable
{
    public function media($type = null, string $name = null)
    {
        return $this->morphMany(Media::class, 'mediable')
            ->when($type, function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->when($name, function ($query) use ($name) {
                $query->where('name', $name);
            })
            ->select([
                'id',
                'type',
                'mediable_type',
                'mediable_id',
                'path',
                'name',
                'title',
                'description',
            ]);
    }

    public function photo(string $name = null)
    {
        return $this->morphOne(Media::class, 'mediable')
            ->where('type', 'photo')
            ->when($name, function ($query) use ($name) {
                $query->where('name', $name);
            })
            ->select([
                'id',
                'type',
                'mediable_type',
                'mediable_id',
                'path',
                'name',
                'title',
                'description',
            ]);
    }

    public function video(string $name = null)
    {
        return $this->morphOne(Media::class, 'mediable')
            ->where('type', 'video')
            ->when($name, function ($query) use ($name) {
                $query->where('name', $name);
            })
            ->select([
                'id',
                'type',
                'mediable_type',
                'mediable_id',
                'path',
                'name',
                'title',
                'description',
            ]);
    }

    public function file(string $name = null)
    {
        return $this->morphOne(Media::class, 'mediable')
            ->where('type', 'file')
            ->when($name, function ($query) use ($name) {
                $query->where('name', $name);
            })
            ->select([
                'id',
                'type',
                'mediable_type',
                'mediable_id',
                'path',
                'name',
                'title',
                'description',
            ]);
    }

    public function addMedia($path, $type = 'photo', array $additional = [])
    {
        if ( $path instanceof UploadedFile ) {
            $path = $this->storeFile($path, $type);
        }

        return $this->media()->create([
            'path' => $path,
            'type' => $type,
            'name' => array_key_exists('name', $additional) ? $additional['name'] : null,
            'title' => array_key_exists('title', $additional) ? $additional['title'] : null,
            'description' => array_key_exists('description', $additional) ? $additional['description'] : null,
            'notes' => array_key_exists('notes', $additional) ? $additional['notes'] : null,
        ]);
    }

    public function addMediaUrl($url, $type = 'photo', array $additional = [])
    {
        return $this->media()->create([
            'path' => $url,
            'type' => $type,
            'name' => array_key_exists('name', $additional) ? $additional['name'] : null,
            'title' => array_key_exists('title', $additional) ? $additional['title'] : null,
            'description' => array_key_exists('description', $additional) ? $additional['description'] : null,
            'notes' => array_key_exists('notes', $additional) ? $additional['notes'] : null,
        ]);
    }

    public function updateMedia(Media $media, $path, array $additional = [])
    {
        if ( $path instanceof UploadedFile ) {
            $path = $this->storeFile($path, $media->type);
        }

        $this->deleteOldMedia($media);

        return tap($media, function () use ($media, $path, $additional) {
            $media->update([
                'path' => $path,
                'name' => array_key_exists('name', $additional) ? $additional['name'] : $media->name,
                'title' => array_key_exists('title', $additional) ? $additional['title'] : $media->title,
                'description' => array_key_exists('description', $additional) ? $additional['description'] : $media->description,
                'notes' => array_key_exists('notes', $additional) ? $additional['notes'] : $media->notes,
            ]);
        });
    }

    public function deleteOldMedia(Media $media)
    {
        if ( Str::contains($media->path, url('')) ) {
            $path = explode('storage', $media->path)[1];
            $path = 'public' . $path;
            @Storage::delete($path);
        }
    }

    protected function storeFile(UploadedFile $requestFile, string $type)
    {
        $type = Str::plural( strtolower($type) );
        $typeDirectory = $type . "DirectoryMedia";

        return $requestFile->storeAs(
            config("media.{$type}.path") . $this->$typeDirectory,
            Str::random( config('media.filename.length') ) .
            '.' . $requestFile->getClientOriginalExtension()
        );
    }

    //? ==== [ Accessors & Mutators ] ==== //
    public function getPhotosDirectoryMediaAttribute()
    {
        $class = new ReflectionClass($this);
        return Str::plural(strtolower( $class->getShortName() ));
    }

    public function getVideosDirectoryMediaAttribute()
    {
        $class = new ReflectionClass($this);
        return Str::plural(strtolower( $class->getShortName() ));
    }

    public function getFilesDirectoryMediaAttribute()
    {
        $class = new ReflectionClass($this);
        return Str::plural(strtolower( $class->getShortName() ));
    }

    //? ==== Delete ==== //
    public function deleteMedia(Media $media)
    {
        @Storage::delete( $media->path );
        $media->delete();
    }

    public function deleteAllMedia($type = null, $name = null)
    {
        $this->media->when($name, function ($query) use ($name) {
                        $query->where('name', $name);
                    })
                    ->when($type, function ($query) use ($type) {
                        $query->where('type', $type);
                    })
                    ->each(function ($singleMedia) {
                        $this->deleteMedia($singleMedia);
                    });
    }
}
