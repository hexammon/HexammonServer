<?php

namespace FreeElephants\HexoNardsGameServer\Message;

use FreeElephants\HexoNardsGameServer\Model\Room\Room;
use FreeElephants\HexoNardsGameServer\Model\Room\RoomDTO;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class RoomsListResponse extends AbstractEventMessage
{
    /**
     * @var array
     */
    private $rooms;

    public function __construct(array $rooms)
    {
        $this->rooms = $rooms;
    }

    public function toString(): string
    {
        $data = [
            'eventType' => $this->getEventType(),
            'eventData' => [
                'rooms' => array_map(function (Room $room) {
                    return new RoomDTO($room);
                }, $this->rooms)
            ]
        ];
        return json_encode($data);
    }
}