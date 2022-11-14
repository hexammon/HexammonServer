<?php
declare(strict_types=1);

namespace Hexammon\Server\Infrastructure\Repository;

use Hexammon\HexoNards\Game\PlayerInterface;
use Hexammon\Server\Domain\Player\NamedPlayer;
use Hexammon\Server\Domain\Player\Exception\PlayerNameAlreadyTaken;
use Hexammon\Server\Domain\Player\Exception\UnknownPlayerName;
use Hexammon\Server\Domain\Player\PlayerRepositoryInterface;

class PlayerRepository implements PlayerRepositoryInterface
{
    private array $players = [];
    private array $playerNamesMap = [];

    public function addPlayer(NamedPlayer $player): void
    {
        if ($this->isPlayerNameUsed($player->getName())) {
            throw new PlayerNameAlreadyTaken($player->getName());
        }

        $this->players[$player->getId()] = $player;
        $this->playerNamesMap[$player->getName()] = $player->getId();
    }

    public function isPlayerNameUsed(string $playerName): bool
    {
        return array_key_exists($playerName, $this->playerNamesMap);
    }

    public function getPlayerByName($playerName): PlayerInterface
    {
        if ($this->isPlayerNameUsed($playerName)) {
            return $this->players[$this->getPlayerIdByName($playerName)];
        }

        throw new UnknownPlayerName($playerName);
    }

    private function getPlayerIdByName($playerName)
    {
        return $this->playerNamesMap[$playerName];
    }
}
