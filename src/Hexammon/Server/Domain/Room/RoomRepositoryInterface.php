<?php

namespace Hexammon\Server\Domain\Room;

use Hexammon\Server\Domain\Player\Room;
use Ramsey\Uuid\UuidInterface;

interface RoomRepositoryInterface
{
    public function addRoom(Room $room);

    /**
     * @return array|Room[]
     */
    public function getAll(): array;

    public function getRoomById(UuidInterface $roomId): Room;
}
