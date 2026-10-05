# Laravel TODO

A small TODO manager built with Laravel. It supports creating, editing, completing,
and deleting tasks.

## Requirements

- PHP 8.3+
- Composer

## Setup

```sh
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan serve
```

Open the URL printed by `artisan serve`. Run the feature tests with
`vendor/bin/phpunit`.
