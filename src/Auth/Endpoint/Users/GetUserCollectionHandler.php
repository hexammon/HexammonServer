<?php

namespace FreeElephants\HexammonServer\Auth\Endpoint\Users;

use FreeElephants\HexammonServer\Auth\Endpoint\AbstractHandler;
use FreeElephants\HexammonServer\Auth\Model\AuthKey\AuthKeyProviderInterface;
use FreeElephants\HexammonServer\Auth\Model\User\UserRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class GetUserCollectionHandler extends AbstractHandler
{

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var AuthKeyProviderInterface
     */
    private $serviceAuthClient;

    public function __construct(UserRepository $userRepository, AuthKeyProviderInterface $serviceAuthClient)
    {
        $this->userRepository = $userRepository;
        $this->serviceAuthClient = $serviceAuthClient;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        $authKey = $request->getHeaderLine('Authorization');
        if ($this->serviceAuthClient->isAuthKeyPresent($authKey)) {
            $newResponse = $response->withStatus(200);
            $users = $this->userRepository->getAll();
            $newResponse->getBody()->write($this->serializeCollection($users, $request));
        } else {
            $newResponse = $response->withStatus(401);
        }
        return $next($request, $newResponse);
    }

    public function getHandlerScopeBeforeMiddleware(): array
    {
        return [
//            AuthorizationChecker::class,
        ];
    }
}