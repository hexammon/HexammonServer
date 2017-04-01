<?php

namespace FreeElephants\HexammonServer\Model\Player;

use FreeElephants\HexammonServer\Model\User\UserDTO;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class PlayerDTO
{

    public $user;
    public $color;

    public function __construct(Player $player)
    {
        $this->user = new UserDTO($player->getUser());
        $this->color = $player->getColor()->toHtmlString();
    }
}