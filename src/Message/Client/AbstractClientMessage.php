<?php

namespace FreeElephants\HexammonServer\Message\Client;

use FreeElephants\HexammonServer\Message\AbstractEventMessage;
use FreeElephants\HexammonServer\Model\User\UserInterface;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
abstract class AbstractClientMessage extends AbstractEventMessage
{

    private $user;

    public function setUser(UserInterface $user)
    {
        $this->user = $user;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }
}