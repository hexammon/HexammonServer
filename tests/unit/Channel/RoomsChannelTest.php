<?php
namespace FreeElephants\HexammonServerTests\Channel;

use FreeElephants\HexammonServer\Channel\RoomsChannel;
use FreeElephants\HexammonServer\Model\Player\Player;
use FreeElephants\HexammonServer\Model\Room\Room;
use FreeElephants\HexammonServer\Model\Room\RoomRepository;
use FreeElephants\HexammonServer\Model\User\UserInterface;
use FreeElephants\HexammonServerTests\AbstractTestCase;
use FreeElephants\HexoNards\Board\Board;
use FreeElephants\HexoNards\Board\Column;
use FreeElephants\HexoNards\Board\Row;
use FreeElephants\Phalette\RGB;
use Ratchet\ConnectionInterface;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class RoomsChannelTest extends AbstractTestCase
{

    public function testRoomsListRequest_and_Response()
    {
        $roomRepository = $this->createMock(RoomRepository::class);
        $board = $this->createMock(Board::class);
        $board->method('getType')->willReturn('hex');
        $rows = array_fill(1, 8, $this->createMock(Row::class));
        $columns = array_fill(1, 8, $this->createMock(Column::class));
        $board->method('getRows')->willReturn($rows);
        $board->method('getColumns')->willReturn($columns);
        $user = $this->createMock(UserInterface::class);
        $user->method('getId')->willReturn(1);
        $user->method('getLogin')->willReturn('foo');
        $roomRepository->method('getRooms')->willReturn([
            new Room(2, $board, new Player($user, new RGB(0xFF, 0xFF, 0xFF)))
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
                "boardConfig": {
                    "type": "hex",
                    "numberOfRows": 8, 
                    "numberOfColumns": 8 
                },
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