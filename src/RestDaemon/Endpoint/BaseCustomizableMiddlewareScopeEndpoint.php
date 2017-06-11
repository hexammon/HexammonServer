<?php

namespace FreeElephants\RestDaemon\Endpoint;

use FreeElephants\RestDaemon\Endpoint\Handler\AbstractSerializerAwareHandler;
use FreeElephants\RestDaemon\Middleware\MiddlewareInterface;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class BaseCustomizableMiddlewareScopeEndpoint extends BaseEndpoint
{

    protected $endpointBeforeMiddleware = [];

    public function setMethodHandler(string $method, EndpointMethodHandlerInterface $handler)
    {
        parent::setMethodHandler($method, $handler);
        if($handler instanceof AbstractSerializerAwareHandler) {
            foreach ($this->getEndpointScopeBeforeMiddleware() as $middleware) {
                $handler->addBeforeMiddleware($middleware);
            }
        }
    }

    public function addEndpointScopeBeforeMiddleware(MiddlewareInterface $middleware)
    {
        $this->endpointBeforeMiddleware[] = $middleware;
    }

    public function getEndpointScopeBeforeMiddleware(): array
    {
        return $this->endpointBeforeMiddleware;
    }
}
