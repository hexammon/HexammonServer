<?php

namespace FreeElephants\RestDaemon\Endpoint;

use FreeElephants\DI\Injector;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class EndpointFactory implements EndpointFactoryInterface
{

    /**
     * @var Injector
     */
    private $di;

    public function __construct(Injector $di)
    {
        $this->di = $di;
    }

    public function buildEndpoint(string $endpointPath, array $endpointConfig): EndpointInterface
    {
        $name = $endpointConfig['name'];
        $endpoint = new BaseCustomizableMiddlewareScopeEndpoint($endpointPath, $name);
        $middlewareClasses = isset($endpointConfig['middleware']) ? $endpointConfig['middleware'] : [];
        foreach ($middlewareClasses as $middlewareClassName) {
            if (!$this->di->hasImplementation($middlewareClassName)) {
                $this->di->registerService($middlewareClassName, $middlewareClassName);
            }
            $middleware = $this->di->getService($middlewareClassName);
            $endpoint->addEndpointScopeBeforeMiddleware($middleware);
        }
        return $endpoint;
    }
}