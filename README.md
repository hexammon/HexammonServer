# Hexo-Nards Game Server
 
Game Server implementation is web-socket server. 
All messages based on player actions or game related events. Messages are JSON. Common format is:

```json
{
    "eventType": "<eventName>",
    "eventData": {
        // fields with event data
    }
}
```

Server provide next routes:

## `/rooms`

This channel provide events about new games.   

### Client messages: 
#### Init new room.

```json
{
    "eventType": "InitNewRoom",
    "eventData": {
        "numberOfPlayers": 2, 
        "boardType": "<hex|square>",
        "boardSize": 8, // e.g. 8x8
    }
}
```

## `/rooms/<roomId>`
 
### Server messages:

#### Player joined to room

```json
{
    "eventType": "PlayerJoinedToRoom",
    "eventData": {
        "playerId": "<playerId>"
    }
}
```
 

#### Room is filled (game is begin)

```json
{
    "eventType": "RoomIsFilled",
    "eventData": {
        "gameId": "<gameId>" // id of new game
    }
}
```
 
## `/game/<gameId>`

This channel provide all events in game, like player actions. 

### Client messages: 

#### AssaultCastle

```json
{
    "eventType": "AssaultCastle",
    "eventData": {
        // TBD
    }
}
```


#### BaseCastle

```json
{
    "eventType": "BaseCastle",
    "eventData": {
        "tileCoordinate": "<tileCoordinate>" // coordinate of tile in "1-1" format, where first number is row, and second is column, started from 1.   
    }
}
```


#### MergeArmy

```json
{
    "eventType": "MergeArmy",
    "eventData": {
        // TBD
    }
}
```

#### MoveArmy

```json
{
    "eventType": "MoveArmy",
    "eventData": {
        // TBD
    }
}
```

#### ReplenishArmy

```json
{
    "eventType": "ReplenishArmy",
    "eventData": {
        "tileCoordinate": "<tileCoordinate>"
    }
}
```

#### TakeOffEnemyGarrison

```json
{
    "eventType": "",
    "eventData": {
        // TBD
    }
}
```

### Server messages:
Server re-send all client messages to every player in game as is. Client must update board state with action from other players. 

#### SwitchActivePlayer
Send when previous player make all moves, and active player switched.  
```json
{
    "eventType": "SwitchActivePlayer",
    "eventData": {
        "activePlayer": "<playerId>"
    }
}
```