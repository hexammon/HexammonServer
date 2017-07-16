<?php

namespace FreeElephants\HexammonServer\Auth\Endpoint\Users;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Tools\Pagination\Paginator;
use FreeElephants\HexammonServer\Auth\Endpoint\AbstractHandler;
use FreeElephants\HexammonServer\Auth\Model\AuthKey\AuthKeyProviderInterface;
use FreeElephants\HexammonServer\Auth\Model\User\UserRepository;
use FreeElephants\RestDaemon\DTO\BaseHalPaginatorDTO;
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
            $this->userRepository->matching(new Criteria(null, null, null, 20));
            $query = $this->userRepository->createNamedQuery('active');
            $paginator = new Paginator($query);
            $total = $paginator->count();
            $currentPageNumber = 1;
            $offset = (int) (20 / ($currentPageNumber - 1));
            $paginator->getQuery()->setMaxResults(20)->setFirstResult($offset);
            $newResponse->getBody()->write($this->serializeCollection($paginator->getQuery()->getResult(), new BaseHalPaginatorDTO('/api/v1/users', $total, 20, $offset)));
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