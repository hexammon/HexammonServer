<?php

namespace FreeElephants\HexoNardsGameServer\Message;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
abstract class AbstractEventMessage
{

    public function getEventType(): string
    {
        $names = explode('\\', static::class);
        return array_pop($names);
    }
}