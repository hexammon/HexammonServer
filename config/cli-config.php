<?php
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use FreeElephants\DI\Injector;

/**
 * @var $di Injector
 */
$di = require __DIR__ . '/../bootstrap.php';

$entityManager = $di->getService(EntityManager::class);

return ConsoleRunner::createHelperSet($entityManager);