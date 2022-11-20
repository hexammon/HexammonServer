<?php
declare(strict_types=1);


namespace Hexammon\Server\Application\Console;

use Hexammon\Server\Application\Wamp\UseCase\CreateRoom;
use Hexammon\Server\Application\Wamp\UseCase\FetchRooms;
use Hexammon\Server\Application\Wamp\UseCase\GetRoom;
use Hexammon\Server\Application\Wamp\UseCase\JoinRoom;
use Hexammon\Server\Application\Wamp\UseCase\RegisterUser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Thruway\ClientSession;
use Thruway\Peer\ClientInterface;

class RunWampServer extends Command
{

    private CreateRoom $createRoom;
    private RegisterUser $registerUser;
    private ClientInterface $client;
    private FetchRooms $fetchRooms;
    private GetRoom $getRoom;
    private JoinRoom $joinRoom;

    public function __construct(
        RegisterUser    $registerUser,
        CreateRoom      $createRoom,
        FetchRooms      $fetchRooms,
        ClientInterface $client,
        GetRoom         $getRoom,
        JoinRoom        $joinRoom
    )
    {
        $this->createRoom = $createRoom;
        $this->registerUser = $registerUser;
        $this->fetchRooms = $fetchRooms;
        $this->client = $client;
        $this->getRoom = $getRoom;
        $this->joinRoom = $joinRoom;
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->client->on('open', function (ClientSession $session) {
            $session->register('nex.hexammon.register_player', $this->registerUser);

            $session->register('net.hexammon.create_room', $this->createRoom);

            $session->register('net.hexammon.get_rooms', $this->fetchRooms);

            $session->register('net.hexammon.get_room', $this->getRoom);

            $session->register('net.hexammon.join_room', $this->joinRoom);
        });

        $this->client->start();

        return 0;
    }
}
