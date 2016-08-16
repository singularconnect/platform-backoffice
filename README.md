# SingularBet Backoffice
SingularBet Backoffice is a web application...

## Installation
You can download SingularBet Backoffice either through GitHub.

### Via GitHub
```
$ git clone https://github.com/singularconnect/singularbet-backoffice.git
```

### Recommended Servers
We recommend that you use the following servers to run SingularBet Backoffice.

- [nginx web server](https://www.nginx.com/)
- [RethinkDB database for the realtime web](https://www.rethinkdb.com/)
- [Redis server](http://redis.io/)

### Create a Database
Make sure you also update your `.env` file with your database credentials and other configuration options.

### Configuration
You can simply run `php artisan backoffice:install` to run migrations, configure your folders, and enter API keys. This command will also perform an initial.

### Jobs
...

### Schedule
...

### Commands
...

## Testing
``` bash
$ phpunit
```