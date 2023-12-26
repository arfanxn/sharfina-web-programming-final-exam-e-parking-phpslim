<?php

use App\Controllers\DashboardController;
use App\Controllers\UserController;
use Slim\App;

return function (App $app) {
    $container = $app->getContainer();

    // Add the before middlewares
    $app->add(\App\Middlewares\ValidationFailedMiddleware::class);
    $app->add(\App\Middlewares\ErrorMiddleware::class);

    /*    
    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");
        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });
    */


    /**
     *  Routes that are excluded from the Authentication process
     */
    $app->group('', function (App $app) {
        $app->group('/users', function (App $app) {
            $app->get('/login', UserController::class . ':login');
            $app->post('/handle-login', UserController::class . ':handleLogin');
        });
    });

    /**
     *  Routes that are included in the Authentication process
     */
    $app->group('', function (App $app) use ($container) {

        $app->get('/', DashboardController::class);

        /**
         *  User routes
         */
        $app->group('/users', function (App $app) {
            $app->delete('/handle-logout', UserController::class . ':handleLogout');
            $app->get('', UserController::class . ':index');
            $app->get('/{id}', UserController::class . ':view');
            $app->get('//create', UserController::class . ':create');
            $app->post('//handle-create', UserController::class . ':store');
            $app->get('/{id}/edit', UserController::class . ':edit');
            $app->put('/{id}/handle-edit', UserController::class . ':update');
            $app->delete('/{id}/handle-delete', UserController::class . ':destroy');
        });
    })->add(\App\Middlewares\AuthMiddleware::class);
};
