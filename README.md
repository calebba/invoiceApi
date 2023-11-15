# Invoice Management API

Welcome to the Invoice Management API! This API allows you to manage invoices and associated items. Follow the steps below to set up and try out the API using Postman.

## Getting Started

### Prerequisites

1. Make sure you have [PHP](https://www.php.net/) and [Composer](https://getcomposer.org/) installed on your machine.
2. Install [Postman](https://www.postman.com/) for API testing.

### Setup

1. Clone the repository:
   ```bash
   git clone https://github.com/calebba/invoiceApi.git
   cd invoiceApi

## If you are running on windows
Add extension=php_sodium.dll to php.ini

## Install dependencies:
composer install

## Set up your environment variables by copying the .env.example file to .env:

cp .env.example .env
Update the .env file with your database configuration.

## Generate the application key:

php artisan key:generate

## Api Auth
Run php artisan passport:install
which will be used to create "personal access" and "password grant" clients which will be used to generate access client for api auth:

## Run database migrations:
php artisan migrate


## Start the Laravel development server:
php artisan serve



Testing with Postman
1. Open Postman.
2. Set up your environment variables in Postman, including the base URL (e.g., http://localhost:8000).
3. Test the API endpoints by sending requests using the provided examples.
4. Feel free to explore and test other endpoints based on your needs.

Happy testing!
 
