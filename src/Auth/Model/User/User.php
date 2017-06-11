<?php

namespace FreeElephants\HexammonServer\Auth\Model\User;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class User
{

    private $id;
    /**
     * @var string
     */
    private $login;
    /**
     * @var string
     */
    private $passwordHash;

    public function __construct($id, string $login, string $passwordHash)
    {
        $this->id = $id;
        $this->login = $login;
        $this->passwordHash = $passwordHash;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->passwordHash);
    }

}