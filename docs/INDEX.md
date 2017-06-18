# Hexammon Server Documentation

Game Server implementation consists of 2 parts: 
- [Web-socket server](./wss/v1/INDEX.md) for game events and players interaction: useful in all event-driven cases.
- [REST API](./api/v1/INDEX.md) for receiving data (like statistic) and authorization: useful in data-driven cases and request-response logic. 

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

Player - it's User in game context. One User will be present as new Player in every games. Order in lists of Players corresponds with move order in game.    
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
