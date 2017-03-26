<?php

namespace FreeElephants\HexammonServer\Model\Room;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class RoomRepository implements \Countable
{
    private $rooms = [];

    public function addRoom(Room $room)
    {
        $this->rooms[] = $room;
    }

    public function getRooms(): array
    {
        return $this->rooms;
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count(): int
    {
        return count($this->rooms);
    }
}