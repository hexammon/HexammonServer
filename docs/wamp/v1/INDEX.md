# WAMP Router

Server side implement WAMP (ver.2) Router. 

URI for connection with WAMP-router is `/wamp/v1`. All resources begin with `net.hexammon.`. In this documentation for message examples using standart codes notation [see Message Codes and Direction in RFC for details](http://wamp-proto.org/static/rfc/draft-oberstet-hybi-crossbar-wamp.html#rfc.section.6.5).
 
## Common Architecture  
 
WAMP API provide two roles: 
 
- Player (client from web-frontend: subscriber and callee)
- Game Server (client from backend, publisher and caller)

Every player action it is call of some RPC. Every call is delivered to Game Server. After authorization checking and processing with business-login, Game Server send result to callee-player and publish triggered events with board updates to all subscribed clients. 
     
For example: 
1. Player do attack enemy in game it's RPC `net.hexammon.attack_enemy`.
2. Router check authentication and if it's ok, invoke Game Server. 
3. Game server check authorization (players moving order and army places), and if it's correct, update board state. Events about all updated tiles are published.
4. All subscribed players (who watch this game board) update it view.  

Router provide next topics and RPC's:

## RPC and Channels by Workflow 

### Get List of Open Rooms

### Request (RPC)
`net.hexammon.rooms`

#### Response

```json
[
    {
        "uuid": "<uuid>",
        "boardConfig": "<Board>",
        "players": "<Player>[]"
    }
]
```

### Join to the Room

`net.hexammon.rooms.join, [<uuid>]`

#### Response

On success
```json
{
    "gameTopic": "net.hexammon.games.<gameUuid>"
}
```

On failure
```json
{
    "error": {
        "code": "<int>",
        "reason": "Message with description. "
    }
}
```
