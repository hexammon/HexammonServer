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
        $isGameStarted = $room->isFilled();
        $playersIncluded = array_map(function (NamedPlayer $player) {
            return [
                'type'       => 'players',
                'id'         => $player->getId(),
                'attributes' => [
                    'name' => $player->getName(),
                ],
            ];
        }, $room->getPlayers());

        $document = [
            'data' => $this->mapResource($room),
            'included' => $playersIncluded,
        ];

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

            $document['included'][] = [
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

        return $document;
    }

    public function mapCollection(array $rooms): array
    {
        return [
            'data' => array_map([$this, 'mapResource'], $rooms)
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

}
