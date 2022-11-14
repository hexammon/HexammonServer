<?php

namespace Hexammon\Server\Domain\Room;

use Hexammon\Server\Domain\Player\Room;

interface RoomRepositoryInterface
{
    public function addRoom(Room $room);

    /**
     * @return array|Room[]
     */
    public function getAll(): array;
}
