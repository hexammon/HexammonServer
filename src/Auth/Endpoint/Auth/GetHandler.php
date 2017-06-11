<?php

namespace FreeElephants\HexammonServer\Auth\Endpoint\Auth;

use FreeElephants\HexammonServer\Auth\Endpoint\AbstractHandler;
use FreeElephants\HexammonServer\Auth\Model\AuthKey\AuthKeyProviderInterface;
use FreeElephants\HexammonServer\Auth\Model\AuthKey\CompositeAuthKeyProvider;
use FreeElephants\HexammonServer\Auth\Model\AuthKey\Exception\UnknownAuthKeyException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class GetHandler extends AbstractHandler
{
    /**
     * @var AuthKeyProviderInterface
     */
    private $authKeyProvider;

    public function __construct(AuthKeyProviderInterface $authKeyProvider)
    {
        $this->authKeyProvider = $authKeyProvider;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface
    {
        $authKey = $request->getAttribute('authKey');
        try {
            $user = $this->authKeyProvider->getUserByAuthKey($authKey);
            $response->getBody()->write(json_encode([
                'authKey' => $authKey,
                'user' => [
                    'id' => $user->getId(),
                    'login' => $user->getLogin(),
                ]
            ]));
        } catch (UnknownAuthKeyException $e) {
            $response = $response->withStatus(404);
        }

        return $next($request, $response);
    }
}