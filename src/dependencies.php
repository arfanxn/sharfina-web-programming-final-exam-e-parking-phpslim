<?php

use App\Controllers\UserController;
use App\Repositories\UserRepository;
use App\Services\UserService;
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
     *  Container of controllers, services and repositories
     */
    $container[UserRepository::class] = function ($c) {
        return new UserRepository($c->get(\PDO::class));
    };
    $container[UserService::class] = function ($c) {
        return new UserService($c->get(UserRepository::class));
    };
    $container[UserController::class] = function ($c) {
        return new UserController($c->get(UserService::class));
    };

};
