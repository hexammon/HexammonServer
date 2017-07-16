<?php

namespace FreeElephants\DataBox\Collection;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
interface EntityCollectionInterface extends \Countable, \ArrayAccess
{
    public function getEntityClassName(): string;
}