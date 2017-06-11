<?php

namespace FreeElephants\HexammonServer\Auth\Endpoint\DTO;

use FreeElephants\RestDaemon\DTO\DTOMapperInterface;
use Psr\Http\Message\UriInterface;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
abstract class AbstractDTOMapper implements DTOMapperInterface
{
    protected $baseModuleUri;

    public function setBaseModuleUri(UriInterface $baseModuleUri)
    {
        $this->baseModuleUri = $baseModuleUri;
    }
}