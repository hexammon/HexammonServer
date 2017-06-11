<?php

namespace FreeElephants\HexammonServer\Auth\Endpoint\DTO\User;

use FreeElephants\RestDaemon\DTO\DTOInterface;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class UserDTO implements DTOInterface
{
    public $id;
    public $login;
    public $email;
}