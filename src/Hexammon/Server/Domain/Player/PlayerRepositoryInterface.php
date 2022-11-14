<?php

namespace Hexammon\Server\Domain\Player;

use Hexammon\HexoNards\Game\PlayerInterface;
use Hexammon\Server\Domain\Player\NamedPlayer;

interface PlayerRepositoryInterface
{
    public function addPlayer(NamedPlayer $player): void;

    public function isPlayerNameUsed(string $playerName): bool;

    public function getPlayerByName($playerName): PlayerInterface;
}
