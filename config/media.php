<?php

return [
    'filename' => [
        'length' => 20,
    ],

    'photos' => [
        'path' => env('APP_PHOTOS_PATH', 'public/photos/'),
        'max' => '2048',
        'allowed_extensions' => [
            'jpeg',
            'png',
        ],
    ],
];
