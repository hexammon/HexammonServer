<?php

namespace FreeElephants\HexoNardsGameServer\Model\Room;

use FreeElephants\HexoNards\Board\Board;
use FreeElephants\HexoNards\Game\PlayerInterface;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class Room
{
    private $numberOfPlayers;

    private $board;

    private $players;
    /**
     * @var PlayerInterface
     */
    private $owner;

    public function __construct(int $numberOfPlayers, Board $board, PlayerInterface $owner)
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