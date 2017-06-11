<?php

namespace FreeElephants\RestDaemon\Middleware;

use Econ\AuthClient\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class AuthorizationChecker implements MiddlewareInterface
{

    /**
     * @var ClientInterface
     */
    private $authClient;

    public function __construct(ClientInterface $authClient)
    {
        $this->authClient = $authClient;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface
    {
        $authKey = $request->getHeaderLine('Authorization');
        if ($this->authClient->isAuthKeyValid($authKey)) {
            return $next($request, $response);
        } else {
            return $response->withStatus(401);
        }
    }
}