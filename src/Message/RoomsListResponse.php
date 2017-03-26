<?php

namespace FreeElephants\HexammonServer\Message;

use FreeElephants\HexammonServer\Message\Server\AbstractServerMessage;
use FreeElephants\HexammonServer\Model\Room\Room;
use FreeElephants\HexammonServer\Model\Room\RoomDTO;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class RoomsListResponse extends AbstractServerMessage
{
    /**
     * @var array
     */
    private $rooms;

    public function __construct(array $rooms)
    {
        $this->rooms = $rooms;
    }

    public function getEventData()
    {
        $data = [
            'rooms' => array_map(function (Room $room) {
                return new RoomDTO($room);
            }, $this->rooms)
        ];
        return $data;
    }
}