<?php

namespace FreeElephants\HexammonServer\Auth\Endpoint\Users;

use FreeElephants\HexammonServer\Auth\Endpoint\AbstractHandler;
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

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->serviceAuthClient = $serviceAuthClient;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface
    {

        $authKey = $request->getHeaderLine('Authorization');
        if (!empty($authKey) && $this->serviceAuthClient->isValidServiceAuthKey($authKey)) {
            $consumerService = $this->serviceAuthClient->getService($authKey);
            if ($consumerService->getName() === 'banking') {
                $processedResponse  = $response->withStatus(200);
                $userId = $request->getAttribute('userId');
                $user = $this->userRepository->getUserById($userId);
                $processedResponse ->getBody()->write($this->serializeEntity($user, $request));
            } else {
                $processedResponse = $response->withStatus(403);
            }
        } else {
            $processedResponse  = $response->withStatus(401);
        }

        return $next($request, $processedResponse);
    }
}