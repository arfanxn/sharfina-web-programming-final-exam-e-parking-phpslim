# Fanparking (PHP Slim)

Fanparking is an E-Parking application built with PHP, PHP Slim 3, Javascript, and Tailwind CSS.

## Directory structure

```
.
├── logs // logging
├── public // publicly accessible files
│   └── assets
│       ├── css // CSS files
│       ├── images // Images
│       └── js // JavaScript files
├── src // the root of the source project
│   ├── Controllers // Controllers: classes that handle requests and responses
│   ├── Exceptions // Exceptions: classes that handle errors
│   ├── Forms // Forms: classes that validate and handle conversion from json to php object
│   │   └── User
│   ├── Handlers // Handlers
│   ├── Helpers // Helpers: classes that store generally used functions
│   ├── Interfaces // Interfaces
│   ├── Middlewares // Middlewares: classes that being the middlemen of the requests
│   ├── Models // Models: classes that used for row modeling from database
│   ├── Repositories // Repositories: classes that communicate with the database, it's like DAO (Data access object)
│   ├── Resources // Resources: classes that used for response modeling from the model class
│   ├── Services // Services: classes that communicate controllers with repositories
│   └── Traits // Traits
├── templates // templates: it contains views like html or phtml and so on
│   └── users
└── tests // testing
    └── Functional

```

## Installation

Install all composer dependencies

```sh
composer install --ignore-platform-reqs // to ignore the platform requirements
// or
composer install
```

Create environtment file from example.env file

```sh
cp example.env .env
```

Migrate database;
You have to migrate the database manually; the sql migration file is located at ./migration.sql and if necessary, you can seed the database with the sql seeder file that is located at ./seeder.sql

```sh
cat ./migration.sql | pbcopy //  migration file
cat ./seeder.sql | pbcopy // seeder file
```

## Configuration

Configure the app settings

```sh
code ./src/settings.php
// or
vim ./src/settings.php
```

Configure the app dependencies

```sh
code ./src/dependencies.php
// or
vim ./src/dependencies.php
```

Configure the app environment

```sh
code .env
// or
vim .env
```

## Running

Start and run the server with this following command

```
 php -S localhost:8000 -t public
```

## Credentials

Login with these following credentials

```
 // Admin credentials
 email: admin@fanparking.com
 password: password

 // Employee credentials
 email: ozey@gmail.com
 password: password

```
