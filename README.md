<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## About This Application

- This project is the backend application for the full stack app.
- It requires the frontend application to display the user interface.
- It uses Laravel Sanctum for API authentication.
- It uses Swagger for API documentation and testing.
- It is built with PHP 8.1 and Laravel 10.

## Project Purpose

This backend application provides API endpoints for:

- user authentication and token generation
- protected product CRUD operations
- Swagger documentation for testing endpoints

## Prerequisites

Before starting, make sure your machine has:

- PHP 8.1 or newer
- Composer
- MySQL or another supported database
- Apache / Nginx / Laragon / XAMPP
- Git

## Full Installation Guide

### 1. Clone the Repository

Open your terminal and run:

```bash
git clone https://github.com/irwansyah1998/BackendApp.git
```

Then go into the project folder:

```bash
cd BackendApp
```

### 2. Install Composer Dependencies

Run:

```bash
composer install
```

If Composer warns about platform requirements, make sure your PHP version matches the project requirement.

### 3. Create the Environment File

Copy the example environment file:

```bash
copy .env.example .env
```

On Linux or macOS:

```bash
cp .env.example .env
```

### 4. Generate Application Key

Generate the Laravel app key:

```bash
php artisan key:generate
```

### 5. Configure the Database

Open the `.env` file and set your database configuration.

Example for MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=backendapp
DB_USERNAME=root
DB_PASSWORD=
```

If you are using Laragon, usually the DB host is `127.0.0.1` and the username/password are set based on your local environment.

### 6. Run Migrations and Seeders

Create the database tables and seed the default login users:

```bash
php artisan migrate --seed
```

This will create the default users for testing, including:

- `user1@example.com` / `password1`
- `user2@example.com` / `password2`
- `user3@example.com` / `password3`

### 7. Start the Application

Run the app locally:

```bash
php artisan serve
```

The backend will be available at:

```text
http://127.0.0.1:8000
```

### 8. Access Swagger API Documentation

Open the Swagger UI in your browser:

```text
http://127.0.0.1:8000/api/documentation
```

From there you can test the endpoints directly in the browser.

## Login and Token Flow

This backend uses Sanctum token authentication.

### Step 1: Login to Get a Token

Send a POST request to:

```text
/api/login
```

Example JSON body:

```json
{
  "email": "user1@example.com",
  "password": "password1"
}
```

Example using curl:

```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user1@example.com","password":"password1"}'
```

The response will include a token like this:

```json
{
  "message": "Login successful",
  "token": "1|abcdefghijklmnopqrstuvwxyz",
  "user": {
    "id": 1,
    "name": "User One",
    "email": "user1@example.com"
  }
}
```

### Step 2: Use the Token for Protected Routes

For protected endpoints, send the token in the Authorization header:

```http
Authorization: Bearer <your_token>
```

Example:

```bash
curl -X GET http://127.0.0.1:8000/api/products \
  -H "Authorization: Bearer <your_token>"
```

## API Routes

### Public route

- `POST /api/login`

### Protected routes

- `GET /api/products`
- `POST /api/products`
- `GET /api/products/{id}`
- `PUT /api/products/{id}`
- `DELETE /api/products/{id}`
- `GET /api/user`
- `POST /api/user`
- `GET /api/user/{id}`
- `PUT /api/user/{id}`
- `DELETE /api/user/{id}`

## Troubleshooting

### If login returns Invalid credentials

Check the following:

1. Confirm the database was migrated and seeded:

```bash
php artisan migrate --seed
```

2. Confirm the user exists in the database.

3. Confirm the login request uses JSON and includes `email` and `password`.

### If Swagger does not load

Run:

```bash
php artisan l5-swagger:generate
```

Then refresh the Swagger page.

## Conclusion

This project is a Laravel backend API with token-based authentication and Swagger documentation. After installing dependencies, setting up the database, and running migrations, you can log in, receive a bearer token, and use it to access protected API endpoints.