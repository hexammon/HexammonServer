<?php
declare(strict_types=1);


namespace Hexammon\Server\Infrastructure\Repository;

use Hexammon\Server\Domain\Player\Room;
use Hexammon\Server\Domain\Room\Exception\RoomNotExists;
use Hexammon\Server\Domain\Room\RoomRepositoryInterface;
use Ramsey\Uuid\UuidInterface;

class RoomRepository implements RoomRepositoryInterface
{
    private array $rooms = [];

    public function addRoom(Room $room)
    {
        $this->rooms[$room->getId()->toString()] = $room;
    }

    public function getAll(): array
    {
        return array_values($this->rooms);
    }

    public function getRoomById(UuidInterface $roomId): Room
    {
        if (array_key_exists($roomId->toString(), $this->rooms)) {
            return $this->rooms[$roomId->toString()];
        }

        throw new RoomNotExists();
    }
}
