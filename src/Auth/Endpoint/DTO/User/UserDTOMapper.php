<?php

namespace FreeElephants\HexammonServer\Auth\Endpoint\DTO\User;

use FreeElephants\HexammonServer\Auth\Endpoint\DTO\AbstractDTOMapper;
use FreeElephants\HexammonServer\Auth\Model\User\User;
use FreeElephants\RestDaemon\DTO\DTOInterface;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class UserDTOMapper extends AbstractDTOMapper
{

    /**
     * @param User $entity
     * @return DTOInterface
     */
    public function assemble($entity): DTOInterface
    {
        $dto = new UserDTO();
        $dto->id = $entity->getId();
        $dto->login = $entity->getLogin();
        return $dto;
    }
}