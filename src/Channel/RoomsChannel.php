<?php
namespace FreeElephants\HexoNardsGameServer\Channel;

use FreeElephants\HexoNardsGameServer\Message\RoomsListResponse;
use FreeElephants\HexoNardsGameServer\Model\Room\Room;
use FreeElephants\HexoNardsGameServer\Model\Room\RoomRepository;
use Ratchet\App;
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

    public function __construct(RoomRepository $roomRepository)
    {
        $this->clients = new \SplObjectStorage;
        $this->roomRepository = $roomRepository;
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
        $roomsList = $this->roomRepository->getRooms();
        $response = new RoomsListResponse($roomsList);
        $from->send($response->toString());
    }
}