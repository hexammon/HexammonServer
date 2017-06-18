# Web-socket server

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

- [/rooms](./rooms.md)
- [/games](./games.md)