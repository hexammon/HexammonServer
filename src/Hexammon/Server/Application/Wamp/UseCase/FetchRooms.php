<?php
declare(strict_types=1);

namespace Hexammon\Server\Application\Wamp\UseCase;

use Hexammon\Server\Domain\Room\RoomRepositoryInterface;
use Hexammon\Server\Infrastructure\Mapper\RoomMapper;

class FetchRooms
{

    private RoomRepositoryInterface $roomRepository;
    private RoomMapper $mapper;

    public function __construct(RoomRepositoryInterface $roomRepository, RoomMapper $mapper)
    {
        $this->roomRepository = $roomRepository;
        $this->mapper = $mapper;
    }

    public function __invoke(): array
    {
        $rooms = $this->roomRepository->getAll();
        return $this->mapper->mapCollection($rooms);
    }
}
