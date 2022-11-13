<?php
declare(strict_types=1);


namespace Hexammon\Server\Application\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Thruway\Peer\Router;
use Thruway\Transport\RatchetTransportProvider;

class RunRouter extends Command
{
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $router = new Router();

        $transportProvider = new RatchetTransportProvider('0.0.0.0', 9090);

        $router->addTransportProvider($transportProvider);

        $router->start();
    }
}
