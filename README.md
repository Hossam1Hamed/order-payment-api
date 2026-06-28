# Order & Payment Management API

## Overview

A Laravel RESTful API for managing orders and payments with JWT authentication.
The payment system is designed using the Strategy Pattern, making it easy to add new payment gateways with minimal code changes.

---

## Features

- JWT Authentication
- User Registration & Login
- Order CRUD
- Order Items
- Payment Processing
- Multiple Payment Gateways
- RESTful APIs
- Validation
- Pagination

---

## Requirements

- PHP 8.3+
- Composer
- MySQL
- Laravel 12

---

## Installation

```bash
git clone https://github.com/Hossam1Hamed/order-payment-api.git

cd order-payment-api

composer install

cp .env.example .env

php artisan key:generate

php artisan jwt:secret
```

Update your `.env` database configuration.

```bash
php artisan migrate --seed
```

Start the server

```bash
php artisan serve
```

---

## Authentication

Register

```
POST /api/register
```

Login

```
POST /api/login
```

Use the returned JWT token:

```
Authorization: Bearer YOUR_TOKEN
```

---

## API Documentation

Import the included Postman collection: 
https://documenter.getpostman.com/view/19191090/2sBXwyGStA

---

## Payment Gateway Extensibility

The payment system follows the Strategy Pattern.

Current gateways:

- Credit Card
- PayPal
- Stripe

To add a new gateway:

1. Create a class implementing `PaymentGatewayInterface`
2. Implement the `pay()` method
3. Register the gateway in `PaymentGatewayFactory`
4. Add gateway configuration to `.env` or `config/payment.php`

No controller or service changes are required.

## Author

Hossam Hamed