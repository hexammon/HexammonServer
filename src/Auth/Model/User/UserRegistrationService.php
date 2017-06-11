<?php

namespace FreeElephants\HexammonServer\Auth\Model\User;

use FreeElephants\Amqp\ApqpClientInterface;
use FreeElephants\Amqp\BaseEvent;
use FreeElephants\Db\PdoReconnectWrapper;
use FreeElephants\HexammonServer\Auth\Model\User\Exception\LoginOrEmailUsedException;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class UserRegistrationService
{

    /**
     * @var PdoReconnectWrapper
     */
    private $pdoReconnectWrapper;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var ApqpClientInterface
     */
    private $amqpClient;

    public function __construct(PdoReconnectWrapper $pdoReconnectWrapper, UserRepository $userRepository/*, ApqpClientInterface $amqpClient*/)
    {
        $this->pdoReconnectWrapper = $pdoReconnectWrapper;
        $this->userRepository = $userRepository;
//        $this->amqpClient = $amqpClient;
    }

    public function register(UserRegistrationDTO $registrationDTO): User
    {
        $PDO = $this->pdoReconnectWrapper->getConnection();
        $PDO->beginTransaction();

        $login = $registrationDTO->getLogin();
        $passwordHash = password_hash($registrationDTO->getPassword(), PASSWORD_DEFAULT);
        $email = $registrationDTO->getEmail();

        if ($this->isLoginOrEmailUsed($login, $email)) {
            $PDO->rollBack();
            throw new LoginOrEmailUsedException();
        }

        $insertUserQuery = $PDO->prepare('INSERT INTO `user`
          SET 
            `login` = :login,
            `password_hash` = :passwordHash,
            `email` = :email');
        $insertUserQuery->execute([
            ':login' => $login,
            ':passwordHash' => $passwordHash,
            ':email' => $email
        ]);
        if($PDO->commit()) {
//            $this->amqpClient->send(new BaseEvent('foo', 'bar'));
            return $this->userRepository->getUserByLogin($login);
        } else {
            throw new \RuntimeException($PDO->errorInfo(), $PDO->errorCode());
        }
    }

    private function isLoginOrEmailUsed(string $login, string $email): bool
    {
        $checkIsLoginOrEmailQuery = $this->pdoReconnectWrapper->getConnection()->prepare('SELECT count(`id`) FROM `user`
          WHERE
          `login` = :login OR `email` = :email
        ');
        $checkIsLoginOrEmailQuery->execute([
            ':login' => $login,
            ':email' => $email
        ]);
        $numberOfUsers = (int)$checkIsLoginOrEmailQuery->fetchColumn();
        return $numberOfUsers > 0;
    }
}