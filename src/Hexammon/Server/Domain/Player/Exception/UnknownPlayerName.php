<?php
declare(strict_types=1);

namespace Hexammon\Server\Domain\Player\Exception;

class UnknownPlayerName extends \DomainException
{
    public function __construct(string $playerName)
    {
        $message = sprintf('Player with name `%s` not found', $playerName);
        parent::__construct($message, 0, null);
    }
}
