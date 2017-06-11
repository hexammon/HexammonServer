<?php

namespace FreeElephants\RestDaemon\Serialization;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class JsonSerializer implements SerializerInterface
{

    /**
     * @param mixed $object
     * @return string - Json Value
     */
    public function serialize($object): string
    {
        $jsonEncoder = new JsonEncoder();
        $normalizer = new PropertyNormalizer(null);
        // TODO use custom Hydrators for dto objects, or add serializable methods to its.
        $normalizer->setIgnoredAttributes(['baseUri']);
        $serializer = new Serializer([$normalizer], [$jsonEncoder]);
        $jsonData = $serializer->serialize($object, 'json');
        return $jsonData;
    }
}