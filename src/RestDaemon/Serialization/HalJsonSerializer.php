<?php

namespace FreeElephants\RestDaemon\Serialization;

use FreeElephants\HexammonServer\Auth\Endpoint\DTO\User\UserCollectionMapping;
use FreeElephants\HexammonServer\Auth\Endpoint\DTO\User\UserMapping;
use NilPortugues\Api\Hal\HalSerializer;
use NilPortugues\Api\Hal\JsonTransformer;
use NilPortugues\Api\Mapping\Mapper;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class HalJsonSerializer implements SerializerInterface
{

    private $serializer;

    public function __construct()
    {
        $mapper = new Mapper([
            UserMapping::class,
            UserCollectionMapping::class,
        ]);

        $transformer = new JsonTransformer($mapper);
        $this->serializer = new HalSerializer($transformer);

    }

    /**
     * @param mixed $object
     * @return string
     */
    public function serialize($object): string
    {
        return $this->serializer->serialize($object);
    }
}