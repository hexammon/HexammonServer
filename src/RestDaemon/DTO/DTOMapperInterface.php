<?php

namespace FreeElephants\RestDaemon\DTO;

use Psr\Http\Message\UriInterface;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
interface DTOMapperInterface
{

    public function setBaseModuleUri(UriInterface $baseModuleUri);

    public function assemble($entity): DTOInterface;
}