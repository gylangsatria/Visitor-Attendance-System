# VAS - Visitor & Attendance System

VAS is a web-based application for visitor management and attendance tracking in organizations. Built with Laravel 10, it helps businesses monitor user activity, manage visitor records, and track attendance in one integrated system.

## Features

- Visitor Management - Register, check-in, and check-out visitors with detailed information
- Attendance Tracking - Record and monitor employee attendance in real-time
- User and Role Management - Multi-level access control (Admin, Editor, Staff, Attendee)
- Authentication and Access Control - Secure login with role-based permissions
- Dashboard Overview - Real-time statistics and recent activities
- Responsive Design - Mobile-friendly interface with Tailwind CSS
- Export to CSV - Export visitor data for reporting

## Access Levels

| Level | Role     | Permissions                                               |
| ----- | -------- | --------------------------------------------------------- |
| 1     | Admin    | Full access to all features (users, visitors, attendance) |
| 2     | Editor   | Manage visitors and attendance, limited user management   |
| 3     | Staff    | View-only access to assigned data, cannot create visitors |
| 4     | Attendee | Limited access, attendance only, cannot manage visitors   |

## Tech Stack

- Backend: Laravel 10 (PHP 8.2)
- Web Server: Nginx Alpine
- Database: MySQL 8.0
- Cache and Queue: Redis
- Container: Docker and Docker Compose
- Database UI: phpMyAdmin
- Frontend: Blade Templates, Tailwind CSS, Font Awesome

## System Architecture

VAS uses a multi-container Docker setup with five services:

- **app** - PHP 8.2-FPM container running the Laravel application
- **web** - Nginx Alpine web server (port 8080)
- **db** - MySQL 8.0 database server (port 3306)
- **redis** - Redis for caching and session management
- **phpmyadmin** - Database management UI (port 8081)

## Installation

### Prerequisites

- Docker and Docker Compose
- Git

### Project Structure

```
visitor-attendance-system/
  src/                    Laravel application source code
    app/                  Application core (Controllers, Models, Middleware)
    config/               Configuration files
    database/             Migrations and seeders
    resources/            Views (Blade templates)
    routes/               Web and API routes
    storage/              Logs, cache, sessions
  nginx/
    default.conf          Nginx server configuration
  php.ini                 PHP configuration overrides
  Dockerfile              PHP-FPM container definition
  docker-compose.yml      Docker services orchestration
  README.md               Project documentation
```

### Quick Start

1. Clone the repository:

```bash
git clone https://github.com/gylangsatria/Visitor-Attendance-System.git
cd Visitor-Attendance-System
```

2. Build and run containers:

```bash
docker compose build --no-cache
cp .env.example src/.env
docker compose up -d
```

3. Install PHP dependencies:

```bash
docker exec -it vas-app composer install
```

4. Configure environment:

```bash
docker exec -it vas-app php artisan key:generate
```

5. Run database migrations and seeders:

```bash
docker exec -it vas-app php artisan migrate
docker exec -it vas-app php artisan db:seed
```

6. Create storage link (for avatar uploads):

```bash
docker exec -it vas-app php artisan storage:link
```

7. Set permissions (if needed):

```bash
docker exec -it vas-app chmod -R 775 storage bootstrap/cache
```

## Access URLs and Credentials

| Service     | URL                   | Credentials                                          |
| ----------- | --------------------- | ---------------------------------------------------- |
| Application | http://localhost:8080 | See default user accounts below                      |
| phpMyAdmin  | http://localhost:8081 | Server: db, Username: vas_user, Password: dbpassword |

### Default User Accounts

| Role     | Access Level | Email          | Password    |
| -------- | ------------ | -------------- | ----------- |
| Admin    | Level 1      | admin@vas.com  | password123 |
| Editor   | Level 2      | editor@vas.com | password123 |
| Staff    | Level 3      | viewer@vas.com | password123 |
| Attendee | Level 4      | guest@vas.com  | password123 |

### Database Credentials

| Parameter | Value                 |
| --------- | --------------------- |
| Host      | db (inside container) |
| Port      | 3306                  |
| Database  | vas_db                |
| Username  | vas_user              |
| Password  | dbpassword            |

## Troubleshooting

### Permission Issues

If you encounter file permission errors, run these commands inside the container:

```bash
docker exec -it vas-app bash
```

Then inside the container:

```bash
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/logs
mkdir -p bootstrap/cache

chmod -R 775 storage
chmod -R 775 bootstrap/cache

rm -rf bootstrap/cache/*.php
rm -rf storage/framework/views/*.php
rm -rf storage/framework/cache/*.php

php artisan config:cache
php artisan view:cache
php artisan route:cache

php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Container Not Starting

Check container logs:

```bash
docker logs vas-app
docker logs vas-db
docker logs vas-nginx
```

## Code Review Findings

The following improvements were identified during a comprehensive code review. They are grouped by priority.

### Bugs (High Priority)

1. **Duplicate visitor route declaration** - In `routes/web.php`, `Route::resource('/visitors', VisitorController::class)` is declared twice (lines 22 and 27). This may cause route conflicts or errors. Remove the duplicate declaration.

2. **Visitor status filter mismatch** - In `resources/views/visitors/index.blade.php`, the status filter option uses the value `checked_out`, but the `VisitorController@checkOut` method sets the status to `completed`. The filter will never match any records. Change the filter option value to `completed` or update the controller to use `checked_out` consistently.

### Security

3. **CORS overly permissive** - `config/cors.php` allows all origins (`*`). For production, restrict this to specific domains that need access.

4. **Debug mode enabled in production** - `.env` has `APP_DEBUG=true` and `LOG_LEVEL=debug`, which can leak sensitive information. Set `APP_DEBUG=false` and `LOG_LEVEL=warning` for production.

5. **Weak default passwords** - All seeder accounts use `password123`. Use stronger passwords or generate random credentials for production.

6. **Database and Redis ports exposed to host** - In `docker-compose.yml`, MySQL (3306) and Redis (6379) ports are mapped to the host. Remove these port mappings for production environments to reduce attack surface.

### Code Quality and Architecture

7. **Excessive file permissions** - The `Dockerfile` uses `chmod -R 777` for several directories. Use `775` or `755` instead; 777 is overly permissive and a security risk.

8. **Unnecessary packages in Dockerfile** - The Dockerfile installs `nginx`, `nodejs`, `npm`, and `libpq-dev` (PostgreSQL), none of which are needed since Nginx runs in a separate container.

9. **Missing queue worker** - The application is configured to use Redis for the queue (`QUEUE_CONNECTION=redis`), but there is no process running `php artisan queue:work`. Add a queue worker service or supervisor configuration.

10. **Raw PHP echo in Blade template** - `resources/views/users/edit.blade.php` uses raw `echo '<div>...'` statements for error messages instead of Blade syntax. Use `@php` / `@endphp` or Blade directives for consistency.

11. **Redundant access checks in VisitorController** - Methods `create()` and `store()` manually check `access_level === 4` even though the constructor middleware `access:1,2,3` already handles this for those methods.

12. **Incomplete CSV export** - The `export()` method in `VisitorController` writes to `php://output` but does not set proper HTTP response headers, so the download will not work correctly.

13. **No soft deletes** - The User and Visitor models do not use Laravel's `SoftDeletes` trait. When a user is deleted, related attendance and visitor records are cascade-deleted, causing data loss.

### User Experience

14. **Profile editing restricted** - `ProfileController` only allows levels 1 and 2 to edit profiles. Users at levels 3 (Staff) and 4 (Attendee) cannot update their own profile information.

15. **Missing autocomplete attributes** - The login form does not have `autocomplete` attributes on email and password inputs, which may hinder password manager usage.

16. **Inconsistent language** - The UI mixes Indonesian ("Absensi Hari Ini") and English ("Register Visitor"). Choose one language and apply it consistently across all views.

### Docker and Deployment

17. **No health checks** - Services in `docker-compose.yml` lack `healthcheck` configurations. The app container may attempt to connect to the database before it is ready.

18. **Nginx lacks optimization** - `nginx/default.conf` is minimal. Add gzip compression, security headers (X-Frame-Options, X-Content-Type-Options), and cache control for static assets.

19. **No task scheduler** - There is no cron job configured to run `php artisan schedule:run`. Any scheduled tasks will not execute.

20. **Missing .env.example** - The file `.env.example` is referenced in the quick start instructions but does not exist in the `src/` directory.

## What Works Well

The following aspects of the project are already implemented well:

- Clean Laravel 10 project structure following framework conventions
- Effective role-based access control via the `AccessLevel` middleware
- Redis integration for caching and session management
- Dashboard with real-time attendance and visitor statistics
- Search and filter functionality on all index pages
- Responsive mobile layout using Tailwind CSS
- Pagination with preserved query string parameters
- Docker-based multi-container architecture for easy deployment
- Avatar upload with public storage disk
- CSRF protection on all web routes
