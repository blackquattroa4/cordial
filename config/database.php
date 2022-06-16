<?php

return [

    'default' => 'mongodb',

    'connections' => [
        'mysql' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', 'localhost'),
            'port'      => env('DB_PORT', 3306),
            'database'  => env('DB_DATABASE', ''),
            'username'  => env('DB_USERNAME', ''),
            'password'  => env('DB_PASSWORD', ''),
            'charset'   => env('DB_CHARSET', 'utf8'),
            'collation' => env('DB_COLLATION', 'utf8_unicode_ci'),
            'prefix'    => env('DB_PREFIX', ''),
            'timezone'  => env('DB_TIMEZONE', '+00:00'),
            'strict'    => env('DB_STRICT_MODE', false),
        ],

        'mongodb' => array(
            'driver'   => 'mongodb',
            'host'     => env('DB_MONGO_HOST', 'localhost'),
            'port'     => env('DB_MONGO_PORT',27017),
            'username' => env('DB_MONGO_USERNAME', ''),
            'password' => env('DB_MONGO_PASSWORD', ''),
            'database' => env('DB_MONGO_DATABASE', ''),
        ),

    ],

];