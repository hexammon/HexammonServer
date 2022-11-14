<?php
declare(strict_types=1);

namespace Hexammon\Server\Application\Wamp\UseCase;

use Hexammon\Server\Domain\Room\RoomRepositoryInterface;

class FetchRooms
{

    private RoomRepositoryInterface $roomRepository;

    public function __construct(RoomRepositoryInterface $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    public function __invoke(): array
    {
        $rooms = $this->roomRepository->getAll();

        $roomsData = [];
        foreach ($rooms as $room) {
            $roomsData[] = [
                'type'       => 'rooms',
                'id'         => $room->getId(),
                'attributes' => [
                    'name'                  => $room->getName(),
                    'createdBy'             => $room->getCreator(),
                    'isFilled'              => $room->isFilled(),
                    'numberOfPlayer'        => $room->getNumberOfPlayers(),
                    'numberOfJoinedPlayers' => $room->getNumberOfJoinedPlayers(),
                    'createdAt' => $room->getCreatedAt(),
                ],
            ];
        }
        return [
            'data' => $roomsData
        ];
    }
}
