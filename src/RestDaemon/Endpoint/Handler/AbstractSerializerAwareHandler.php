<?php

namespace FreeElephants\RestDaemon\Endpoint\Handler;

use FreeElephants\RestDaemon\DTO\DTOFactoryInterface;
use FreeElephants\RestDaemon\DTO\HalPaginatorDTOInterface;
use FreeElephants\RestDaemon\Endpoint\AbstractEndpointMethodHandler;
use FreeElephants\RestDaemon\Middleware\Collection\EndpointMiddlewareCollectionInterface;
use FreeElephants\RestDaemon\Middleware\MiddlewareInterface;
use FreeElephants\RestDaemon\Serialization\SerializerInterface;
use NilPortugues\Api\Hal\HalPagination;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
abstract class AbstractSerializerAwareHandler extends AbstractEndpointMethodHandler
{
    protected $handlerBeforeMiddleware = [];

    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var DTOFactoryInterface
     */
    private $dtoFactory;

    public function setDTOFactory(DTOFactoryInterface $DTOFactory)
    {
        $this->dtoFactory = $DTOFactory;
    }

    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function serializeEntity($entity): string
    {
        return $this->serializer->serialize($entity);
    }

    public function serializeCollection(iterable $entities, HalPaginatorDTOInterface $paginatorDTO): string
    {
        $page = new HalPagination();
        $page->setTotal($paginatorDTO->getTotal());
        $page->setCount(count($entities));
        $page->setSelf($paginatorDTO->getBasePaginationLinkHref());
        $page->setFirst($paginatorDTO->getBasePaginationLinkHref() . '?page=1');
        $page->setPrev($paginatorDTO->getBasePaginationLinkHref() . '?page=1');
        $page->setLast($paginatorDTO->getBasePaginationLinkHref() . '?page=1');
        $page->setNext($paginatorDTO->getBasePaginationLinkHref() . '?page=1');
        $page->setEmbedded(['users' => $entities]);
        return $this->serializer->serialize($page);
    }

    public function addBeforeMiddleware(MiddlewareInterface $middleware)
    {
        $this->handlerBeforeMiddleware[] = $middleware;
    }

    public function getCustomBeforeMiddleware(): array
    {
        return $this->handlerBeforeMiddleware;
    }

    public function getHandlerScopeBeforeMiddleware(): array
    {
        return [];
    }

    public function setMiddlewareCollection(EndpointMiddlewareCollectionInterface $endpointMiddlewareCollection)
    {
        $extendedEndpointMiddlewareCollection = clone $endpointMiddlewareCollection;
        foreach ($this->getCustomBeforeMiddleware() as $key => $middleware) {
            $extendedEndpointMiddlewareCollection->getBefore()->offsetSet($key, $middleware);
        }
        parent::setMiddlewareCollection($extendedEndpointMiddlewareCollection);
    }
}