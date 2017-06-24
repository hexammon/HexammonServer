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

    public function __construct(
        PdoReconnectWrapper $pdoReconnectWrapper,
        UserRepository $userRepository/*, ApqpClientInterface $amqpClient*/
    )
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
        $user = new User();
        $user->setLogin($login);
        $user->setPasswordHash($passwordHash);
        $user->setEmail($email);
        $this->userRepository->save($user);
        return $user;
    }

    private function isLoginOrEmailUsed(string $login, string $email): bool
    {
        $user = $this->userRepository->findOneBy([
            'login' => $login,
            'email' => $email
        ]);

        return isset($user);
    }
}