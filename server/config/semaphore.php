<?php

return [
    'key' => env('SEMAPHORE_TOKEN'),

    'urls' => [
        'local' => env('SEMAPHORE_DEV_SERVER', 'http://localhost:3535'),
        'production' => env('SEMAPHORE_PROD_SERVER', 'https://api.semaphore.co/api/v4/'),
    ],
];
