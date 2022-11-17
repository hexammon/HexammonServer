<?php
declare(strict_types=1);


namespace Hexammon\Server\Domain\Player;

use Hexammon\HexoNards\Board\Board;
use Hexammon\HexoNards\Board\BoardBuilder;
use Hexammon\HexoNards\Game\Game;
use Hexammon\HexoNards\Game\PlayerInterface;
use Hexammon\Server\Domain\Player\Exception\GameNotStarted;
use Ramsey\Uuid\UuidInterface;

class Room
{
    private string $name;
    private int $numberOfPlayers;
    private PlayerInterface $creator;
    private array $players = [];
    private UuidInterface $uuid;
    private \DateTimeInterface $createdAt;
    private Game $game;

    public function __construct(UuidInterface $uuid, string $name, NamedPlayer $creator, int $numberOfPlayers = 2)
    {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->numberOfPlayers = $numberOfPlayers;
        $this->creator = $creator;
        $this->players[] = $creator;
        $this->createdAt = new \DateTime();
    }

    public function joinPlayer(PlayerInterface $player)
    {
        $this->players[] = $player;
    }

    public function isFilled(): bool
    {
        return count($this->players) === $this->numberOfPlayers;
    }

    public function getId(): UuidInterface
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCreator(): NamedPlayer
    {
        return $this->creator;
    }

    public function getNumberOfPlayers(): int
    {
        return $this->numberOfPlayers;
    }

    public function getNumberOfJoinedPlayers(): int
    {
        return count($this->players);
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getPlayers(): array
    {
        return $this->players;
    }

    public function initGame(): Game
    {
        $board = (new BoardBuilder())->build(Board::TYPE_SQUARE, 4, 4);
        $this->game = new Game($this->getPlayers(), $board);

        return $this->game;
    }

    public function getGame(): Game
    {
        if (isset($this->game)) {
            return $this->game;
        }

        throw new GameNotStarted();
    }
}
