# Slim Swoole Kit

A modern, high-performance PHP API boilerplate combining the Slim Framework, Swoole HTTP server, and Eloquent ORM. This project is designed for rapid development of RESTful APIs with support for both SQLite and MySQL, and is fully containerized using Docker Compose.

## Features

- **Slim 4 Framework**: Lightweight, PSR-7 compliant HTTP API framework.
- **Swoole HTTP Server**: High-performance, asynchronous server for handling concurrent requests.
- **Eloquent ORM**: Elegant database access with support for SQLite and MySQL.
- **Dependency Injection**: Powered by PHP-DI.
- **Environment Management**: Uses `vlucas/phpdotenv` for environment variables.
- **RESTful User API**: CRUD endpoints for user management.
- **Dockerized**: Includes services for PHP, Swoole, MySQL, and phpMyAdmin.

## Getting Started

### Prerequisites
- Docker & Docker Compose

### Quick Start

1. **Clone the repository:**
   ```sh
   git clone <your-repo-url>
   cd slim-swoole-kit
   ```
2. **Copy and edit the environment file:**
   ```sh
   cp .env.example .env
   # Edit .env as needed (choose SQLite or MySQL)
   ```
3. **Start the services:**
   ```sh
   docker-compose up --build
   ```
4. **Access the API:**
   - Slim (PHP built-in server): http://localhost:8080
   - Swoole server: http://localhost:9501
   - phpMyAdmin: http://localhost:8081 (user: root, password: secret)

## API Endpoints

| Method | Endpoint         | Description         |
|--------|------------------|--------------------|
| GET    | /users           | List all users     |
| POST   | /users           | Create a user      |
| GET    | /users/{id}      | Get user by ID     |
| PUT    | /users/{id}      | Update user by ID  |
| DELETE | /users/{id}      | Delete user by ID  |

## Project Structure

```
├── composer.json
├── docker-compose.yml
├── swoole-server.php
├── public/
│   └── index.php
├── src/
│   ├── bootstrap.php
│   ├── Controllers/
│   ├── Models/
│   ├── Routes/
│   └── Services/
└── database/
```

- **public/index.php**: Entry for Slim (PHP built-in server)
- **swoole-server.php**: Entry for Swoole HTTP server
- **src/Controllers**: API controllers (e.g., `UserController`)
- **src/Models**: Eloquent models (e.g., `User`)
- **src/Services**: Business logic (e.g., `UserService`)
- **src/Routes/api.php**: Route definitions
- **src/bootstrap.php**: App and DI container setup

## Database Configuration

- **SQLite**: Set `DB_CONNECTION=sqlite` and `SQLITE_PATH=database/database.sqlite` in `.env`.
- **MySQL**: Set `DB_CONNECTION=mysql` and configure `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `DB_PORT`.

## License

This project is open-source and available under the MIT License.
