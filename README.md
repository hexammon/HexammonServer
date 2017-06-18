# Hexo-Nards Game Server

## Requirements

docker with docker-compose. 

## Installation

```bash
git clone git@github.com:FreeElephants/HexammonServer.git
cd HexammonServer
./tools/composer.sh install
./tools/prepare-local-config.sh 
# modify your *.env files with desired credentials, or leave it's default
docker-compose -f docker-compose.yml -f docker-compose-<YOUR_ENV>.yml up -d
# where <YOUR_ENV> one of: dev|test|prod
```

## Integration and documentation

Default port for connection to server is 9000. 

See [documentation](./docs/INDEX.md) about [RESTful API resources](./docs/api/v1/INDEX.md) and [Web-socket channels](./docs/wss/v1/INDEX.md).  

## Testing

```bash
./tools/prepare-local-config.sh
docker-compose -f docker-compose.yml -f docker-compose-test.yml up -d
docker exec server vendor/bin/codecept run
```
If you get next error on run codecept:
```bash
  [Codeception\Exception\ModuleException]
  Db: SQLSTATE[HY000] [2002] Connection refused while creating PDO connection
```
Don't worry, it's ok - mysql server get long time on startup. Wait for it, and rerun tests. 
