<?php

namespace FreeElephants\HexoNardsGameServer\Model\Room;

use FreeElephants\HexoNards\Board\Board;
use FreeElephants\HexoNards\Game\Player;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class Room
{
    private $numberOfPlayers;

    private $board;

    private $players;
    /**
     * @var Player
     */
    private $owner;

    public function __construct(int $numberOfPlayers, Board $board, Player $owner)
    {
        $this->numberOfPlayers = $numberOfPlayers;
        $this->board = $board;
        $this->owner = $owner;
        $this->players[] = $owner;
    }

    public function getNumberOfPlayers(): int
    {
        return $this->numberOfPlayers;
    }

    public function getBoard(): Board
    {
        return $this->board;
    }

    public function getPlayers(): array
    {
        return $this->players;
    }
}