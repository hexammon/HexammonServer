# Hexo-Nards Game Server
 
Game Server implementation consists of 2 parts: 
- Web-socket server for game events and players interaction: useful in all event-driven cases.
- REST API for receiving data (like statistic) and authorization: useful in data-driven cases and request-response logic. 

## Common Object Types used in both API's

Scalar values and complex objects present in current documentation in "<angle brackets>". E.g.:
- `<int>`: integer value
- `<string>`: string value
- `<User>`: User object.
- etc... 

### <User>
```json
{
    "id": "<int>",
    "login": "<string>"
}
```

### <Player>
Player - it's User in game context. On User will be present as new Player in every games. Order in lists of Players corresponds with move order in game.    
```json
{
    "user": "<User>",
    "color": "#FFFFFF", // HTML RGB color of player pieces in game 
}
```

### <Room>
Room it's Game before start, when Users can join it as Players. Room close when all players assembled. 
  
```json
{
    "id": "<roomId>",
    "channel": "/wss/v1/rooms/<roomId>",
    "numberOfPlayers": 2,
    "boardConfig": {
        "type": "<hex|square>",
        "numberOfRows": 8,
        "numberOfColumns": 8
    },
    "players": "<Player>[]"    // list of connected players
}
```

## Web-socket server

All messages based on player actions or game related events. Messages are JSON. Common format for client messages is:

```json
{
    "authKey": "<string>", 
    "eventType": "<string>",
    "eventData": {
        // fields with event data
    }
}
```

Common format for server messages is:
```json
{
    "user": "<User>|null", // null if event does not triggered by user, e.g. from game server 
    "eventType": "<string>",
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
            "type": "(hex|square)",
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
        // reserved for filtering, allow be empty in current version
    }
}
```

##### JoinToRoom

For join to some room, client need open connection to its channel, e.g. `/wss/v1/rooms/<roomId>`. 

#### Server messages:

##### RoomsListResponse

```json
{
    "eventType": "RoomsListResponse",
    "eventData": {
        "rooms": "<Rooms>[]"
    }
}
```

##### PlayerJoinedToRoom

```json
{
    "eventType": "PlayerJoinedToRoom",
    "eventData": {
        "id": "<roomId>",
        "channel": "/wss/v1/rooms/<roomId>",
        "player": "<Player>"
    }
}
```

##### RoomFilled (game is begin)

```json
{
    "eventType": "RoomFilled",
    "eventData": {
        "gameChannel": "/wss/v1/games/<gameId>" // channel of new game, connect to its channel (see below)
    }
}
```
 
##### NewRoomCreated
 
```json
{
    "eventType": "NewRoomCreated",
    "eventData": "<Room>"
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
        "activePlayer": "<Player>"
    }
}
```

### REST API (auth service and statistic data)

All REST routes prefixed with `/api/v1`. REST API provide next routes: 

#### Auth Resource
##### Create authKey with user credentials

Request: 
```
POST: /api/v1/auth
{
    "login": <string>,
    "password": <string>
}
```
Response contains `Location` header with new generated authKey uri and it's content in body. 

#### Get user by authKey
Request: 
`GET: /api/v1/auth/<authKey>`
Response: 
```
{
    "authKey": <authKey>,
    "user": <User>
}
```
Use HEAD method for check that authKey is valid. 

### User Resource
#### Create new user (registration)
Request:
```
POST /api/v1/users
{
    "login": <string>,
    "email": <string>,
    "password": <string>,
    "invite": <string>
}
```
Error cases (TODO write test for this cases):
- 400 some field not present (all is required).
- 422 login or email already used.  

#### Get Users Collection

Request: `GET /api/v1/users`
Service Authorization is required. 
Response: 
```
{
    "items": <User>[]
}
```

## Get User by ID
Request: `GET /api/v1/users/{userId}`
Service Authorization is required. 
Response: 
```
{
    "id": <userId>,
    "login": <string>
}
```