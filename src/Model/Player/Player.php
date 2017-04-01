<?php

namespace FreeElephants\HexammonServer\Model\Player;

use FreeElephants\HexammonServer\Model\User\UserInterface;
use FreeElephants\HexoNards\Game\PlayerInterface;
use FreeElephants\Phalette\ColorInterface;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class Player implements PlayerInterface, UserInterface
{

    /**
     * @var UserInterface
     */
    private $user;
    /**
     * @var ColorInterface
     */
    private $color;

    public function __construct(UserInterface $user, ColorInterface $color)
    {
        $this->user = $user;
        $this->color = $color;
    }

    public function getId()
    {
        return $this->user->getId();
    }

    public function getLogin(): string
    {
        return $this->user->getLogin();
    }

    public function getColor(): ColorInterface
    {
        return $this->color;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }
}