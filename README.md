# BackendApp API

BackendApp is a Laravel 10 API for authentication and product management. It uses Laravel Sanctum for bearer-token authentication and L5 Swagger for interactive API documentation.

The root URL also includes a small BackendApp API landing page with links to the API documentation and a summary of the available capabilities.

## Technology Stack

- PHP 8.1+
- Laravel 10
- Laravel Sanctum 3
- MySQL by default, with SQLite, PostgreSQL, and SQL Server configuration options
- L5 Swagger / OpenAPI
- PHPUnit 10
- Vite for the optional frontend asset pipeline

## Requirements

Install the following before starting:

- PHP 8.1 or newer with the required Laravel extensions
- Composer
- MySQL or another supported database
- Git
- Laragon, Apache, Nginx, or another PHP-compatible local server

Node.js and npm are only required if you change or build the Vite-managed assets. The current root landing page is self-contained and does not require a frontend build to render.

## Installation

### 1. Install PHP dependencies

```bash
composer install
```

### 2. Create the environment file

Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Linux or macOS:

```bash
cp .env.example .env
```

Never commit `.env`. It can contain the application key, database credentials, and other environment-specific values.

### 3. Generate the application key

```bash
php artisan key:generate
```

### 4. Configure the database

Update `.env` with the database used by your local environment. Example:

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=backendapp
DB_USERNAME=root
DB_PASSWORD=
```

For a deployed environment, use a unique database account with only the required permissions. Do not copy local credentials into production.

### 5. Run migrations

```bash
php artisan migrate
```

To create the development sample users and products as well:

```bash
php artisan migrate --seed
```

The seeders are intended for local or test environments. They create sample accounts with known passwords, so do not run them against a production database unless those credentials are replaced and the resulting accounts are secured.

### 6. Start the application

```bash
php artisan serve
```

The application is then available at:

```text
http://127.0.0.1:8000
```

The root page is the BackendApp landing page:

```text
http://127.0.0.1:8000/
```

## API Documentation

Swagger UI:

```text
http://127.0.0.1:8000/api/documentation
```

If the generated documentation is stale, regenerate it with:

```bash
php artisan l5-swagger:generate
```

Review the documentation access policy before exposing Swagger in production. The current configuration does not add an application authentication middleware to the documentation routes.

## Authentication

Protected API routes use Laravel Sanctum bearer tokens.

### Login

Request:

```http
POST /api/login
Content-Type: application/json
```

Body:

```json
{
  "email": "user1@example.com",
  "password": "password1"
}
```

Example:

```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user1@example.com","password":"password1"}'
```

Successful responses contain a token and a user object:

```json
{
  "message": "Login successful",
  "token": "1|replace-with-the-issued-token",
  "user": {
    "id": 1,
    "name": "User One",
    "email": "user1@example.com"
  }
}
```

Do not log, commit, or share the issued token. Send it on protected requests as:

```http
Authorization: Bearer <issued-token>
```

Example:

```bash
curl http://127.0.0.1:8000/api/products \
  -H "Authorization: Bearer <issued-token>"
```

## API Routes

### Public

| Method | Path | Purpose |
| --- | --- | --- |
| `POST` | `/api/login` | Validate credentials and issue a Sanctum token |

### Protected: products

All product routes require `Authorization: Bearer <token>`.

| Method | Path | Purpose |
| --- | --- | --- |
| `GET` | `/api/products` | List products |
| `POST` | `/api/products` | Create a product |
| `GET` | `/api/products/{product}` | Retrieve one product |
| `PUT/PATCH` | `/api/products/{product}` | Update a product |
| `DELETE` | `/api/products/{product}` | Delete a product |

Product creation accepts `name`, `price`, and optional `description`. Product updates support partial updates.

### Protected: users

| Method | Path | Purpose |
| --- | --- | --- |
| `GET` | `/api/user` | List users |
| `POST` | `/api/user` | Create a user |
| `GET` | `/api/user/{user}` | Retrieve one user |
| `PUT/PATCH` | `/api/user/{user}` | Update a user |
| `DELETE` | `/api/user/{user}` | Delete a user |

The current implementation protects these routes with authentication but does not define a separate admin role or policy layer. Review that authorization model before using user CRUD in a multi-user production system.

## Development Commands

Run the test suite:

```bash
php artisan test
```

Check PHP formatting without changing files:

```bash
vendor/bin/pint --test
```

Apply the configured PHP formatter:

```bash
vendor/bin/pint
```

Inspect registered routes:

```bash
php artisan route:list
```

Build Vite assets when needed:

```bash
npm install
npm run build
```

## Testing

The current test suite covers:

- Root page availability
- Login and token issuance
- Unauthorized product access
- Authenticated product create, read, update, and delete operations

Tests use the configured testing environment. Confirm the test database settings before running the suite in a shared environment.

## Security Notes

- Keep `.env` and all issued Sanctum tokens private.
- Set `APP_DEBUG=false` outside local development.
- Use a strong, unique `APP_KEY` in each deployed environment.
- Do not use the seeded sample passwords in production.
- Restrict Swagger documentation if API metadata must not be public.
- Review the user CRUD authorization model before production use.
- Use HTTPS for deployed API traffic.
- Do not pass API credentials in query strings.
- Rotate credentials if they are exposed in logs, screenshots, commits, or chat messages.

## Troubleshooting

### Login returns `Invalid credentials`

1. Confirm that the database is running and configured in `.env`.
2. Run `php artisan migrate --seed` in a local development database.
3. Confirm the request contains valid `email` and `password` fields.
4. Check that the request is sent as JSON.

### The root page does not render

1. Confirm Composer dependencies are installed.
2. Confirm `APP_KEY` exists in `.env`.
3. Clear cached configuration and views:

```bash
php artisan optimize:clear
```

### Swagger does not show the current endpoints

Regenerate the specification and refresh the browser:

```bash
php artisan l5-swagger:generate
```

## Project Structure

```text
app/
  Http/Controllers/Api/  API controllers
  Http/Middleware/       HTTP middleware
  Models/                Eloquent models
config/                  Application and package configuration
database/
  migrations/            Database schema migrations
  seeders/               Local sample data
resources/views/          Root Blade landing page
routes/api.php            API routes
routes/web.php            Root web route
storage/api-docs/         Generated Swagger documentation
tests/                    PHPUnit tests
```

## License

This project follows the license declared in `composer.json` and the licenses of its dependencies.
