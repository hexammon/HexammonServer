<?php

use FreeElephants\DI\Injector;
use FreeElephants\HexammonServer\Channel\RoomsChannel;
use FreeElephants\RestDaemon\RestServer;
use FreeElephants\RestDaemon\RestServerBuilder;
use Ratchet\App;

require __DIR__ . '/../vendor/autoload.php';

$components = require __DIR__ . '/../config/components.php';
$di = (new \FreeElephants\DI\InjectorBuilder)->buildFromArray($components);

$httpHost = $argv[1] ?? getenv('H6N_HOST') ?: '127.0.0.1';
$port = $argv[2] ?? getenv('H6N_PORT') ?: 8080;
$address = $argv[3] ?? getenv('H6N_ADDR') ?: '0.0.0.0';
$origin = isset($argv[4]) ? explode(',', $argv[4]) : explode(',', getenv('H6N_ORIGIN')) ?: ['*'];

$routes = require __DIR__ . '/../config/routes.php';
$server = new RestServer($httpHost, $port, $address, $origin);
$restServerBuilder = new RestServerBuilder();
$restServerBuilder->setModuleFactory(new \FreeElephants\RestDaemon\Module\ModuleFactory());
$restServerBuilder->setEndpointFactory(new \FreeElephants\RestDaemon\Endpoint\EndpointFactory($di));
$restServerBuilder->setHandlerFactory(new \FreeElephants\RestDaemon\Endpoint\Handler\HandlerFactory($di));
$restServerBuilder->setServer($server);
$restServerBuilder->buildServer($routes);

$wssInjector = function (App $app, RestServer $server) use ($di, $origin) {
    $app->route('/wss/v1/rooms', $di->createInstance(RoomsChannel::class), $origin);
};
$server->run($wssInjector);