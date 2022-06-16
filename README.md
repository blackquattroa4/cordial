# Cordial Interview Exercise

## License

The Lumen framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Prerequisite

Assuming docker & docker-compose is already installed

## Build

In project root directory, type
```
> sudo docker-compose build --no-cache
```

## Run

Copy .env.example to .env
In project root directory, type
```
> sudo docker-compose up -d
```

## Monitor

In project root directory, type
```
> sudo docker-compose logs -f
```

## Test

In project root directory, type
```
> sudo docker-compose run cordial-interview bash
```
which should bring up a terminal within docker container, do not change directory and type
```
#>php vendor/bin/phpunit
```
