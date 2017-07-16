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
class GetUserByIdHandler extends AbstractHandler
{

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var AuthKeyProviderInterface
     */
    private $authKeyProvider;

    public function __construct(UserRepository $userRepository, AuthKeyProviderInterface $authKeyProvider)
    {
        $this->userRepository = $userRepository;
        $this->authKeyProvider = $authKeyProvider;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        $authKey = $request->getHeaderLine('Authorization');
        if ($this->authKeyProvider->isAuthKeyPresent($authKey)) {
            $processedResponse = $response->withStatus(200);
            $userId = $request->getAttribute('userId');
            $user = $this->userRepository->find($userId);
            $processedResponse->getBody()->write($this->serializeEntity($user));
        } else {
            $processedResponse = $response->withStatus(401);
        }

        return $next($request, $processedResponse);
    }
}