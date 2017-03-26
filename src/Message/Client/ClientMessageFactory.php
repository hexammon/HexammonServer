<?php

namespace FreeElephants\HexammonServer\Message\Client;

use FreeElephants\HexammonServer\Auth\AuthClientInterface;
use FreeElephants\HexammonServer\Channel\Exception\InvalidAuthKeyException;
use FreeElephants\HexammonServer\Channel\Exception\InvalidMessageException;
use FreeElephants\HexammonServer\Channel\Exception\UnsupportedMessageTypeException;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class ClientMessageFactory
{

    private $messageTypesMap = [
        'CreateNewRoom' => CreateNewRoom::class,
        'RoomsListRequest' => RoomsListRequest::class,
    ];
    /**
     * @var AuthClientInterface
     */
    private $authClient;

    public function __construct(AuthClientInterface $authClient)
    {
        $this->authClient = $authClient;
    }

    public function createFromJson(string $json): AbstractClientMessage
    {
        $message = json_decode($json, JSON_OBJECT_AS_ARRAY);
        if (!$message) {
            throw new InvalidMessageException('Message is not a valid json string. ');
        }
        if (false === array_key_exists('authKey', $message)) {
            throw new InvalidMessageException('Required field `authKey` is missing. ');
        }
        if (false === $this->authClient->isAuthKeyValid($message['authKey'])) {
            throw new InvalidAuthKeyException();
        }
        if (false === array_key_exists('eventType', $message)) {
            throw new InvalidMessageException('Required field `eventType` is missing. ');
        }
        if (false === array_key_exists('eventData', $message)) {
            throw new InvalidMessageException('Required field `eventData` is missing. ');
        }
        if (false === array_key_exists($message['eventType'], $this->messageTypesMap)) {
            $msg = sprintf('Client message with type `%s` not supported. ', $message['eventType']);
            throw new UnsupportedMessageTypeException($msg);
        }

        $eventClassName = $this->messageTypesMap[$message['eventType']];
        /** @var AbstractClientMessage $clientEventMessage */
        $clientEventMessage = new $eventClassName($message['eventData']);
        $clientEventMessage->setUser($this->authClient->getUserByAuthKey($message['authKey']));

        return $clientEventMessage;
    }
}