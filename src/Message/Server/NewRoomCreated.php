<?php

namespace FreeElephants\HexammonServer\Message\Server;

use FreeElephants\HexammonServer\Model\Room\Room;
use FreeElephants\HexammonServer\Model\Room\RoomDTO;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class NewRoomCreated extends AbstractServerMessage
{
    /**
     * @var Room
     */
    private $room;

    public function __construct(Room $room)
    {
        $this->room = $room;
    }

    public function getEventData()
    {
        return new RoomDTO($this->room);
    }
}