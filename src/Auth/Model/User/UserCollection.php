<?php

namespace FreeElephants\HexammonServer\Auth\Model\User;

use FreeElephants\DataBox\Collection\BaseEntityCollection;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class UserCollection extends BaseEntityCollection
{
    private $users;

    public function __construct($entities = [])
    {
        parent::__construct(User::class, $entities);
        $this->users = $entities;
    }
}