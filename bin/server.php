<?php

use FreeElephants\DI\InjectorBuilder;
use Hexammon\Server\Application\Console\RunRouter;
use Hexammon\Server\Application\Console\RunWampServer;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Application();

$builder = new InjectorBuilder();
$di = $builder->buildFromArray(require_once __DIR__ . '/../config/components.php');
$di->allowInstantiateNotRegisteredTypes(true);

$commands = [
    'router:run' => RunRouter::class,
    'server:run' => RunWampServer::class,
];
foreach ($commands as $commandName => $commandClass) {
    /**@var Command $command */
    $command = $di->get($commandClass);
    $command->setName($commandName);

    $app->add($command);
}

$app->run();
