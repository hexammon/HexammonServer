<?php
use FreeElephants\DI\Injector;
use FreeElephants\DI\InjectorBuilder;

require __DIR__ . '/vendor/autoload.php';

/**
 * @global
 * @var Injector
 */
static $di;

if (empty($di)) {
    $components = require __DIR__ . '/config/components.php';
    $di = (new InjectorBuilder)->buildFromArray($components);
}
return $di;
