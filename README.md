<p align="center">
  <a href="https://symfony.com/" target="blank"><img src="https://linku.nl/app/uploads/2020/07/symfony-logo-breed.png" width="320" alt="Symfony Logo" /></a>
</p>


  <p align="center">A progressive PHP framework for building efficient and scalable server-side applications.</p>



## Description
A fullstack shop app with Symfony framework and doctrine orm using twig as template engine.

<!-- ## Requirements -->
## Installation

```bash
$ composer install
```

## Running the app

```bash
# development with symfony cli:
$ symfony server:start
```


## Migrations

<!-- # Swagger Document:
$ http://localhost:8080/swagger-ui/

# make new migration
$ php bin/console make:migration -->
```bash
# migrate last migration
$ php bin/console doctrine:migrations:migrate
```

## Test

```bash
# tests
$ php bin/phpunit
```