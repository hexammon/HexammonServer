# Hexammon Game Server

Current draft implementation. 

## Wamp

### WAMP Procedures

#### Player registration

URI: `nex.hexammon.register_player`

Args: `[playerName]`

Result: `[playerId]`

#### Create Room

URI: `net.hexammon.create_room`

Args: `[roomName, playerName]`

Result: `roomTopic`

#### Get All Rooms

URI: `net.hexammon.get_rooms`

Args: none.

RESULT: `[<roomsCollection>]`

#### Get Single Room

URI: `net.hexammon.get_room`

Args: `[roomId]`

Result: `[<roomDocument>]`

### WAMP Topics

#### All Rooms Updates

URI: `net.hexammon.rooms'

Args: ...TBD...

#### Separate Room Updates

URI: `net.hexammon.rooms.<roomId>'

Args: ...TBD...

