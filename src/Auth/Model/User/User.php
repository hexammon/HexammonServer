<?php

namespace FreeElephants\HexammonServer\Auth\Model\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author samizdam <samizdam@inbox.ru>
 * @ORM\Entity(repositoryClass="FreeElephants\HexammonServer\Auth\Model\User\UserRepository")
 * @ORM\Table(name="user")
 */
class User
{

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $login;

    /**
     * @ORM\Column(type="string")
     * @var
     */
    private $email;
    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $passwordHash;

    public function __construct($id, string $login, string $passwordHash)
    {
        $this->id = $id;
        $this->login = $login;
        $this->passwordHash = $passwordHash;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->passwordHash);
    }

}