<?php

namespace FreeElephants\RestDaemon\DTO;

use Psr\Http\Message\UriInterface;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
interface DTOFactoryInterface
{

    public function createDTO($entity, UriInterface $baseModuleUri): DTOInterface;

    public function createCollection($entities, UriInterface $baseModuleUri): DTOCollectionInterface;
}