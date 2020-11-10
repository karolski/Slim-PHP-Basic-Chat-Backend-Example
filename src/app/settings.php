<?php
declare(strict_types=1);

use Monolog\Logger;

$DEBUG = isset($_ENV["DEBUG"]);
$TESTING = isset($_ENV["TESTING"]);

return [
    'settings' => [
        'debug' => $DEBUG,
        'displayErrorDetails' => $DEBUG,
        'logErrorDetails' => $DEBUG and !$TESTING,
        "error_log" => __DIR__ . '/../../logs/error.log',
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../../logs/app.log',
            'level' => Logger::INFO,
        ],
        'doctrine' => [
            'dev_mode' => $DEBUG,
            'cache_dir' => __DIR__ . '/../../var/cache/doctrine',
            'metadata_dirs' => [__DIR__ . '/../Entities/'],
            'connections' => [
                "default" => [
                    'driver' => 'pdo_sqlite',
                    'path' => __DIR__ . '/../../var/db.sqlite',
                ],
                "test" => [
                    'driver' => 'pdo_sqlite',
                    'memory' => 'true'
                ]
            ]
        ],
    ]
];
