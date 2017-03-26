<?php

namespace FreeElephants\HexammonServer\Message\Server;

use FreeElephants\HexammonServer\Message\AbstractEventMessage;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
abstract class AbstractServerMessage extends AbstractEventMessage
{

    public function toString(): string
    {
        return json_encode( [
            'eventType' => $this->getEventType(),
            'eventData' => $this->getEventData(),
        ]);
    }

    abstract protected function getEventData();
}