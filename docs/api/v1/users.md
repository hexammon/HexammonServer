# User Resource

## Create new user (registration)
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

## Get Users Collection

Request: `GET /api/v1/users`
Service Authorization is required. 
Response: 
```json
{
    "_links": {
        "self": {
            "href": "https://hexammon.net/api/v1/users"
        }
    },
    "_embedded": {
        "users": <User>[]
    }
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
