<?php

namespace FreeElephants\RestDaemon\Serialization;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
interface SerializerInterface
{

    /**
     * @param mixed $object
     * @return string
     */
    public function serialize($object): string;
}