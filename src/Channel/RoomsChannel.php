<?php
namespace FreeElephants\HexammonServer\Channel;

use FreeElephants\HexammonServer\Auth\AuthClientInterface;
use FreeElephants\HexammonServer\Channel\Exception\UnsupportedMessageTypeException;
use FreeElephants\HexammonServer\Message\Client\ClientMessageFactory;
use FreeElephants\HexammonServer\Message\Client\CreateNewRoom;
use FreeElephants\HexammonServer\Message\Client\RoomsListRequest;
use FreeElephants\HexammonServer\Message\RoomsListResponse;
use FreeElephants\HexammonServer\Message\Server\NewRoomCreated;
use FreeElephants\HexammonServer\Model\Player\Player;
use FreeElephants\HexammonServer\Model\Room\Room;
use FreeElephants\HexammonServer\Model\Room\RoomRepository;
use FreeElephants\HexoNards\Board\BoardBuilder;
use FreeElephants\Phalette\RGB;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class RoomsChannel implements MessageComponentInterface
{
    protected $clients;
    /**
     * @var RoomRepository
     */
    private $roomRepository;
    /**
     * @var ClientMessageFactory
     */
    private $clientMessageFactory;
    /**
     * @var BoardBuilder
     */
    private $boardBuilder;
    /**
     * @var AuthClientInterface
     */
    private $authClient;

    public function __construct(
        RoomRepository $roomRepository,
        ClientMessageFactory $clientMessageFactory,
        BoardBuilder $boardBuilder,
        AuthClientInterface $authClient
    ) {
        $this->clients = new \SplObjectStorage;
        $this->roomRepository = $roomRepository;
        $this->clientMessageFactory = $clientMessageFactory;
        $this->boardBuilder = $boardBuilder;
        $this->authClient = $authClient;
    }

    /**
     * When a new connection is opened it will be passed to this method
     * @param  ConnectionInterface $conn The socket/connection that just connected to your application
     * @throws \Exception
     */
    function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        $conn->send('{"welcome": true}');
    }

    /**
     * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
     * @param  ConnectionInterface $conn The socket/connection that is closing/closed
     * @throws \Exception
     */
    function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
    }

    /**
     * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
     * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method
     * @param  ConnectionInterface $conn
     * @param  \Exception $e
     * @throws \Exception
     */
    function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }

    /**
     * Triggered when a client sends data through the socket
     * @param  ConnectionInterface $from The socket/connection that sent the message to your application
     * @param  string $msg The message received
     * @throws \Exception
     */
    function onMessage(ConnectionInterface $from, $msg)
    {
        $requestMessage = $this->clientMessageFactory->createFromJson($msg);
        switch (true) {
            case $requestMessage instanceof RoomsListRequest:
                $roomsList = $this->roomRepository->getRooms();
                $response = new RoomsListResponse($roomsList);
                $from->send($response->toString());
                break;

            case $requestMessage instanceof CreateNewRoom:
                $numberOfPlayers = $requestMessage->getNumberOfPlayers();
                $boardType = $requestMessage->getBoardConfig()['type'];
                $numberOfRows = $requestMessage->getBoardConfig()['numberOfRows'];
                $numberOfColumns = $requestMessage->getBoardConfig()['numberOfColumns'];
                $board = $this->boardBuilder->build($boardType, $numberOfRows, $numberOfColumns);
                $user = $requestMessage->getUser();
                $owner = new Player($user, new RGB(0xFF, 0xFF, 0xFF));
                $room = new Room($numberOfPlayers, $board, $owner);
                $this->roomRepository->addRoom($room);
                $response = new NewRoomCreated($room);
                /** @var ConnectionInterface $client */
                foreach ($this->clients as $client) {
                    $client->send($response->toString());
                }
                break;

            default:
                throw new UnsupportedMessageTypeException();
        }
    }
}