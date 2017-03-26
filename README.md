# Hexo-Nards Game Server
 
Game Server implementation consists of 2 parts: 
- Web-socket server for game events and players interaction: useful in all event-driven cases.
- REST API for receiving data (like statistic) and authorization: useful in data-driven cases and request-response logic. 

## Web-socket server

All messages based on player actions or game related events. Messages are JSON. Common format for client messages is:

```json
{
    "authKey": "<authKey>", 
    "eventType": "<eventName>",
    "eventData": {
        // fields with event data
    }
}
```

Common format for server messages is:
```json
{
    "initiator": "<player>", 
    "eventType": "<eventName>",
    "eventData": {
        // fields with event data
    }
}
```

### `/auth`

Channel for authentication related actions. 

#### Client events

```json
{
    "initiator": "<player>", 
    "eventType": "<eventName>",
    "eventData": {
        // fields with event data
    }
}
```



All wss-routes prefixed with `/wss/v1`. Server provide next routes:

### `/rooms`

This channel provide events about new games.   

#### Client messages: 

##### CreateNewRoom

```json
{
    "eventType": "CreateNewRoom",
    "eventData": {
        "numberOfPlayers": 2,
        "boardConfig": {
            "type": "<hex|square>",
            "numberOfRows": 8,
            "numberOfColumns": 8
        } 
    }
}
```

##### RoomsListRequest

```json
{
    "eventType": "RoomsListRequest",
    "eventData": {
        // reserved for filtering
    }
}
```

##### JoinToRoom


#### Server messages:

##### RoomsListResponse

```json
{
    "eventType": "RoomsListResponse",
    "eventData": {
        "rooms": [
            {
                "numberOfPlayers": 2,
                 "boardConfig": {
                     "type": "<hex|square>",
                     "numberOfRows": 8,
                     "numberOfColumns": 8
                 },
                "players": [    // list of connected players
                    {
                        "id": "<playerId>",
                        "login": "<playerLogin>",
                        "color": "#FFFFFF"
                    }
                ]
            }
        ]
    }
}
```


##### PlayerJoinedToRoom

```json
{
    "eventType": "PlayerJoinedToRoom",
    "eventData": {
        "roomId": "<roomId>",
        "player": {
            "id": "<playerId>",
            "login": "<playerLogin",
            "color": "#FFFFFF" // RGB color of player
        }
    }
}
```

##### RoomFilled (game is begin)

```json
{
    "eventType": "RoomFilled",
    "eventData": {
        "gameId": "<gameId>" // id of new game, connect to its channel (see below)
    }
}
```
 
##### NewRoomCreated
 
```json
{
    "eventType": "NewRoomCreated",
    "eventData": {
        "numberOfPlayers": 2,
        "boardConfig": {
            "type": "<hex|square>",
            "numberOfRows": 8,
            "numberOfColumns": 8
        },
        "players": [    // list of connected players
            {
                "id": "<playerId>",
                "login": "<playerLogin>",
                "color": "#FFFFFF"
            }
        ]
    }
}
```
 
### `/game/<gameId>`

This channel provide all events in game, like player actions. 

#### Client messages: 

##### AssaultCastle

```json
{
    "eventType": "AssaultCastle",
    "eventData": {
        // TBD
    }
}
```


##### BuildCastle

```json
{
    "eventType": "BuildCastle",
    "eventData": {
        "tileCoordinate": "<tileCoordinate>" // coordinate of tile in "1-1" format, where first number is row, and second is column, started from 1.   
    }
}
```


##### MergeArmy

```json
{
    "eventType": "MergeArmy",
    "eventData": {
        // TBD
    }
}
```

##### MoveArmy

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

##### ReplenishGarrison

```json
{
    "eventType": "ReplenishGarrison",
    "eventData": {
        "tileCoordinate": "<tileCoordinate>"
    }
}
```

##### TakeOffEnemyGarrison

```json
{
    "eventType": "TakeOffEnemyGarrison",
    "eventData": {
        "tileCoordinate": "<tileCoordinate>"
    }
}
```
##### AttackEnemy

```json
{
    "eventType": "AttackEnemy",
    "eventData": {
        "assaulterCoordinate": "<tileCoordinate>",
        "attackedCoordinate": "<tileCoordinate>",
    }
}
```

#### Server messages:
Server re-send all client messages to every player in game as is. Client must update board state with action from other players. 

##### SwitchActivePlayer
Send when previous player make all moves, and active player switched.  
```json
{
    "eventType": "SwitchActivePlayer",
    "eventData": {
        "activePlayerId": "<playerId>"
    }
}
```

### REST API (auth service and statistic data)

All REST routes prefixed with `/api/v1`. REST API provide next routes: 

...TBD...