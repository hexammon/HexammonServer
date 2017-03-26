<?php

namespace FreeElephants\HexammonServer\Message\Client;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class CreateNewRoom extends AbstractClientMessage
{
    private $numberOfPlayers;
    private $boardConfig;

    public function __construct(array $eventData)
    {
        $this->numberOfPlayers = $eventData['numberOfPlayers'];
        $boardConfig = $eventData['boardConfig'];
        $this->boardConfig = [
            'type' => $boardConfig['type'],
            'numberOfRows' => $boardConfig['numberOfRows'],
            'numberOfColumns' => $boardConfig['numberOfColumns'],
        ];
    }

    public function getNumberOfPlayers(): int
    {
        return $this->numberOfPlayers;
    }

    public function getBoardConfig(): array
    {
        return $this->boardConfig;
    }
}