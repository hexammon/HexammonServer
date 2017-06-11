<?php

namespace FreeElephants\RestDaemon\Endpoint\Handler;

use FreeElephants\RestDaemon\DTO\DTOFactoryInterface;
use FreeElephants\RestDaemon\Endpoint\AbstractEndpointMethodHandler;
use FreeElephants\RestDaemon\Middleware\Collection\EndpointMiddlewareCollectionInterface;
use FreeElephants\RestDaemon\Middleware\MiddlewareInterface;
use FreeElephants\RestDaemon\Serialization\SerializerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Uri;

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

    public function serializeEntity($entity, ServerRequestInterface $request): string
    {
        $baseModuleUri = new Uri($this->getBaseServerUri($request) . $this->getEndpoint()->getModule()->getPath());
        $dto = $this->dtoFactory->createDTO($entity, $baseModuleUri);
        return $this->serializer->serialize($dto);
    }

    public function serializeCollection(array $entities, ServerRequestInterface $request): string
    {
        $baseModuleUri = new Uri($this->getBaseServerUri($request) . $this->getEndpoint()->getModule()->getPath());
        $dto = $this->dtoFactory->createCollection($entities, $baseModuleUri);
        return $this->serializer->serialize($dto);
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