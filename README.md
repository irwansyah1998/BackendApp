<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## About This Application
- This application is a back-end application and requires the [FrontendApp](https://github.com/irwansyah1998/FrontendApp) for the user interface.
- This application uses Swagger for API documentation.
- It is built with PHP 8.1 and Laravel version 10.

## Installation Guide

Follow these steps to set up the application:

### Prerequisites

- PHP 8.1 or higher
- Composer
- Laravel 10
- A web server (e.g., Apache, Nginx)

### Steps to Install

1. **Clone the Repository**

Open your terminal and run the following command to clone the repository:

```
git clone https://github.com/irwansyah1998/BackendApp.git
```

Replace your-username and your-repo-name with your GitHub username and the repository name.

2. **Navigate to the Project Directory**

Change your directory to the cloned repository:

```
cd BackendApp
```

3. **Install Dependencies**

Run the following command to install the required PHP packages using Composer:

```
composer install
```

4. **Set Up Environment File**

Create a copy of the `.env.example` file and rename it to `.env`:

```
cp .env.example .env
```

5. **Generate Application Key**

Generate a new application key by running:

```
php artisan key:generate
```

6. **Configure Database**

Open the `.env` file and update the database configuration as follows:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```
Replace `your_database_name`, `your_database_user`, and `your_database_password` with your actual database credentials.

7. **Run Migrations**

Run the following command to create the database tables:

```
php artisan migrate
```

8. **Run the Application**

Start the development server with the following command:

```
php artisan serve
```

The application will be accessible at `http://localhost:8000`.

### Accessing Swagger Documentation

Once your application is running, you can access the Swagger documentation at:

```
http://localhost:8000/api/documentation
```

### Conclusion

You have successfully set up the application. For further details on usage, refer to the API documentation or the FrontendApp repository.