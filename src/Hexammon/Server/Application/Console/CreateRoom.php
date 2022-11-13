<?php
declare(strict_types=1);


namespace Hexammon\Server\Application\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Thruway\ClientSession;
use Thruway\Peer\Client;
use Thruway\Transport\PawlTransportProvider;

class CreateRoom extends Command
{

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $client = new Client(WAMP_REALM);
        $client->addTransportProvider(new PawlTransportProvider("ws://wamp-router:9090/"));

        $client->on('open', function (ClientSession $session) use ($client) {
            // 4) call a remote procedure
            $session->call('net.hexammon.create_room')->then(
                function ($res) use ($session, $client) {
                    var_dump($res);
                    echo "Result: {$res}\n";
                    exit(0);
                },
                function ($error) {
                    echo "Call Error: {$error}\n";
                }
            );
            return 0;
        });

        $client->start();

        return 0;
    }
}
