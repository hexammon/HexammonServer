<?php

use Ratchet\App;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Ratchet\Server\EchoServer;

require __DIR__ . '/../vendor/autoload.php';

class MyChat implements MessageComponentInterface {
    protected $clients;
    /**
     * @var App
     */
    private $app;

    public function __construct(App $app) {
        $this->clients = new \SplObjectStorage;
        $this->app = $app;
    }

    public function onOpen(ConnectionInterface $conn) {
//        $this->app->route('/game/1', new EchoServer(), ['*']);
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        foreach ($this->clients as $client) {
            if ($from != $client) {
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }
}


$app = new App('192.168.1.42', 8080, '192.168.1.42');
$app->route('/chat', new MyChat($app));
//$app->route('/echo', new EchoServer, array('*'));
$app->run();