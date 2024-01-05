<?php

use App\Controllers\DashboardController;
use App\Controllers\ParkedVehicleController;
use App\Controllers\UserController;
use App\Controllers\VehicleController;
use Slim\App;

return function (App $app) {
    $container = $app->getContainer();

    // Add the before middlewares
    $app->add(\App\Middlewares\ErrorMiddleware::class);
    $app->add(\App\Middlewares\ValidationFailedMiddleware::class);

    /*    
    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");
        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });
    */


    /**
     *  Routes that are EXCLUDED from the Authentication process
     */
    $app->group('', function (App $app) {
        $app->group('/users', function (App $app) {
            $app->get('/login', UserController::class . ':login');
            $app->post('/handle-login', UserController::class . ':handleLogin');
        });
    })->add(\App\Middlewares\GuestMiddleware::class);

    /**
     *  Routes that are INCLUDED in the Authentication process
     */
    $app->group('', function (App $app) use ($container) {

        $app->get('/', DashboardController::class);

        /**
         *  User routes
         */
        $app->map(['POST', 'GET', 'DELETE'], '/users/handle-logout', UserController::class . ':handleLogout');
        $app->group('/users', function (App $app) {
            $app->get('', UserController::class . ':index');
            $app->get('/{id}', UserController::class . ':view');
            $app->get('//create', UserController::class . ':create');
            $app->post('//handle-create', UserController::class . ':store');
            $app->get('/{id}/edit', UserController::class . ':edit');
            $app->map(['POST', 'PUT'], '/{id}/handle-edit', UserController::class . ':update');
            $app->map(['POST', 'DELETE'], '/{id}/handle-delete', UserController::class . ':destroy');
        })->add(\App\Middlewares\AdminMiddleware::class);

        /**
         *  Parked Vehicle routes
         */
        $app->group('/parked-vehicles', function (App $app) {
            $app->get('', ParkedVehicleController::class . ':index');
            $app->get('/{id}', ParkedVehicleController::class . ':view');
            $app->get('//enter', ParkedVehicleController::class . ':enter');
            $app->post('//handle-enter', ParkedVehicleController::class . ':handleEnter');
            $app->map(['POST', 'GET', 'PUT',], '/{id}/toggle-left', ParkedVehicleController::class . ':toggleLeft');
        });

        /**
         *  Vehicle routes
         */
        $app->group('/vehicles', function (App $app) {
            $app->get('/{id}', VehicleController::class . ':view');
        });
    })->add(\App\Middlewares\AuthMiddleware::class);
};
