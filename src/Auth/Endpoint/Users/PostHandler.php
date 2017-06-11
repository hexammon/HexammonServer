<?php

namespace FreeElephants\HexammonServer\Auth\Endpoint\Users;

use FreeElephants\HexammonServer\Auth\Endpoint\AbstractHandler;
use FreeElephants\HexammonServer\Auth\Model\User\Exception\LoginOrEmailUsedException;
use FreeElephants\HexammonServer\Auth\Model\User\UserRegistrationService;
use FreeElephants\HexammonServer\Auth\Model\User\UserRegistrationDTO;
use FreeElephants\RestDaemon\Util\ParamsContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class PostHandler extends AbstractHandler
{
    /**
     * @var UserRegistrationService
     */
    private $registerService;

    public function __construct(UserRegistrationService $registerService)
    {
        $this->registerService = $registerService;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface
    {
        /**@var $requestParams ParamsContainer */
        $requestParams = $request->getParsedBody();
        $errors = $this->validateRequestParams($requestParams);
        if (empty($errors)) {
            $login = $requestParams->get('login');
            $password = $requestParams->get('password');
            $email = $requestParams->get('email');
            $userRegistrationDTO = new UserRegistrationDTO($login, $password, $email);
            try {
                $user = $this->registerService->register($userRegistrationDTO);
                $userLocation = '/api/v1/users/' . $user->getId();
                $response->getBody()->write(json_encode([
                    'id' => $user->getId(),
                    'login' => $user->getLogin(),
                ]));
                return $next($request, $response->withStatus(201)->withHeader('Location', $userLocation));
            } catch (LoginOrEmailUsedException $e) {
                return $response->withStatus(422);
            }
        } else {
            $response->getBody()->write(json_encode($errors));
            return $response->withStatus(400);
        }
    }

    private function validateRequestParams(ParamsContainer $paramsContainer): array
    {
        $errors = [];
        $paramsContainer->has('login') ?: $errors['login'] = ['login is missed. '];
        $paramsContainer->has('email') ?: $errors['email'] = ['email is missed. '];
        $paramsContainer->has('password') ?: $errors['password'] = ['password is missed. '];
        return $errors;
    }
}