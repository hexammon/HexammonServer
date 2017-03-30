<?php

use FreeElephants\HexammonServer\Auth\AuthClientInterface;
use FreeElephants\HexammonServer\Auth\FakeAuthClient;
use FreeElephants\HexammonServer\Message\Client\ClientMessageFactory;
use FreeElephants\HexammonServer\Model\Room\RoomRepository;
use FreeElephants\HexoNards\Board\BoardBuilder;

$authClient = new FakeAuthClient([
    'test user' => 'test-user-auth-key',
]);


return [
    'register' => [
        RoomRepository::class => RoomRepository::class,
        ClientMessageFactory::class => ClientMessageFactory::class,
        BoardBuilder::class => BoardBuilder::class,
    ],
    'instances' => [
        AuthClientInterface::class => $authClient,
    ],
];