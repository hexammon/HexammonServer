<?php
declare(strict_types=1);


namespace Hexammon\Server\Infrastructure\Wamp;

use Thruway\ClientSession;
use Thruway\Peer\ClientInterface;

class SessionProvider
{

    private ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function getClientSession(): ClientSession
    {
        return $this->client->getSession();
    }
}
