<?php
declare(strict_types=1);

namespace Hexammon\Server\Infrastructure\Mapper;

use Hexammon\HexoNards\Board\AbstractTile;
use Hexammon\Server\Domain\Player\NamedPlayer;
use Hexammon\Server\Domain\Player\Room;

class RoomMapper
{
    public function mapSingle(Room $room): array
    {
        $includedPlayers = $this->getPlayersIncluded($room);
        $includedGames = $this->getIncludedGames($room);
        $included = array_merge($includedPlayers, $includedGames);

        return [
            'data'     => $this->mapResource($room),
            'included' => $included,
        ];
    }

    public function mapCollection(array $rooms): array
    {
        $includedPlayers = array_map([$this, 'getPlayersIncluded'], $rooms);
        $includedGames = array_map([$this, 'getIncludedGames'], $rooms);
        $included = array_merge($includedPlayers, $includedGames);
        return [
            'data'     => array_map([$this, 'mapResource'], $rooms),
            'included' => $included,
        ];
    }

    private function mapResource(Room $room): array
    {
        $isGameStarted = $room->isFilled();
        $playersRelationships = array_map(function (NamedPlayer $player) {
            return [
                'type' => 'players',
                'id'   => $player->getId(),
            ];
        }, $room->getPlayers());

        $resource = [
            'type'          => 'rooms',
            'id'            => $room->getId(),
            'attributes'    => [
                'name'                  => $room->getName(),
                'createdBy'             => $room->getCreator()->getId(),
                'isFilled'              => $room->isFilled(),
                'numberOfPlayers'       => $room->getNumberOfPlayers(),
                'numberOfJoinedPlayers' => $room->getNumberOfJoinedPlayers(),
                'createdAt'             => $room->getCreatedAt()->format(\DateTimeInterface::ATOM),
            ],
            'relationships' => [
                'players' => [
                    'data' => [
                        $playersRelationships,
                    ],
                ],
            ],
        ];

        if ($isGameStarted) {
            $resource['relationships']['game'] = [
                'data' => [
                    'type' => 'games',
                ],
            ];
        }

        return $resource;
    }

    /**
     * @param Room $room
     * @return array|array[]
     */
    private function getPlayersIncluded(Room $room): array
    {
        $playersIncluded = array_map(function (NamedPlayer $player) {
            return [
                'type'       => 'players',
                'id'         => $player->getId(),
                'attributes' => [
                    'name' => $player->getName(),
                ],
            ];
        }, $room->getPlayers());
        return $playersIncluded;
    }

    private function getIncludedGames(Room $room): array
    {
        $includedGame = [];
        $isGameStarted = $room->isFilled();
        if ($isGameStarted) {

            $game = $room->getGame();

            $board = $game->getBoard();
            $rows = [];
            foreach ($board->getRows() as $row) {
                $rows[$row->getNumber()] = array_map(function (AbstractTile $tile) {
                    return [
                        'hasCastle' => $tile->hasCastle(),
                        'army'      => $tile->hasArmy() ? [
                            'units'    => $tile->getArmy()->count(),
                            'playerId' => $tile->getArmy()->getOwner()->getId(),
                        ] : null,
                    ];
                }, $row->getTiles());
            }

            $columns = [];
            foreach ($board->getColumns() as $column) {
                $columns[$column->getNumber()] = array_map(function (AbstractTile $tile) {
                    return [
                        'hasCastle' => $tile->hasCastle(),
                        'army'      => $tile->hasArmy() ? [
                            'units'    => $tile->getArmy()->count(),
                            'playerId' => $tile->getArmy()->getOwner()->getId(),
                        ] : null,
                    ];
                }, $column->getTiles());
            }

            $includedGame = [
                'type'       => 'games',
                'attributes' => [
                    'board' => [
                        'type' => $board->getType(),
                        'rows' => $rows,
                        'cols' => $columns,
                    ],
                ],
            ];
        }

        return $includedGame;

    }

}
