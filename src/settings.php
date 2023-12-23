<?php

return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // Database settings
        'db' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'e-parking-1',
            'username' => 'root',
            'password' => '',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ],

        // JWT settings
        'jwt' => [
            'secret' => 'secret',
            'attribute' => 'decoded_token_data', // Attribute name to store decoded token data
            'secure' =>  false, // Set to true in production
            'algorithm' => ['HS256'],
            'error' => function ($response, $arguments) {
                return $response->withJson(
                    \App\Resources\ResponseBody::instantiate()
                        ->setStatusAsError()
                        ->setMessage('Unauthorized')
                        ->toArray(),
                    401
                );
            },
        ],
    ],
];
