<?php

namespace FreeElephants\HexammonServer\Model\User;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class UserDTO
{

    public $id;
    public $login;

    public function __construct(UserInterface $user)
    {
        $this->id = $user->getId();
        $this->login = $user->getLogin();
    }
}