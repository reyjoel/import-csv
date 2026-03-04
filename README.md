Customer Import & API System

A Laravel-based ETL and API application that:

Imports customer data from a CSV file

Stores data efficiently using batched upserts

Provides a paginated REST API

Includes a simple asynchronous web interface

Overview

This project demonstrates:

CSV streaming using SplFileObject

Defensive ETL design

Batched database upserts for performance

Input validation

RESTful API design

Asynchronous frontend data loading

The focus is on performance, maintainability, and clean architecture.

Architecture

CSV File
↓
Artisan Command (import:customers)
↓
Batch Upsert (500 rows per batch)
↓
MySQL Database
↓
REST API (/api/customers)
↓
Async Web UI (Fetch API)

Tech Stack

Laravel

PHP 8+

MySQL

Vanilla JavaScript (Fetch API)

Setup Instructions
1. Clone Repository
git clone <your-repository-url>
cd customer-app
2. Install Dependencies
composer install
3. Environment Setup
cp .env.example .env
php artisan key:generate

Update your database credentials in .env.

4. Run Migrations
php artisan migrate
5. Import CSV Data

Place your CSV file here:

data/customers.csv

Expected CSV header:

id,first_name,last_name,email,gender,ip_address,company,city,title,website

Then run:

php artisan import:customers
6. Start the Server
php artisan serve

Open in browser:

http://localhost:8000/customers
API Endpoint
GET /api/customers
Query Parameters
Parameter	Type	Description
search	string	Filter by name or email
per_page	integer	Items per page (max 100)
page	integer	Pagination page
Example
GET /api/customers?search=Laura&per_page=10
Sample Response
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
Performance Considerations

Uses SplFileObject for memory-efficient streaming

Uses upsert() for batched database operations

Batch size: 500 rows

Wrapped in a transaction for atomicity

Skips malformed rows

Normalizes email input

Designed to handle large CSV datasets efficiently.

Security Considerations

Input validation on API requests

Unique constraint on email

Controlled pagination limits

No raw SQL queries

Mass assignment protection via $fillable

Testing

Run tests with:

php artisan test

Future improvements:

Feature tests for CSV import

API endpoint tests

Import validation tests