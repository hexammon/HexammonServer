# `/games/<gameId>`

This channel provide all events in game, like player actions. 

## Client messages: 

### AssaultCastle

```json
{
    "eventType": "AssaultCastle",
    "eventData": {
        // TBD
    }
}
```


### BuildCastle

```json
{
    "eventType": "BuildCastle",
    "eventData": {
        "tileCoordinate": "<tileCoordinate>" // coordinate of tile in "1-1" format, where first number is row, and second is column, started from 1.   
    }
}
```


### MergeArmy

```json
{
    "eventType": "MergeArmy",
    "eventData": {
        // TBD
    }
}
```

### MoveArmy

```json
{
    "eventType": "MoveArmy",
    "eventData": {
        "sourceTileCoordinate": "<tileCoordinate>",
        "targetTileCoordinate": "<tileCoordinate>",
        "units": 10 // number of units for movement
    }
}
```

### ReplenishGarrison

```json
{
    "eventType": "ReplenishGarrison",
    "eventData": {
        "tileCoordinate": "<tileCoordinate>"
    }
}
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
