<?php

namespace FreeElephants\HexammonServer\Auth\Model\AuthKey;

use FreeElephants\HexammonServer\Auth\Model\User\User;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
interface AuthKeyProviderInterface
{
    public function getUserByAuthKey(string $authKey): User;

    public function isAuthKeyPresent(string $authKey): bool;

    public function generateAuthKey(User $user): string;
}