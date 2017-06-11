<?php
use FreeElephants\HexammonServer\Auth\Endpoint\Auth\GetHandler;
use FreeElephants\HexammonServer\Auth\Endpoint\Auth\PostHandler;
use FreeElephants\HexammonServer\Auth\Endpoint\Users\GetUserByIdHandler;
use FreeElephants\HexammonServer\Auth\Endpoint\Users\GetUserCollectionHandler;
use FreeElephants\HexammonServer\Auth\Endpoint\Users\PostHandler as UserPostHandler;

return [
    'modules' => [
        '/api/v1' => [
            'name' => 'Api ver.1',
            'endpoints' => [
                '/auth' => [
                    'name' => 'Auth Endpoint',
                    'handlers' => [
                        'POST' => PostHandler::class,
                    ],
                ],
                '/auth/{authKey}' => [
                    'name' => 'AuthKey Endpoint',
                    'handlers' => [
                        'GET' => GetHandler::class,
                    ],
                ],
                '/users' => [
                    'name' => 'Users Collection Endpoint',
                    'handlers' => [
                        'POST' => UserPostHandler::class,
                        'GET' => GetUserCollectionHandler::class
                    ]
                ],
                '/users/{userId}' => [
                    'name' => 'User Item Endpoint',
                    'handlers' => [
                        'GET' => GetUserByIdHandler::class,
                    ]
                ]
            ]
        ]
    ]
];