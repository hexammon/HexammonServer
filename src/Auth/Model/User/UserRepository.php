<?php

namespace FreeElephants\HexammonServer\Auth\Model\User;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use FreeElephants\Db\PdoReconnectWrapper;
use FreeElephants\HexammonServer\Auth\Model\User\Exception\UserNotFoundException;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class UserRepository extends EntityRepository
{

    public function __construct(EntityManager $em, ClassMetadata $class)
    {
        parent::__construct($em, new ClassMetadata(User::class));
        $this->getClassMetadata()->addNamedQuery([
            'name' => 'active',
            'query' => 'select u from __CLASS__ u',
        ]);

    }

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