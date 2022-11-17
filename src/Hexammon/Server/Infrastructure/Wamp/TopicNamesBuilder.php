<?php
declare(strict_types=1);

namespace Hexammon\Server\Infrastructure\Wamp;

use Hexammon\Server\Domain\Player\Room;

class TopicNamesBuilder
{
    public const ROOMS_TOPIC = 'net.hexammon.rooms';

    public function getRoomTopicName(Room $room): string
    {
        return sprintf('net.hexammon.rooms.%s', str_replace('-', '_', $room->getId()->toString()));
    }
}
