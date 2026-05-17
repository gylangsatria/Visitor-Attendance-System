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
- Multi-language Support - Toggle between English and Indonesian

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

VAS uses a multi-container Docker setup with six services:

- **app** - PHP 8.2-FPM container running the Laravel application
- **web** - Nginx Alpine web server (port 8080)
- **db** - MySQL 8.0 database server (port 3306)
- **redis** - Redis for caching and session management
- **phpmyadmin** - Database management UI (port 8081)
- **queue-worker** - Redis queue worker running `php artisan queue:work`

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
    lang/                 Language files (en.json, id.json)
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

The following improvements were identified during a comprehensive code review. They are grouped by priority. Items marked as `[FIXED]` have already been resolved.

### Bugs (High Priority)

1. `[FIXED]` **Duplicate visitor route declaration** - In `routes/web.php`, `Route::resource('/visitors', VisitorController::class)` was declared twice. The duplicate declaration has been removed.

2. `[FIXED]` **Visitor status filter mismatch** - The status filter option in `visitors/index.blade.php` used the value `checked_out`, but the controller sets status to `completed`. The filter value has been changed to `completed`.

3. `[FIXED]` **Missing DatabaseSeeder** - File `database/seeders/DatabaseSeeder.php` was missing, causing `php artisan db:seed` to fail. The file has been added and calls the `UserSeeder`.

### Security

4. **CORS overly permissive** - `config/cors.php` allows all origins (`*`). For production, restrict this to specific domains that need access.

5. **Debug mode enabled in production** - `.env` has `APP_DEBUG=true` and `LOG_LEVEL=debug`, which can leak sensitive information. Set `APP_DEBUG=false` and `LOG_LEVEL=warning` for production.

6. **Weak default passwords** - All seeder accounts use `password123`. Use stronger passwords or generate random credentials for production.

7. **Database and Redis ports exposed to host** - In `docker-compose.yml`, MySQL (3306) and Redis (6379) ports are mapped to the host. Remove these port mappings for production environments to reduce attack surface.

### Code Quality and Architecture

8. **Excessive file permissions** - The `Dockerfile` uses `chmod -R 777` for several directories. Use `775` or `755` instead; 777 is overly permissive and a security risk.

9. `[FIXED]` **Unnecessary packages in Dockerfile** - Removed `nginx`, `nodejs`, `npm`, and `libpq-dev` (PostgreSQL) from the Dockerfile since Nginx runs in a separate container and Node.js is not used for build steps.

10. `[FIXED]` **Missing queue worker** - A `queue-worker` service has been added to `docker-compose.yml` running `php artisan queue:work redis --sleep=3 --tries=3`.

11. `[FIXED]` **Raw PHP echo in Blade template** - `resources/views/users/edit.blade.php` has been refactored to use proper Blade `@if` / `@elseif` / `@else` / `@endif` directives instead of raw `echo` statements.

12. `[FIXED]` **Redundant access checks in VisitorController** - Removed duplicate `access_level === 4` checks from `create()` and `store()` methods since the constructor middleware `access:1,2,3` already handles access control for those methods.

13. `[FIXED]` **Incomplete CSV export** - The `export()` method in `VisitorController` now uses `response()->stream()` with proper CSV headers. An export button and route have been added.

14. **No soft deletes** - The User and Visitor models do not use Laravel's `SoftDeletes` trait. When a user is deleted, related attendance and visitor records are cascade-deleted, causing data loss.

### User Experience

15. **Profile editing restricted** - `ProfileController` only allows levels 1 and 2 to edit profiles. Users at levels 3 (Staff) and 4 (Attendee) cannot update their own profile information.

16. `[FIXED]` **Missing autocomplete attributes** - The login form did not have `autocomplete` attributes on email and password inputs. Autocomplete attributes have been added.

17. `[FIXED]` **Inconsistent language** - The UI previously mixed Indonesian and English. Full localization support has been implemented with English and Indonesian via Laravel's `__()` helper and JSON language files. A language switcher is available on all pages.

### Docker and Deployment

18. **No health checks** - Services in `docker-compose.yml` lack `healthcheck` configurations. The app container may attempt to connect to the database before it is ready.

19. **Nginx lacks optimization** - `nginx/default.conf` is minimal. Add gzip compression, security headers (X-Frame-Options, X-Content-Type-Options), and cache control for static assets.

20. **No task scheduler** - There is no cron job configured to run `php artisan schedule:run`. Any scheduled tasks will not execute.

21. **Missing .env.example** - The file `.env.example` is referenced in the quick start instructions but does not exist in the `src/` directory.

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
- Multi-language support with English and Indonesian localization
- Language switcher in navigation bar and login page
