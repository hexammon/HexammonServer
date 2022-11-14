<?php
declare(strict_types=1);

namespace Hexammon\Server\Application\Wamp\UseCase;

use Hexammon\Server\Domain\Player\NamedPlayer;
use Hexammon\Server\Domain\Player\PlayerRepositoryInterface;
use Ramsey\Uuid\Uuid;

class RegisterUser
{

    private PlayerRepositoryInterface $playerRepository;

    public function __construct(PlayerRepositoryInterface $playerRepository)
    {
        $this->playerRepository = $playerRepository;
    }

    public function __invoke(array $args)
    {
        $playerName = $args[0];
        $playerId = Uuid::uuid4()->toString();
        $this->playerRepository->addPlayer(new NamedPlayer($playerName, $playerId));

        return $playerId;
    }
}
