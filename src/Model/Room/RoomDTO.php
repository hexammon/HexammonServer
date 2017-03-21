<?php

namespace FreeElephants\HexoNardsGameServer\Model\Room;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class RoomDTO
{
    /**
     * @var Room
     */
    private $room;

    public $numberOfPlayers;

    public $boardType;

    public $boardSize;

    public $players;

    public function __construct(Room $room)
    {
        $this->numberOfPlayers = $room->getNumberOfPlayers();
        // TODO hardcoded for test
//        $this->boardType = $room->getBoard()->getType();
        $this->boardType = 'hex';
        // TODO hardcoded for test
//        $this->boardSize = $room->getBoard()->getSize();
        $this->boardSize = 8;
        $this->players = $room->getPlayers();
    }
}