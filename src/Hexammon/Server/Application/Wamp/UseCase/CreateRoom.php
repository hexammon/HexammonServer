<?php
declare(strict_types=1);


namespace Hexammon\Server\Application\Wamp\UseCase;

use Hexammon\Server\Domain\Player\PlayerRepositoryInterface;
use Hexammon\Server\Domain\Player\Room;
use Hexammon\Server\Domain\Room\RoomRepositoryInterface;
use Hexammon\Server\Infrastructure\Wamp\SessionProvider;
use Hexammon\Server\Infrastructure\Wamp\TopicNamesBuilder;
use Ramsey\Uuid\Uuid;

class CreateRoom
{
    private PlayerRepositoryInterface $playerRepository;
    private RoomRepositoryInterface $roomRepository;
    private SessionProvider $sessionProvider;
    private TopicNamesBuilder $topicNamesBuilder;

    public function __construct(
        PlayerRepositoryInterface $playerRepository,
        RoomRepositoryInterface   $roomRepository,
        SessionProvider           $sessionProvider,
        TopicNamesBuilder         $topicNamesBuilder
    )
    {
        $this->playerRepository = $playerRepository;
        $this->roomRepository = $roomRepository;
        $this->sessionProvider = $sessionProvider;
        $this->topicNamesBuilder = $topicNamesBuilder;
    }

    public function __invoke(array $args)
    {
        $roomName = $args[0];
        $playerName = $args[1];
        $player = $this->playerRepository->getPlayerByName($playerName);
        $roomId = Uuid::uuid4();
        $room = new Room($roomId, $roomName, $player);

        $this->roomRepository->addRoom($room);

        $roomTopic = $this->topicNamesBuilder->getRoomTopicName($room);
        $session = $this->sessionProvider->getClientSession();
        $session->subscribe($roomTopic, function (array $args, array $argsKw) {
            var_dump(['args', $args]);
            var_dump(['argsKw', $argsKw]);

            return [];
        });
        $session->publish($roomTopic, [], ['event' => 'roomCreated']);

        $session->publish(TopicNamesBuilder::ROOMS_TOPIC, [], ['type' => 'roomCreated', 'id' => $roomId->toString()]);

        return $roomTopic;

    }
}
