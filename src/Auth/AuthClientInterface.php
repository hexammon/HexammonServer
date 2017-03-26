<?php

namespace FreeElephants\HexammonServer\Auth;

use FreeElephants\HexammonServer\Model\User\UserInterface;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
interface AuthClientInterface
{

    public function isAuthKeyValid(string $authKey): bool;

    public function getUserByAuthKey(string $authKey): UserInterface;
}