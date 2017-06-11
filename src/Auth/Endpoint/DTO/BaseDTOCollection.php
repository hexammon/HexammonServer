<?php

namespace FreeElephants\HexammonServer\Auth\Endpoint\DTO;

use FreeElephants\RestDaemon\DTO\DTOCollectionInterface;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class BaseDTOCollection implements DTOCollectionInterface
{
    public $_links = [];
    public $items = [];
}