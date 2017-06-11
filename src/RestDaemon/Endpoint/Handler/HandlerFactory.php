<?php

namespace FreeElephants\RestDaemon\Endpoint\Handler;

use FreeElephants\DI\Injector;
use FreeElephants\RestDaemon\DTO\DTOFactoryInterface;
use FreeElephants\RestDaemon\Endpoint\EndpointMethodHandlerInterface;
use FreeElephants\RestDaemon\Serialization\SerializerInterface;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class HandlerFactory implements HandlerFactoryInterface
{

    /**
     * @var Injector
     */
    private $di;

    public function __construct(Injector $di)
    {
        $this->di = $di;
    }

    public function buildHandler(string $className): EndpointMethodHandlerInterface
    {
        /**@var $handler AbstractSerializerAwareHandler */
        $handler = $this->di->createInstance($className);
        $dtoFactory = $this->di->getService(DTOFactoryInterface::class);
        $handler->setDTOFactory($dtoFactory);
        $serializer = $this->di->getService(SerializerInterface::class);
        $handler->setSerializer($serializer);
        $handlerMiddleware = $handler->getHandlerScopeBeforeMiddleware();
        foreach ($handlerMiddleware as $middlewareClassOrInstance) {
            if (is_string($middlewareClassOrInstance)) {
                if (!$this->di->hasImplementation($middlewareClassOrInstance)) {
                    $this->di->registerService($middlewareClassOrInstance, $middlewareClassOrInstance);
                }
                $middleware = $this->di->getService($middlewareClassOrInstance);
            } else {
                $middleware = $middlewareClassOrInstance;
            }

            $handler->addBeforeMiddleware($middleware);
        }
        return $handler;
    }
}