<?php
declare(strict_types=1);


namespace Hexammon\Server\Domain\Player;

use Hexammon\HexoNards\Game\PlayerInterface;

class NamedPlayer implements PlayerInterface
{
    private string $name;
    private $id;

    public function __construct(string $name, $id)
    {
        $this->name = $name;
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
