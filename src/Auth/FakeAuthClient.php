<?php

namespace FreeElephants\HexammonServer\Auth;

use FreeElephants\HexammonServer\Model\User\UserInterface;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class FakeAuthClient implements AuthClientInterface
{

    /**
     * @var array|UserInterface[]
     */
    private $authKeyUserMap;

    public function __construct(array $authKeyUserMap)
    {
        $this->authKeyUserMap = $authKeyUserMap;
    }

    public function isAuthKeyValid(string $authKey): bool
    {
        return isset($this->authKeyUserMap[$authKey]);
    }

    public function getUserByAuthKey(string $authKey): UserInterface
    {
        if ($this->isAuthKeyValid($authKey)) {
            return $this->authKeyUserMap[$authKey];
        } else {
            throw new \RuntimeException();
        }
    }
}