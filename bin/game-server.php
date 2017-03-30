<?php

use FreeElephants\DI\Injector;
use FreeElephants\HexammonServer\Channel\RoomsChannel;
use FreeElephants\RestDaemon\RestServer;
use Ratchet\App;

require __DIR__ . '/../vendor/autoload.php';

$di = new Injector();
$components = require __DIR__ . '/../config/components.php';
$beansInstances = $components['instances'];
foreach ($beansInstances as $name => $instance) {
    $di->setService($name, $instance);
}
$registeredBeans = $components['register'];
foreach ($registeredBeans as $interface => $implementation) {
    $di->registerService($implementation, $interface);
}

$httpHost = $argv[1] ?? '127.0.0.1';
$port = $argv[2] ?? 8080;
$address = $argv[3] ?? '0.0.0.0';
$origin = isset($argv[4]) ? explode(',', $argv[4]) : ['*'];

$server = new RestServer($httpHost, $port, $address, $origin);
$wssInjector = function (App $app, RestServer $server) use ($di, $origin) {
    $app->route('/wss/v1/rooms', $di->createInstance(RoomsChannel::class), $origin);
};
$server->run($wssInjector);