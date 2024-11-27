<?php
return  [
    'env' => env("APP_ENV", 'local'),
    'name' => env("APP_NAME", 'local'),
    'debug' => env("APP_DEBUG", 'local'),
    'url' => env("APP_URL", 'local'),

    'view_dir' => './views/',
    "page_404" => '404.php',
    'timezone' => 'UTC',
    "logs_file" => './logs/app.log'
];