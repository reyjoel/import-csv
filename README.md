# Customer Import & API System

![Laravel](https://img.shields.io/badge/Laravel-10-red)
![PHP](https://img.shields.io/badge/PHP-8%2B-blue)
![License](https://img.shields.io/badge/license-MIT-green)

A Laravel-based ETL and API application that imports customer data from
CSV files and exposes it through a paginated REST API with a simple
asynchronous frontend.

------------------------------------------------------------------------

## Features

-   CSV customer data import
-   Memory-efficient file streaming using `SplFileObject`
-   Batched database upserts for performance
-   REST API with pagination and search
-   Simple async frontend using Fetch API
-   Defensive data validation
-   Clean and maintainable Laravel structure

------------------------------------------------------------------------

## Architecture Overview

    CSV File
       │
       ▼
    Artisan Command (import:customers)
       │
       ▼
    Batch Processing (500 rows)
       │
       ▼
    MySQL Database
       │
       ▼
    REST API (/api/customers)
       │
       ▼
    Async Web UI

------------------------------------------------------------------------

## Tech Stack

  Technology   Purpose
  ------------ ---------------------
  Laravel      Backend framework
  PHP 8+       Application runtime
  MySQL        Database
  JavaScript   Async frontend
  Fetch API    Client data loading

------------------------------------------------------------------------

## Project Structure

    app/
     ├── Console/Commands
     │    └── ImportCustomers.php
     ├── Http/Controllers
     │    └── Api/CustomerController.php
     ├── Models
     │    └── Customer.php

    database/
     └── migrations

    resources/
     └── views/customers.blade.php

    data/
     └── customers.csv

------------------------------------------------------------------------

## Installation

### Clone the repository

``` bash
git clone https://github.com/your-username/customer-import
cd customer-import
```

### Install dependencies

``` bash
composer install
```

### Setup environment

``` bash
cp .env.example .env
php artisan key:generate
```

Update database credentials in `.env`.

### Run migrations

``` bash
php artisan migrate
```

------------------------------------------------------------------------

## Import Customers

Place the CSV file inside:

    data/customers.csv

Expected CSV format:

    id,first_name,last_name,email,gender,ip_address,company,city,title,website

Run the import command:

``` bash
php artisan import:customers
```

------------------------------------------------------------------------

## Start the Application

``` bash
php artisan serve
```

Open in browser:

    http://localhost:8000/customers

------------------------------------------------------------------------

## API Documentation

### Endpoint

    GET /api/customers

### Query Parameters

  Parameter   Description
  ----------- ----------------------------
  search      Search by name or email
  page        Pagination page
  per_page    Results per page (max 100)

### Example Request

    GET /api/customers?search=Laura&per_page=10

### Example Response

``` json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "first_name": "Laura",
      "last_name": "Richards",
      "email": "lrichards0@reverbnation.com",
      "company": "Meezzy"
    }
  ],
  "last_page": 5,
  "per_page": 10,
  "total": 50
}
```

------------------------------------------------------------------------

## Performance Considerations

The import process was designed to handle large CSV files efficiently.

Key optimizations:

-   Streaming CSV reading with `SplFileObject`
-   Batch upserts (500 rows per query)
-   Transaction wrapping for atomic operations
-   Skipping malformed rows
-   Email normalization

------------------------------------------------------------------------

## Security Considerations

-   Input validation for API queries
-   Pagination limits to prevent abuse
-   Mass assignment protection using `$fillable`
-   Unique email constraint
-   No raw SQL queries

------------------------------------------------------------------------

## Testing

Run tests using:

``` bash
php artisan test
```

Future improvements:

-   CSV import feature tests
-   API endpoint tests
-   Validation tests

------------------------------------------------------------------------

## Future Improvements

-   Queue-based background imports
-   Import progress tracking
-   Failed row logging
-   API authentication (Laravel Sanctum)
-   Docker support
-   Caching layer (Redis)
-   Advanced filtering and sorting

------------------------------------------------------------------------

## License

MIT
