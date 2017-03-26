<?php

namespace FreeElephants\HexoNardsGameServer\Model\Room;

use FreeElephants\HexoNardsGameServer\Model\Board\BoardConfigDTO;
use FreeElephants\HexoNardsGameServer\Model\Player\Player;
use FreeElephants\HexoNardsGameServer\Model\Player\PlayerDTO;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class RoomDTO
{
    public $numberOfPlayers;

    public $boardConfig;

    public $players;

    public function __construct(Room $room)
    {
        $this->numberOfPlayers = $room->getNumberOfPlayers();
        $this->boardConfig = new BoardConfigDTO($room->getBoard());
        $this->players = array_map(function(Player $player){
            return new PlayerDTO($player);
        }, $room->getPlayers());}
}