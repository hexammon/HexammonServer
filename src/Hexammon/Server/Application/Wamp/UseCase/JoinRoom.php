<?php
declare(strict_types=1);


namespace Hexammon\Server\Application\Wamp\UseCase;

use Hexammon\HexoNards\Board\Board;
use Hexammon\HexoNards\Board\BoardBuilder;
use Hexammon\HexoNards\Game\Game;
use Hexammon\Server\Domain\Player\PlayerRepositoryInterface;
use Hexammon\Server\Domain\Room\RoomRepositoryInterface;
use Hexammon\Server\Infrastructure\Mapper\RoomMapper;
use Hexammon\Server\Infrastructure\Wamp\SessionProvider;
use Hexammon\Server\Infrastructure\Wamp\TopicNamesBuilder;
use Ramsey\Uuid\Uuid;

class JoinRoom
{

    private RoomRepositoryInterface $roomRepository;
    private PlayerRepositoryInterface $playerRepository;
    private SessionProvider $sessionProvider;
    private TopicNamesBuilder $topicNamesBuilder;
    private RoomMapper $roomMapper;

    public function __construct(
        RoomRepositoryInterface   $roomRepository,
        PlayerRepositoryInterface $playerRepository,
        SessionProvider           $sessionProvider,
        TopicNamesBuilder         $topicNamesBuilder,
        RoomMapper                $roomMapper
    )
    {
        $this->roomRepository = $roomRepository;
        $this->playerRepository = $playerRepository;
        $this->sessionProvider = $sessionProvider;
        $this->topicNamesBuilder = $topicNamesBuilder;
        $this->roomMapper = $roomMapper;
    }

    public function __invoke($args)
    {
        $roomId = Uuid::fromString($args[0]);
        $playerName = $args[1];

        $room = $this->roomRepository->getRoomById($roomId);
        if ($room->isFilled()) {
            throw new \DomainException('Room is filled');
        }

        $player = $this->playerRepository->getPlayerByName($playerName);
        $room->joinPlayer($player);
        if ($room->isFilled()) {
            $room->initGame();

            $roomTopic = $this->topicNamesBuilder->getRoomTopicName($room);
            $this->sessionProvider->getClientSession()->publish($roomTopic, $this->roomMapper->mapSingle($room));
        }
    }

}
