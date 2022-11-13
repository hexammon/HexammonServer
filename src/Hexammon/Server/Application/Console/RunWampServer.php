<?php
declare(strict_types=1);


namespace Hexammon\Server\Application\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Thruway\ClientSession;
use Thruway\Peer\Client;
use Thruway\Transport\PawlTransportProvider;

class RunWampServer extends Command
{

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $client = new Client(WAMP_REALM);
        $client->addTransportProvider(new PawlTransportProvider("ws://wamp-router:9090/"));

        $client->on('open', function (ClientSession $session) use ($client) {
            $createRoom = function ($args) {
                var_dump($args);
            };
            $session->register('net.hexammon.create_room', $createRoom);
        });

        $client->start();

        return 0;

    }
}
