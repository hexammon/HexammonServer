<?php
declare(strict_types=1);

namespace Hexammon\Server\Infrastructure\Mapper;

use Hexammon\Server\Domain\Player\Room;

class RoomMapper
{
    public function mapSingle(Room $room): array
    {
        return [
            'data' => $this->mapResource($room),
        ];
    }

    public function mapCollection(array $rooms): array
    {
        return [
            'data' => array_map([$this, 'mapResource'], $rooms)
        ];
    }

    private function mapResource(Room $room): array
    {
        return [
            'type'       => 'rooms',
            'id'         => $room->getId(),
            'attributes' => [
                'name'                  => $room->getName(),
                'createdBy'             => $room->getCreator()->getId(),
                'isFilled'              => $room->isFilled(),
                'numberOfPlayers'       => $room->getNumberOfPlayers(),
                'numberOfJoinedPlayers' => $room->getNumberOfJoinedPlayers(),
                'createdAt'             => $room->getCreatedAt()->format(\DateTimeInterface::ATOM),
            ],
        ];
    }

}
