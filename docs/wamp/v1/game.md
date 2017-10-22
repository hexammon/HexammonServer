# Realm `games`

## Topic `net.hexammon.games.<gameUID>`

This topic provide all events in game, that triggered on Game Server after player actions.  

E.g. server provide next events in topic:

### Board Update

```json
[
    EVENT,
    Subscription.id,
    Publication.id,
    {
        "nextActive": {
            "player": "<Player>",
            "moves": "<int>"
        },
        "updatedTiles": [
            {
                "coordinates": "<tileCoordinate>", 
                "owner": "<Player>",
                "hasCastle": "<boolean>",
                "units": "<int>",
            },
        ]
    }
]
```

## Client Messages: 

Player actions it is RPC prefixed with `net.hexammon.games.<gameUID>`

### Assault Castle

```json
[
    CALL,
    Request.id,
    [
        "<tileCoordinate>", // coordinate of Castle
        "<tileCoordinate>" // coordinate of Army
    ],
    "net.hexammon.games.<gameUID>.assault_castle"
]
```

### Build Castle

Build new castle cost one unit. Unit must be on same tile that castle. 

```json
[
    CALL,
    Request.id,
    [
        "<tileCoordinate>" // coordinate of tile in "1-1" format, where first number is row, and second is column, started from 1.   
    ],
    "net.hexammon.games.<gameUID>.build_castle"
]
```


### Merge Armies

Armies must ne placed on adjacent tiles. 

```json
[
    CALL,
    Request.id,
    [
        "<tileCoordinate>", // first army
        "<tileCoordinate>" // second army for merging.
    ],
    "net.hexammon.games.<gameUID>.merger_armies"
]
```

#### Triggered events
 
```json
[
    EVENT,
    
]
``` 

### MoveArmy

```json
[
    CALL,
    Request.id,
    [
        "<tileCoordinate>", // source tile coordinate
        "<tileCoordinate>", // target tile coordinate
        "<units>" // integer number of units for movement
    ],
    "net.hexammon.games.<gameUID>.move_army"
]
```

### ReplenishGarrison

```json
[
    CALL,
    Request.id,
    [
        "<tileCoordinate>" // coordinate of castle for replenish 
    ],
    "net.hexammon.games.<gameUID>.replenish_garrison"
]
```

#### Result

```json
[
    result,
    Request.id,
    [
        "<units>" // new number of units 
    ],
    "net.hexammon.games.<gameUID>.replenish_garrison"
]
```

### TakeOffEnemyGarrison

```json
{
    "eventType": "TakeOffEnemyGarrison",
    "eventData": {
        "tileCoordinate": "<tileCoordinate>"
    }
}
```

### AttackEnemy

```json
{
    "eventType": "AttackEnemy",
    "eventData": {
        "assaulterCoordinate": "<tileCoordinate>",
        "attackedCoordinate": "<tileCoordinate>",
    }
}
```

## Server messages:

Server re-send all client messages to every player in game as is. Client must update board state with action from other players. 

### SwitchActivePlayer

Send when previous player make all moves, and active player switched.  

```json
{
    "eventType": "SwitchActivePlayer",
    "eventData": {
        "activePlayer": "<Player>"
    }
}
```
