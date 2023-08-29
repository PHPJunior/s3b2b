<p align="center">
<a href="#" target="_blank">
<img src="art/logo.svg" width="100" alt="Laravel Logo">
</a>
</p>


## About S3B2B

S2B2B is a simple S3 file manager. It can manage multiple file storage at the same time and can also easily move files from one S3 bucket to another S3 bucket.

## Requirements

- PHP >= 8.1

## Installation

Before installing, make sure you have [Composer](https://getcomposer.org/) and [Yarn](https://yarnpkg.com/) installed.
And, you need to install [Soketi](https://docs.soketi.app/) to run the web socket server.

```bash
composer install
yarn install && yarn dev
cp .env.example .env

php artisan key:generate
php artisan migrate

soketi start
```

or you can use [Laravel Sail](https://laravel.com/docs/8.x/sail) to run the project.

```bash
./vendor/bin/sail up -d
```

## Screenshots
