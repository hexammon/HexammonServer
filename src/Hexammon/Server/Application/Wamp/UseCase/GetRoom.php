<?php
declare(strict_types=1);

namespace Hexammon\Server\Application\Wamp\UseCase;

use Hexammon\Server\Domain\Room\RoomRepositoryInterface;
use Hexammon\Server\Infrastructure\Mapper\RoomMapper;
use Ramsey\Uuid\Uuid;

class GetRoom
{
    private RoomRepositoryInterface $roomRepository;
    private RoomMapper $mapper;

    public function __construct(RoomRepositoryInterface $roomRepository, RoomMapper $mapper)
    {
        $this->roomRepository = $roomRepository;
        $this->mapper = $mapper;
    }

    public function __invoke(array $args)
    {
        $roomId = Uuid::fromString($args[0]);
        $room = $this->roomRepository->getRoomById($roomId);
        return $this->mapper->mapSingle($room);
    }
}
