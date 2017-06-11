<?php

namespace FreeElephants\RestDaemon\Middleware\Collection;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class ClonableMiddlewareCollection extends DefaultEndpointMiddlewareCollection
{

    public function __clone()
    {
        $this->after = clone $this->after;
        $this->before = clone $this->before;
    }
}