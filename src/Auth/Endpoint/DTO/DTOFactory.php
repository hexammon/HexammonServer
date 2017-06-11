<?php

namespace FreeElephants\HexammonServer\Auth\Endpoint\DTO;

use FreeElephants\HexammonServer\Auth\Endpoint\DTO\User\UserDTOMapper;
use FreeElephants\HexammonServer\Auth\Model\User\User;
use FreeElephants\RestDaemon\DTO\DTOCollectionInterface;
use FreeElephants\RestDaemon\DTO\DTOFactoryInterface;
use FreeElephants\RestDaemon\DTO\DTOInterface;
use FreeElephants\RestDaemon\DTO\DTOMapperInterface;
use Psr\Http\Message\UriInterface;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class DTOFactory implements DTOFactoryInterface
{

    private $dtoMappers = [
        User::class => UserDTOMapper::class,
    ];

    public function createDTO($entity, UriInterface $baseModuleUri): DTOInterface
    {
        $mapper = $this->getMapper(get_class($entity));
        $mapper->setBaseModuleUri($baseModuleUri);
        return $mapper->assemble($entity);
    }

    public function createCollection($entities, UriInterface $baseModuleUri): DTOCollectionInterface
    {
        $collection = new BaseDTOCollection();
        foreach ($entities as $entity) {
            $dto = $this->createDTO($entity, $baseModuleUri);
            $collection->items[] = $dto;
        }

        return $collection;
    }

    protected function getMapper($entityClassName): DTOMapperInterface
    {
        if (isset($this->dtoMappers[$entityClassName])) {
            if (is_string($this->dtoMappers[$entityClassName])) {
                $mapperClassName = $this->dtoMappers[$entityClassName];
                $this->dtoMappers[$entityClassName] = $this->initMapper($mapperClassName);
            }

            return $this->dtoMappers[$entityClassName];
        } else {
            throw new \OutOfBoundsException('Mapper for entity type ' . $entityClassName . ' not found. ');
        }
    }

    private function initMapper($mapperClassName): DTOMapperInterface
    {
        return new $mapperClassName;
    }
}