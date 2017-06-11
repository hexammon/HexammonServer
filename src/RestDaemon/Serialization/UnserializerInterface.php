<?php

namespace FreeElephants\RestDaemon\Serialization;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
interface UnserializerInterface
{
    /**
     * @param string $serializedData
     * @return mixed
     */
    public function unserialize(string $serializedData);
}