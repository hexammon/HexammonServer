<?php
declare(strict_types=1);


namespace Hexammon\Server\Domain\Player\Exception;

use Hexammon\HexoNards\Exception\DomainException;

class PlayerNameAlreadyTaken extends DomainException
{
    public function __construct(string $playerName)
    {
        $message = sprintf('Player name `%s` already taken', $playerName);
        parent::__construct($message);
    }
}
