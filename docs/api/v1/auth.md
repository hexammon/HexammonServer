# Auth Resource

## Create authKey with user credentials

Request: 
```
POST: /api/v1/auth
{
    "login": <string>,
    "password": <string>
}
```
Response contains `Location` header with new generated authKey uri and it's content in body. 

## Get user by authKey
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
