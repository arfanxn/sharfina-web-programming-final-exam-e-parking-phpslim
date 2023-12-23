<?php

use App\Controllers\UserController;
use Slim\App;

/*
use Slim\Http\Request;
use Slim\Http\Response;
*/

return function (App $app) {
    $container = $app->getContainer();

    // Add the before middlewares
    $app->add(new \App\Middlewares\ValidationFailedMiddleware());
    $app->add(new \App\Middlewares\ErrorMiddleware());

    /*
    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");
        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });
    */

    $app->post('/api/users/login', UserController::class . ':login');

    $app->group('/api', function (App $app) {
        /**
         *  User routes
         */
        $app->group('/users', function (App $app) {
            // $app->get('', function (Request $request, Response $response) {
            //     return $response;
            // });

            $app->get('/{id}', UserController::class . ':view');
        });
    })->add(new \Tuupola\Middleware\JwtAuthentication($container->get('settings')['jwt']));
};
