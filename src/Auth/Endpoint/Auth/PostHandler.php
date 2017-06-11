<?php

namespace FreeElephants\HexammonServer\Auth\Endpoint\Auth;

use FreeElephants\HexammonServer\Auth\Endpoint\AbstractHandler;
use FreeElephants\HexammonServer\Auth\Model\AuthKey\AuthKeyProviderInterface;
use FreeElephants\HexammonServer\Auth\Model\User\Exception\UserNotFoundException;
use FreeElephants\HexammonServer\Auth\Model\User\UserRepository;
use FreeElephants\RestDaemon\Util\ParamsContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class PostHandler extends AbstractHandler
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
    ): ResponseInterface
    {
        /**@var $parsedBody ParamsContainer */
        $parsedBody = $request->getParsedBody();
        $login = $parsedBody->get('login');
        $password = $parsedBody->get('password');
        try {
            $user = $this->userRepository->getUserByLogin($login);
            $authKey = $this->authKeyProvider->generateAuthKey($user);
            if ($user->verifyPassword($password)) {
                $authKeyLocation = '/api/v1/auth/' . $authKey;
                $response = $response->withHeader('Location', $authKeyLocation)->withStatus(201);
                $response->getBody()->write(json_encode([
                    'authKey' => $authKey,
                    'user' => [
                        'id' => $user->getId(),
                        'login' => $user->getLogin(),
                    ]
                ]));
                return $next($request, $response);
            } else {
                return $response->withStatus(401);
            }
        } catch (UserNotFoundException $e) {
            return $response->withStatus(404);
        }
    }
}