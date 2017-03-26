<?php

use FreeElephants\HexammonServer\Channel\RoomsChannel;
use Ratchet\App;

require __DIR__ . '/../vendor/autoload.php';

$httpHost = $argv[1] ?? '127.0.0.1';
$port = $argv[2] ?? 8080;
$address = $argv[3] ?? '0.0.0.0';
$app = new App($httpHost, $port, $address);
$app->route('/wss/v1/rooms', new RoomsChannel($app), ['*']);
$app->run();