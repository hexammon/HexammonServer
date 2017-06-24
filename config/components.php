<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use FreeElephants\Db\PdoReconnectWrapper;
use FreeElephants\HexammonServer\Auth\AuthClientInterface;
use FreeElephants\HexammonServer\Auth\Endpoint\DTO\DTOFactory;
use FreeElephants\HexammonServer\Auth\FakeAuthClient;
use FreeElephants\HexammonServer\Auth\Model\AuthKey\AuthKeyProviderInterface;
use FreeElephants\HexammonServer\Auth\Model\AuthKey\UserAuthKeyProvider;
use FreeElephants\HexammonServer\Auth\Model\User\User;
use FreeElephants\HexammonServer\Auth\Model\User\UserRegistrationService;
use FreeElephants\HexammonServer\Auth\Model\User\UserRepository;
use FreeElephants\HexammonServer\Message\Client\ClientMessageFactory;
use FreeElephants\HexammonServer\Model\Room\RoomRepository;
use FreeElephants\HexoNards\Board\BoardBuilder;
use FreeElephants\RestDaemon\DTO\DTOFactoryInterface;
use FreeElephants\RestDaemon\Serialization\JsonSerializer;
use FreeElephants\RestDaemon\Serialization\SerializerInterface;

$authClient = new FakeAuthClient([
    'test user' => 'test-user-auth-key',
]);

$connection = \Doctrine\DBAL\DriverManager::getConnection([
    'dbname' => 'hexammon',
    'user' => 'hexammon',
    'password' => 'hexammon_password',
    'host' => 'db',
    'driver' => 'pdo_mysql',
]);
$config = Setup::createAnnotationMetadataConfiguration([__DIR__ . '/../src'], true, null, null, false);
$entityManager = EntityManager::create($connection, $config);
return [
    'register' => [
        UserRegistrationService::class,
        RoomRepository::class => RoomRepository::class,
        ClientMessageFactory::class => ClientMessageFactory::class,
        BoardBuilder::class => BoardBuilder::class,
        AuthKeyProviderInterface::class => UserAuthKeyProvider::class,
        DTOFactoryInterface::class => DTOFactory::class,
        SerializerInterface::class => JsonSerializer::class,
    ],
    'instances' => [
        AuthClientInterface::class => $authClient,
        PdoReconnectWrapper::class => new PdoReconnectWrapper('mysql:host=db;dbname=hexammon;charset=utf8',
            'hexammon', 'hexammon_password'),
        EntityManager::class => $entityManager,
        UserRepository::class => $entityManager->getRepository(User::class),
    ],
];