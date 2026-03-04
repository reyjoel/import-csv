# Customer Import & API System

![Laravel](https://img.shields.io/badge/Laravel-10-red)
![PHP](https://img.shields.io/badge/PHP-8%2B-blue)

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
     │    └── CustomerController.php
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
git https://github.com/reyjoel/import-csv.git
cd import-csv
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
php artisan app:import-customers
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

    GET       /api/customers
    POST      /api/customers
    GET       /api/customers/{id}
    PUT       /api/customers/{id}
    DELETE    /api/customers/{id}

### Query Parameters for GET

  Parameter       Description
  -----------     ----------------------------
  search          Search by name or email
  page            Pagination page
  per_page        Results per page (min 1 and max 100)

### Example Request for GET

    GET /api/customers?search=Laura&page=1&per_page=10

### Example Response for GET

``` json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "first_name": "Laura",
      "last_name": "Richards",
      "email": "lrichards0@reverbnation.com",
      "gender": "Male",
      "ip_address": "73.114.57.213",
      "company": "Meezzy",
      "city": "Brody",
      "title": "Research Associate",
      "website": "https://rediff.com/sit/amet/diam.aspx?non=donec&sodales=ut",
      "created_at": "2026-03-04T01:03:51.000000Z",
      "updated_at": "2026-03-04T01:03:51.000000Z"
    }
  ],
  "last_page": 5,
  "per_page": 10,
  "total": 50
}
```

### Example Request for POST
    POST /api/customers

  ``` json
  {
    "id": 1, // optional since it is auto increment
    "first_name": "Laura",
    "last_name": "Richards",
    "email": "lrichards0@reverbnation.com",
    "gender": "Male",
    "ip_address": "73.114.57.213",
    "company": "Meezzy",
    "city": "Brody",
    "title": "Research Associate",
    "website": "https://rediff.com/sit/amet/diam.aspx?non=donec&sodales=ut",
  }
```

### Example Response for POST

``` json
{
	"message": "Customer created",
	"data": {
      "id": 1,
      "first_name": "Laura",
      "last_name": "Richards",
      "email": "lrichards0@reverbnation.com",
      "gender": "Male",
      "ip_address": "73.114.57.213",
      "company": "Meezzy",
      "city": "Brody",
      "title": "Research Associate",
      "website": "https://rediff.com/sit/amet/diam.aspx?non=donec&sodales=ut",
    }
}
```

### Query Parameters for PUT

  Parameter   Description
  ----------- ----------------------------
  id          Customer ID

### Example Request for PUT

    PUT /api/customers/1

``` json
{
  "id": 1,
  "first_name": "Laura Jane",
  "last_name": "Richards",
  "email": "lrichards0@reverbnation.com",
  "gender": "Male",
  "ip_address": "73.114.57.213",
  "company": "Meezzy",
  "city": "Brody",
  "title": "Research Associate",
  "website": "https://rediff.com/sit/amet/diam.aspx?non=donec&sodales=ut",
}
```

### Example Response for PUT

``` json
{
  "first_name": "Laura Jane",
  "last_name": "Richards",
  "email": "lrichards0@reverbnation.com",
  "gender": "Male",
  "ip_address": "73.114.57.213",
  "company": "Meezzy",
  "city": "Brody",
  "title": "Research Associate",
  "website": "https://rediff.com/sit/amet/diam.aspx?non=donec&sodales=ut",
}
```

### Query Parameters for DELETE

  Parameter   Description
  ----------- ----------------------------
  id          Customer ID

### Example Request for DELETE

    DELETE /api/customers/1

### Example Response for DELETE

``` json
{
  "message": "Customer deleted successfully"
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
------------------------------------------------------------------------
