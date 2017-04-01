<?php
namespace FreeElephants\HexammonServerTests\Channel;

use FreeElephants\HexammonServer\Auth\AuthClientInterface;
use FreeElephants\HexammonServer\Channel\RoomsChannel;
use FreeElephants\HexammonServer\Message\Client\ClientMessageFactory;
use FreeElephants\HexammonServer\Model\Player\Player;
use FreeElephants\HexammonServer\Model\Room\Room;
use FreeElephants\HexammonServer\Model\Room\RoomRepository;
use FreeElephants\HexammonServer\Model\User\UserInterface;
use FreeElephants\HexammonServerTests\AbstractTestCase;
use FreeElephants\HexoNards\Board\Board;
use FreeElephants\HexoNards\Board\BoardBuilder;
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
        $board = $this->createMock(Board::class);
        $board->method('getType')->willReturn('hex');
        $rows = array_fill(1, 8, $this->createMock(Row::class));
        $columns = array_fill(1, 8, $this->createMock(Column::class));
        $board->method('getRows')->willReturn($rows);
        $board->method('getColumns')->willReturn($columns);

        $user = $this->createMock(UserInterface::class);
        $user->method('getId')->willReturn(1);
        $user->method('getLogin')->willReturn('foo');

        $roomOwner = new Player($user, new RGB(0xFF, 0xFF, 0xFF));

        $roomRepository = $this->createMock(RoomRepository::class);
        $roomRepository->method('getRooms')->willReturn([
            new Room(2, $board, $roomOwner)
        ]);
        $authClient = $this->buildAuthClientMock($user);
        $channel = new RoomsChannel($roomRepository, new ClientMessageFactory($authClient), new BoardBuilder(), $authClient);
        $conn = $this->createMock(ConnectionInterface::class);
        $conn->expects($sendSpy = $this->any())->method('send');
        $requestData = '{
            "eventType": "RoomsListRequest",
            "authKey": "foo",
            "eventData": {}
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
                        "user": {
                            "id": 1,
                            "login": "foo"
                        }, 
                        "color": "#FFFFFF"
                    }
                ]
            }
        ]
    }
}
JSON;

        $roomsListMsg = $this->getFirstInvocationFirstArgument($sendSpy);
        $this->assertJsonStringEqualsJsonString($expectedRoomsListJson, $roomsListMsg);
    }

    public function testCreateNewRoom()
    {
        $roomRepository = new RoomRepository();
        $authClient = $this->buildAuthClientMock();
        $channel = new RoomsChannel($roomRepository, new ClientMessageFactory($authClient), new BoardBuilder(), $authClient);
        $otherConn = $this->createMock(ConnectionInterface::class);
        $otherConn->expects($otherConnSendSpy = $this->any())->method('send');
        $channel->onOpen($otherConn);
        $conn = $this->createMock(ConnectionInterface::class);


        $requestData =
<<<JSON
{
    "eventType": "CreateNewRoom",
    "authKey": "foo",
    "eventData": {
        "numberOfPlayers": 2,
        "boardConfig": {
            "type": "hex",
            "numberOfRows": 8,
            "numberOfColumns": 8
        } 
    }
}
JSON;
        $channel->onMessage($conn, $requestData);
        $this->assertCount(1, $roomRepository);

        $this->assertSame(1, $otherConnSendSpy->getInvocationCount());
        $newRoomMsg = $this->getFirstInvocationFirstArgument($otherConnSendSpy);
        $expectedNewRoomMsg = <<<JSON
{
    "eventType": "NewRoomCreated",
    "eventData": {
        "numberOfPlayers": 2,
        "boardConfig": {
            "type": "hex",
            "numberOfRows": 8,
            "numberOfColumns": 8
        },
        "players": [
            {
                "user": {
                    "id": 1,
                    "login": "foo"
                }, 
                "color": "#FFFFFF"
            }
        ]
    }
}
JSON;
        $this->assertJsonStringEqualsJsonString($expectedNewRoomMsg, $newRoomMsg);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|AuthClientInterface
     */
    private function buildAuthClientMock(UserInterface $user = null): \PHPUnit_Framework_MockObject_MockObject
    {
        $authClient = $this->createMock(AuthClientInterface::class);
        $authClient->method('isAuthKeyValid')->willReturn(true);
        if(empty($user)) {
            $user = $this->createMock(UserInterface::class);
            $user->method('getId')->willReturn(1);
            $user->method('getLogin')->willReturn('foo');
        }
        $authClient->method('getUserByAuthKey')->willReturn($user);
        return $authClient;
    }
}