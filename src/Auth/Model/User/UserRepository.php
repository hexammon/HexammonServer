<?php

namespace FreeElephants\HexammonServer\Auth\Model\User;

use FreeElephants\Db\PdoReconnectWrapper;
use FreeElephants\HexammonServer\Auth\Model\User\Exception\UserNotFoundException;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class UserRepository
{

    /**
     * @var PdoReconnectWrapper
     */
    private $pdoReconnectWrapper;

    public function __construct(PdoReconnectWrapper $pdoReconnectWrapper)
    {
        $this->pdoReconnectWrapper = $pdoReconnectWrapper;
    }

    public function getUserByLogin(string $login): User
    {
        $query = $this->pdoReconnectWrapper->getConnection()->prepare('SELECT * from `user`
          WHERE 
          `login` = :login
          LIMIT 1');
        $query->execute([':login' => $login]);
        if ($query->rowCount()) {
            $userData = $query->fetch();
            $user = new User($userData['id'], $userData['login'], $userData['password_hash']);
            return $user;
        } else {
            throw new UserNotFoundException();
        }
    }

    public function getAll(): array
    {
        $query = $this->pdoReconnectWrapper->getConnection()->prepare("SELECT * FROM `user`");
        $query->execute();
        $users = [];
        foreach ($query->fetchAll() as $userData) {
            $users[] = new User($userData['id'], $userData['login'], $userData['password_hash']);
        }

        return $users;
    }

    public function getUserById($id): User
    {
        $query = $this->pdoReconnectWrapper->getConnection()->prepare('SELECT * from `user`
          WHERE 
          `id` = :id
          LIMIT 1');
        $query->execute([':id' => $id]);
        if ($query->rowCount()) {
            $userData = $query->fetch();
            $user = new User($userData['id'], $userData['login'], $userData['password_hash']);
            return $user;
        } else {
            throw new UserNotFoundException();
        }
    }
}