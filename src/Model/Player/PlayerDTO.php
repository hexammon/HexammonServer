<?php

namespace FreeElephants\HexoNardsGameServer\Model\Player;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class PlayerDTO
{

    public $id;
    public $login;
    public $color;

    public function __construct(Player $player)
    {
        $this->id = $player->getId();
        $this->login = $player->getLogin();
        $this->color = $player->getColor()->toHtmlString();
    }
}