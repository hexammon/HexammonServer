<?php
namespace FreeElephants\HexoNardsGameServerTests\Channel;

use FreeElephants\HexoNards\Board\Board;
use FreeElephants\HexoNards\Game\Player;
use FreeElephants\HexoNardsGameServer\Channel\RoomsChannel;
use FreeElephants\HexoNardsGameServer\Model\Room\Room;
use FreeElephants\HexoNardsGameServer\Model\Room\RoomRepository;
use FreeElephants\HexoNardsGameServerTests\AbstractTestCase;
use Ratchet\ConnectionInterface;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class RoomsChannelTest extends AbstractTestCase
{

    public function testRoomsListRequest_and_Response()
    {
        $roomRepository = $this->createMock(RoomRepository::class);
        $roomRepository->method('getRooms')->willReturn([
            new Room(2, new Board(), new Player())
        ]);
        $channel = new RoomsChannel($roomRepository);
        $conn = $this->createMock(ConnectionInterface::class);
        $conn->expects($sendSpy = $this->any())->method('send');
        $requestData = '{
            "eventType": "RoomsListRequest",
        }';
        $channel->onMessage($conn, $requestData);
        $this->assertSame(1, $sendSpy->getInvocationCount());
        $expectedRoomsListJson = <<<JSON
{
    "eventType": "RoomsListResponse",
    "eventData": {
        "rooms": [
            {
                "numberOfPlayers": 2,
                "boardType": "hex",
                "boardSize": 8,
                "players": [
                    {
                        "id": 1,
                        "login": "foo",
                        "color": "#FFFFFF"
                    }
                ]
            }
        ]
    }
}
JSON;

        $roomsListMsg = $sendSpy->getInvocations()[0]->parameters[0];
        $this->assertJsonStringEqualsJsonString($expectedRoomsListJson, $roomsListMsg);
    }
}