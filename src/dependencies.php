<?php

use App\Controllers\DashboardController;
use App\Controllers\ParkedVehicleController;
use App\Controllers\UserController;
use App\Controllers\VehicleController;
use App\Middlewares\AdminMiddleware;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\ErrorMiddleware;
use App\Middlewares\GuestMiddleware;
use App\Middlewares\ValidationFailedMiddleware;
use App\Models\VehicleColor;
use App\Repositories\ParkedVehicleRepository;
use App\Repositories\UserRepository;
use App\Repositories\VehicleColorRepository;
use App\Repositories\VehicleRatePerHourRepository;
use App\Repositories\VehicleRepository;
use App\Repositories\VehicleTypeRepository;
use App\Services\ParkedVehicleService;
use App\Services\UserService;
use App\Services\VehicleColorService;
use App\Services\VehicleRatePerHourService;
use App\Services\VehicleService;
use App\Services\VehicleTypeService;
use Slim\App;

return function (App $app) {
    $container = $app->getContainer();

    // view renderer
    $container['renderer'] = function ($c) {
        $settings = $c->get('settings')['renderer'];
        return new \Slim\Views\PhpRenderer($settings['template_path']);
    };

    // monolog
    $container['logger'] = function ($c) {
        $settings = $c->get('settings')['logger'];
        $logger = new \Monolog\Logger($settings['name']);
        $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
        return $logger;
    };

    // Database
    $container[\PDO::class] = function ($c) {
        $dbSettings = $c['settings']['db'];
        $pdo = new \PDO(
            "mysql:host=" . $dbSettings['host'] . ";dbname=" . $dbSettings['database'],
            $dbSettings['username'],
            $dbSettings['password']
        );
        // Optional: Set the PDO in exception mode
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    };

    /**
     *  Container of middlewares
     */
    $container[GuestMiddleware::class] = function ($c) {
        return (new GuestMiddleware())->setContainer($c);
    };
    $container[AuthMiddleware::class] = function ($c) {
        return (new AuthMiddleware())->setContainer($c);
    };
    $container[ValidationFailedMiddleware::class] = function ($c) {
        return (new ValidationFailedMiddleware())->setContainer($c);
    };
    $container[ErrorMiddleware::class] = function ($c) {
        return (new ErrorMiddleware())->setContainer($c);
    };
    $container[AdminMiddleware::class] = function ($c) {
        return (new AdminMiddleware())->setContainer($c);
    };

    /**
     *  Container of dashboard controller
     */
    $container[DashboardController::class] = function ($c) {
        return (new DashboardController())->setContainer($c);
    };

    /**
     *  Container of user controller, service and repository
     */
    $container[UserRepository::class] = function ($c) {
        return new UserRepository($c->get(\PDO::class));
    };
    $container[UserService::class] = function ($c) {
        return (new UserService($c->get(UserRepository::class)))->setContainer($c);
    };
    $container[UserController::class] = function ($c) {
        return (new UserController($c->get(UserService::class)))->setContainer($c);
    };

    /**
     *  Container of vehicle colors controller, service and repository
     */
    $container[VehicleColorRepository::class] = function ($c) {
        return new VehicleColorRepository($c->get(\PDO::class));
    };
    $container[VehicleColorService::class] = function ($c) {
        return (new VehicleColorService($c->get(VehicleColorRepository::class)))->setContainer($c);
    };

    /**
     *  Container of vehicle types controller, service and repository
     */
    $container[VehicleTypeRepository::class] = function ($c) {
        return new VehicleTypeRepository($c->get(\PDO::class));
    };
    $container[VehicleTypeService::class] = function ($c) {
        return (new VehicleTypeService($c->get(VehicleTypeRepository::class)))->setContainer($c);
    };

    /**
     *  Container of vehicle rate per hours controller, service and repository
     */
    $container[VehicleRatePerHourRepository::class] = function ($c) {
        return new VehicleRatePerHourRepository($c->get(\PDO::class));
    };
    $container[VehicleRatePerHourService::class] = function ($c) {
        return (new VehicleRatePerHourService($c->get(VehicleRatePerHourRepository::class)))->setContainer($c);
    };

    /**
     *  Container of vehicles controller, service and repository
     */
    $container[VehicleRepository::class] = function ($c) {
        return new VehicleRepository($c->get(\PDO::class));
    };
    $container[VehicleService::class] = function ($c) {
        return new VehicleService(
            $c->get(VehicleRepository::class),
            $c->get(VehicleColorRepository::class),
            $c->get(VehicleTypeRepository::class),
            $c->get(VehicleRatePerHourRepository::class),
        );
    };
    $container[VehicleController::class] = function ($c) {
        return (new VehicleController($c->get(VehicleService::class)))->setContainer($c);
    };

    /**
     *  Container of parked vehicles controller, service and repository
     */
    $container[ParkedVehicleRepository::class] = function ($c) {
        return new ParkedVehicleRepository($c->get(\PDO::class));
    };
    $container[ParkedVehicleService::class] = function ($c) {
        return (new ParkedVehicleService(
            $c->get(ParkedVehicleRepository::class),
            $c->get(VehicleRepository::class),
        ))->setContainer($c);
    };
    $container[ParkedVehicleController::class] = function ($c) {
        return (new ParkedVehicleController(
            $c->get(ParkedVehicleService::class),
            $c->get(VehicleColorService::class),
            $c->get(VehicleTypeService::class),
        ))->setContainer($c);
    };
};
