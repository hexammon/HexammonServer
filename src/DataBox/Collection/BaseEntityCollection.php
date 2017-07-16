<?php

namespace FreeElephants\DataBox\Collection;

use FreeElephants\HexammonServer\Exception\InvalidArgumentException;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class BaseEntityCollection extends \ArrayObject implements EntityCollectionInterface
{

    /**
     * @var string
     */
    private $entityClassName;

    public function __construct(string $entityClassName, iterable $entities = [])
    {
        $this->entityClassName = $entityClassName;
        array_walk($entities, function ($entity) {
            $this->assertEntityType($entity);
        });
        parent::__construct($entities);
    }

    public function offsetSet($index, $newval)
    {
        $this->assertEntityType($newval);
        parent::offsetSet($index, $newval);
    }

    public function append($value)
    {
        $this->assertEntityType($value);
        parent::append($value);
    }

    public function getEntityClassName(): string
    {
        return $this->entityClassName;
    }

    private function assertEntityType($value)
    {
        if (false === $value instanceof $this->entityClassName) {
            throw new InvalidArgumentException('Collection values must be instance of ' . $this->entityClassName);
        }
    }

}