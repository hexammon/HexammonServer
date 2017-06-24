<?php

namespace FreeElephants\HexammonServer\Auth\Model\User;

use Doctrine\ORM\EntityRepository;
use FreeElephants\Db\PdoReconnectWrapper;
use FreeElephants\HexammonServer\Auth\Model\User\Exception\UserNotFoundException;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class UserRepository extends EntityRepository
{

    /**
     * @var PdoReconnectWrapper
     */
    private $pdoReconnectWrapper;

    public function getUserByLogin(string $login): User
    {
        $user = $this->findOneBy(['login' => $login]);
        if ($user) {
            /** @var User $user */
            return $user;
        } else {
            throw new UserNotFoundException();
        }
    }

    public function save(User $user)
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }
}