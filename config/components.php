<?php

use Hexammon\Server\Infrastructure\Repository\RoomRepository;
use Thruway\Peer\Client;
use Thruway\Transport\PawlTransportProvider;

const WAMP_REALM = 'hexammon';

$client = new Client(WAMP_REALM);
$client->addTransportProvider(new PawlTransportProvider('ws://wamp-router:9090/'));

return [
    \FreeElephants\DI\InjectorBuilder::REGISTER_KEY  => [
        \Hexammon\Server\Domain\Player\PlayerRepositoryInterface::class => \Hexammon\Server\Infrastructure\Repository\PlayerRepository::class,
        \Hexammon\Server\Domain\Room\RoomRepositoryInterface::class     => RoomRepository::class,
    ],
    \FreeElephants\DI\InjectorBuilder::INSTANCES_KEY => [
        \Thruway\Peer\ClientInterface::class => $client,
        \Hexammon\Server\Infrastructure\Wamp\SessionProvider::class => new \Hexammon\Server\Infrastructure\Wamp\SessionProvider($client)
    ],
];
