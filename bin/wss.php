<?php

use Ratchet\App;
use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServerInterface;
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
        $conn->send('welcome');
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        foreach ($this->clients as $client) {
            if ($from != $client) {
                $client->send($msg);
            }
        }
        $client->send('yep');
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }
}

class WebPage implements HttpServerInterface
{

    /**
     * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
     * @param  ConnectionInterface $conn The socket/connection that is closing/closed
     * @throws \Exception
     */
    function onClose(ConnectionInterface $conn)
    {
        // TODO: Implement onClose() method.
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
        // TODO: Implement onError() method.
    }

    /**
     * @param \Ratchet\ConnectionInterface $conn
     * @param \Guzzle\Http\Message\RequestInterface $request null is default because PHP won't let me overload; don't pass null!!!
     * @throws \UnexpectedValueException if a RequestInterface is not passed
     */
    public function onOpen(ConnectionInterface $conn, \Guzzle\Http\Message\RequestInterface $request = null)
    {
        $conn->send('hello');
        $conn->close();
    }

    /**
     * Triggered when a client sends data through the socket
     * @param  \Ratchet\ConnectionInterface $from The socket/connection that sent the message to your application
     * @param  string $msg The message received
     * @throws \Exception
     */
    function onMessage(ConnectionInterface $from, $msg)
    {
        // TODO: Implement onMessage() method.
    }
}


$app = new App('192.168.1.42', 8080, '192.168.1.42');
$app->route('/chat', new MyChat($app));
$app->route('/', new WebPage($app), ['*']);
//$app->route('/echo', new EchoServer, array('*'));
$app->run();