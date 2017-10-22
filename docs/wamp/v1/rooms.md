# `/rooms`

This topic provide events about new games.   

## Client messages: 

### CreateNewRoom

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

### RoomsListRequest

```json
{
    "eventType": "RoomsListRequest",
    "eventData": {
        // reserved for filtering, allow be empty in current version
    }
}
```

### JoinToRoom

For join to some room, client need open connection to its channel, e.g. `/wss/v1/rooms/<roomId>`. 

## Server messages:

### RoomsListResponse

```json
{
    "eventType": "RoomsListResponse",
    "eventData": {
        "rooms": "<Rooms>[]"
    }
}
```

### PlayerJoinedToRoom

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

### RoomFilled (game is begin)

```json
{
    "eventType": "RoomFilled",
    "eventData": {
        "gameChannel": "/wss/v1/games/<gameId>" // channel of new game, connect to its channel (see below)
    }
}
```
 
### NewRoomCreated
 
```json
{
    "eventType": "NewRoomCreated",
    "eventData": "<Room>"
}
```
