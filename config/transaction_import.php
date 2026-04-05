<?php

declare(strict_types=1);

return [
    /*
    | If set, imports target this user regardless of APP_ENV.
    | Otherwise: production uses production_default_email; other envs use TestUserSeeder::TEST_USER_EMAIL.
    */
    'user_email' => env('TRANSACTION_IMPORT_USER_EMAIL'),

    'production_default_email' => env('TRANSACTION_IMPORT_PRODUCTION_EMAIL', 'jhonnycarpenito@gmail.com'),
];
