<?php

namespace FreeElephants\HexammonServer\Auth\Model\AuthKey;

use FreeElephants\HexammonServer\Auth\Model\AuthKey\Exception\UnknownAuthKeyException;
use FreeElephants\HexammonServer\Auth\Model\User\User;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class UserAuthKeyProvider implements AuthKeyProviderInterface
{

    private $userAuthKeyMap = [];

    public function getUserByAuthKey(string $authKey): User
    {
        if ($this->isAuthKeyPresent($authKey)) {
            return $this->userAuthKeyMap[$authKey];
        } else {
            throw new UnknownAuthKeyException('Auth key ' . $authKey . ' not exists. ');
        }
    }

    public function isAuthKeyPresent(string $authKey): bool
    {
        return array_key_exists($authKey, $this->userAuthKeyMap);
    }

    public function generateAuthKey(User $user): string
    {
        $authKey = base64_encode(sha1($user->getId() . random_bytes(8)));
        $this->userAuthKeyMap[$authKey] = $user;
        return $authKey;
    }
}